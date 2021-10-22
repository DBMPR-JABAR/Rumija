<?php

namespace App\Http\Controllers\InputData;

use App\User;
use App\Model\Transactional\UPTD;
use App\Model\Transactional\PekerjaanPemeliharaan as Pemeliharaan;
use App\Http\Controllers\Controller;
// use App\Model\DWH\RawanBencana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\Datatables\DataTables;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\ItemPeralatan;



class PekerjaanController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Pekerjaan', ['createData', 'createDataMaterial', 'submitData'], ['index', 'getData', 'statusData', 'materialData', 'show', 'detailPemeliharaan', 'json'], ['editData', 'updateData', 'updateDataMaterial'], ['deleteData']);
        foreach ($roles as $role => $permission) {
            $this->middleware($role)->only($permission);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            'status' => 'false',
            'data' => []
        ];

        $pekerjaan = new Pekerjaan();
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $laporan = $pekerjaan->where('UPTD', $uptd_id);
        }
        $pekerjaan = $pekerjaan->get();

        return view('admin.input.pekerjaan.index', compact('pekerjaan'));
    }
    public static function cekMaterial($id)
    {
        $cek = DB::table('bahan_material')->where('id_pek', $id)->exists();
        return $cek;
    }

    public function sendEmail($data, $to_email, $to_name, $subject)
    {

        return Mail::send('mail.notifikasiStatusLapMandor', $data, function ($message) use ($to_name, $to_email, $subject) {
            $message->to($to_email, $to_name)->subject($subject);

            $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
        });
        // dd($mail);
    }
    public function setSendEmail($name, $id, $mandor, $jenis_pekerjaan, $uptd, $sup_mail, $status_mail, $keterangan, $to_email, $to_name, $subject)
    {

        $temporari = [
            'name' => Str::title($name),
            'id_pek' => $id,
            'nama_mandor' => Str::title($mandor),
            'jenis_pekerjaan' => Str::title($jenis_pekerjaan),
            'uptd' => Str::upper($uptd),
            'sup' => $sup_mail,
            'status' => $status_mail,
            'keterangan' => $keterangan
        ];

        // dd($subject);
        // dd($item);
        $count_email = DB::table('session_email')->where('created_at', Carbon::now()->format('Y-m-d'))->count();
        if ($count_email <= 350) {
            $email = [
                'description' => 'Laporan Pemeliharaan',
                'created_at' => Carbon::now()->format('Y-m-d')
            ];
            // dd($email);
            DB::table('session_email')->insert($email);
            $mail = $this->sendEmail($temporari, $to_email, $to_name, $subject);
        }
    }
    public function getData(Request $request)
    {

        $filter['tanggal_awal'] = Carbon::now()->subDays(14)->format('Y-m-d');
        $filter['tanggal_akhir'] = Carbon::now()->format('Y-m-d');
        // dd($tanggal_awal);

        if ($request->tanggal_awal != null) {
            $filter['tanggal_awal'] =  Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->format('Y-m-d');
        }
        if ($request->tanggal_akhir != null) {
            $filter['tanggal_akhir'] =  Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->format('Y-m-d');
        }
        // dd($filter);
        if (Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role, 'Mandor') || str_contains(Auth::user()->internalRole->role, 'Pengamat') || str_contains(Auth::user()->internalRole->role, 'Kepala Satuan Unit Pemeliharaan')) {
            if (!Auth::user()->sup_id || !Auth::user()->internalRole->uptd) {
                // dd(Auth::user()->sup_id);
                $color = "danger";
                $msg = "Lengkapi Data Terlebih dahulu";
                if (Auth::user()->internalRole->uptd == null)
                    $msg = "Hubungi admin untuk melengkapi data jabatan";

                return redirect(url('admin/profile', Auth::user()->id))->with(compact('color', 'msg'));
            }
        }
        $nama_kegiatan_pekerjaan = DB::table('utils_nama_kegiatan_pekerjaan')->get();
        $pekerjaan = DB::table('kemandoran');

        $pekerjaan = $pekerjaan->leftJoin('master_ruas_jalan', 'master_ruas_jalan.id', '=', 'kemandoran.ruas_jalan')->select('kemandoran.*', 'master_ruas_jalan.nama_ruas_jalan');
        // $pekerjaan = $pekerjaan->leftJoin('kemandoran_detail_status', 'kemandoran.id_pek', '=','kemandoran_detail_status.id_pek')->select('kemandoran.*', 'master_ruas_jalan.nama_ruas_jalan','kemandoran_detail_status.*');

        if (Auth::user() && Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $pekerjaan = $pekerjaan->where('kemandoran.uptd_id', $uptd_id);
            if (str_contains(Auth::user()->internalRole->role, 'Mandor')) {
                $pekerjaan = $pekerjaan->where('kemandoran.user_id', Auth::user()->id);
            } else if (Auth::user()->sup_id) {
                $pekerjaan = $pekerjaan->where('kemandoran.sup_id', Auth::user()->sup_id);
                if (count(Auth::user()->ruas) > 0) {
                    $pekerjaan = $pekerjaan->whereIn('ruas_jalan_id', Auth::user()->ruas->pluck('id_ruas_jalan')->toArray());
                }
            }
        }
        // $pekerjaan = $pekerjaan->whereRaw("YEAR(tanggal) BETWEEN 2021 AND 2021");

        $pekerjaan = $pekerjaan->whereBetween('tanggal', [$filter['tanggal_awal'], $filter['tanggal_akhir']]);
        $pekerjaan = $pekerjaan->where('is_deleted', 0)->latest('tglreal');
        // dd($request->uptd_filter);
        if ($request->uptd_filter != null) {
            $pekerjaan = $pekerjaan->where('kemandoran.uptd_id', $request->uptd_filter);
            $filter['uptd_filter'] = $request->uptd_filter;
        }
        $pekerjaan = $pekerjaan->paginate(700);

        foreach ($pekerjaan as $no => $data) {
            // echo "$data->id_pek<br>";

            $data->status = "";
            $data->jenis_pekerjaan = DB::table('utils_jenis_laporan')->where('id', $data->jenis_pekerjaan)->pluck('name')->first();
            $detail_adjustment = DB::table('kemandoran_detail_status')->where('id_pek', $data->id_pek);
            $input_material = $this->cekMaterial($data->id_pek);
            if ($input_material) {
                $tempuser = DB::table('users')
                    ->leftJoin('user_role', 'users.internal_role_id', '=', 'user_role.id')->where('users.id', $data->user_id);
                if ($tempuser->exists()) {
                    $tempuser = $tempuser->first();
                    $data->status = $tempuser;
                    $data->status->status = "";
                }
            }
            $data->input_material = $input_material;
            $data->keterangan_status_lap = $detail_adjustment->exists();
            if ($detail_adjustment->exists()) {
                $detail_adjustment = $detail_adjustment
                    ->leftJoin('users', 'users.id', '=', 'kemandoran_detail_status.adjustment_user_id')
                    ->leftJoin('user_role', 'users.internal_role_id', '=', 'user_role.id');

                if ($detail_adjustment->count() > 1) {
                    $detail_adjustment = $detail_adjustment->get();
                    foreach ($detail_adjustment as $num => $data1) {
                        $temp = $data1;
                    }
                    $data->status = $temp;
                    // dd($data);
                } else {
                    $detail_adjustment = $detail_adjustment->first();
                    $data->status = $detail_adjustment;
                }
                $temp = explode(" - ", $data->status->role);
                $data->status->jabatan = $temp[0];
            }

            // echo "$data->id_pek<br>";


        }
        // dd(Carbon::now());
        // print_r(Auth::user()->internal_role_id);
        // dd($pekerjaan);
        $ruas_jalan = DB::table('master_ruas_jalan');
        if (Auth::user()->internalRole->uptd) {
            $ruas_jalan = $ruas_jalan->where('uptd_id', $uptd_id);
        }
        $ruas_jalan = $ruas_jalan->get();

        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $sup = $sup->where('uptd_id', $uptd_id);
        }
        $sup = $sup->get();


        $mandor = User::where('user_role.role', 'like', '%mandor%');
        $mandor = $mandor->leftJoin('user_role', 'user_role.id', '=', 'users.internal_role_id')->select('users.*', 'user_role.id as id_role');
        if (Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role, 'Mandor') || str_contains(Auth::user()->internalRole->role, 'Pengamat') || str_contains(Auth::user()->internalRole->role, 'Kepala Satuan Unit Pemeliharaan')) {

            $mandor = $mandor->where('sup_id', Auth::user()->sup_id);
        }
        $mandor = $mandor->get();


        //dd($uptd);
        $kemandoran = DB::table('kemandoran');

        foreach ($pekerjaan as $item) {
            if ($item->mail == 1) {
                $next_user = DB::table('users')->where('internal_role_id', $item->status->parent)->where('sup_id', $item->status->sup_id)->get();
                // dd($next_user);
                $item->status->next_user = $next_user;

                $name = Str::title($item->status->name);
                $id_pek = $item->id_pek;
                $nama_mandor = Str::title($item->nama_mandor);
                $jenis_pekerjaan = Str::title($item->paket);
                $uptd = Str::upper($item->status->uptd);
                $sup_mail = $item->sup;
                $status_mail = "Submitted";
                $keterangan = "Silahkan menunggu sampai semua menyetujui / Approved";
                $subject = "Status Laporan $item->id_pek-Submitted";
                if (str_contains($item->status->status, 'Edited')) {
                    // dd($item);
                    $status_mail = $item->status->status;
                    $subject = "Status Laporan $item->id_pek-" . $item->status->status;
                }

                $to_email = $item->status->email;
                $to_name = $item->nama_mandor;
                // dd($subject);
                // dd($item);
                $mail = $this->setSendEmail($name, $id_pek, $nama_mandor, $jenis_pekerjaan, $uptd, $sup_mail, $status_mail, $keterangan, $to_email, $to_name, $subject);
                if ($item->status->next_user != "") {
                    foreach ($item->status->next_user as $no => $item1) {
                        // dd($item->email);
                        $to_email = $item1->email;
                        $to_name = $item1->name;

                        $name = Str::title($item1->name);
                        $id_pek = $item->id_pek;
                        $nama_mandor = Str::title($item->nama_mandor);
                        $jenis_pekerjaan = Str::title($item->paket);
                        $uptd = Str::upper($item->status->uptd);
                        $sup_mail = $item->sup;

                        $keterangan = "Silahkan ditindak lanjuti";

                        $mail = $this->setSendEmail($name, $id_pek, $nama_mandor, $jenis_pekerjaan, $uptd, $sup_mail, $status_mail, $keterangan, $to_email, $to_name, $subject);

                        // $mail = $this->sendEmail($temporari1, $to_email, $to_name, $subject);

                    }
                }
                if ($kemandoran->where('id_pek', $item->id_pek)->where('mail', $item->mail)->exists()) {
                    $mail['mail'] = 2;
                    $kemandoran->update($mail);
                }
            }
        }
        // $kode_otp = rand(100000, 999999);
        // echo Auth::user()->internalRole->id;
        // echo Auth::user()->sup;
        // echo Auth::user()->internalRole->role;
        // dd($pekerjaan);
        $approve = 0;
        $reject = 0;
        $submit = 0;
        $not_complete = 0;

        $rekaps = DB::table('kemandoran')
            ->leftJoin('kemandoran_detail_status', 'kemandoran_detail_status.id_pek', '=', 'kemandoran.id_pek')
            ->select('kemandoran.*', 'kemandoran_detail_status.status', DB::raw('max(kemandoran_detail_status.id ) as status_s'), DB::raw('max(kemandoran_detail_status.id ) as status_s'))
            ->groupBy('kemandoran.id_pek');
        // ->where('kemandoran_detail_status.status','Approved')

        if (Auth::user() && Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $rekaps = $rekaps->where('kemandoran.uptd_id', $uptd_id);
            if (str_contains(Auth::user()->internalRole->role, 'Mandor')) {
                $rekaps = $rekaps->where('kemandoran.user_id', Auth::user()->id);
            } else if (Auth::user()->sup_id)
                $rekaps = $rekaps->where('kemandoran.sup_id', Auth::user()->sup_id);
        }

        $rekaps = $rekaps->get();
        foreach ($rekaps as $it) {
            // echo $it->status.' | '.$it->id_pek.'<br>';

            $it->status_material = DB::table('bahan_material')->where('id_pek', $it->id_pek)->exists();

            $rekaplap = DB::table('kemandoran_detail_status')->where('id', $it->status_s)->pluck('status')->first();
            $it->status = $rekaplap;
            if (($it->status == "Approved" || $it->status == "Rejected" || $it->status == "Edited") || $it->status_material) {
                if ($it->status == "Approved") {
                    $approve += 1;
                    // echo $it->status.' | '.$it->id_pek.'<br>';
                } else if ($it->status == "Rejected" || $it->status == "Edited") {
                    $reject += 1;
                    // echo $it->status.' | '.$it->id_pek.'<br>';
                } else
                    $submit += 1;
            } else
                $not_complete += 1;

            // echo $it->id_pek.' | '.$it->status.'<br>';

        }
        // dd($rekaps);
        $sum_report = [
            "approve" => $approve,
            "reject" => $reject,
            "submit" => $submit,
            "not_complete" => $not_complete

        ];
        $jenis_laporan_pekerjaan = DB::table('utils_jenis_laporan')->get();
        return view('admin.input.pekerjaan.index', compact('pekerjaan', 'ruas_jalan', 'sup', 'mandor',  'sum_report', 'nama_kegiatan_pekerjaan', 'jenis_laporan_pekerjaan', 'filter'));
    }

    public function statusData($id)
    {
        $adjustment = DB::table('kemandoran_detail_status')
            ->Join('kemandoran', 'kemandoran.id_pek', '=', 'kemandoran_detail_status.id_pek')->where('kemandoran_detail_status.id_pek', $id)
            ->first();
        $det = $detail_adjustment = DB::table('kemandoran_detail_status')->where('id_pek', $id)->pluck('updated_at');
        $detail_adjustment = DB::table('kemandoran_detail_status')
            ->Join('kemandoran', 'kemandoran.id_pek', '=', 'kemandoran_detail_status.id_pek')
            ->leftJoin('users', 'users.id', '=', 'kemandoran_detail_status.adjustment_user_id')
            ->leftJoin('user_role', 'users.internal_role_id', '=', 'user_role.id')->where('kemandoran_detail_status.id_pek', $id)
            ->select('kemandoran_detail_status.*', 'kemandoran.*', 'users.*', 'user_role.*', 'kemandoran_detail_status.created_by as id_user_create_status')
            ->get();
        // dd($det);
        foreach ($detail_adjustment as $data) {
            $data->nama_user_create = "";
            $data->jabatan_user_create = "";
            $temp = explode(" - ", $data->role);
            $data->jabatan = $temp[0];
            // if($data)
            if ($data->id_user_create_status) {
                $temporari = User::where('users.id', $data->id_user_create_status)
                    ->leftJoin('user_role', 'users.internal_role_id', '=', 'user_role.id')->select('users.name as nama_create', 'user_role.role as jabatan_create')->first();
                $data->nama_user_create = $temporari->nama_create;
                $data->jabatan_user_create = $temporari->jabatan_create;
            }
        }
        // dd($detail_adjustment);

        return view('admin.input.pekerjaan.detail-status', compact('detail_adjustment', 'adjustment', 'det'));
    }
    public function editData($id)
    {

        $color = "danger";
        $msg = "Somethink when wrong!";
        $pekerjaan = DB::table('kemandoran')->where('id_pek', $id);
        $nama_kegiatan_pekerjaan = DB::table('utils_nama_kegiatan_pekerjaan')->get();
        if (!$pekerjaan->exists())
            return back()->with(compact('color', 'msg'));

        $pekerjaan = $pekerjaan->first();
        if (Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role, 'Mandor'))
            if (Auth::user()->id != $pekerjaan->user_id) {
                return back()->with(compact('color', 'msg'));
                // return redirect('admin/user/profile/'. auth()->user()->id)->with(['error' => 'Somethink when wrong!']);
            }

        $ruas_jalan = DB::table('master_ruas_jalan');
        // if (Auth::user()->internalRole->uptd) {
        //     $ruas_jalan = $ruas_jalan->where('uptd_id', Auth::user()->internalRole->uptd);
        // }
        // echo $pekerjaan->uptd_id;
        $ruas_jalan = $ruas_jalan->where('uptd_id', $pekerjaan->uptd_id);
        $ruas_jalan = $ruas_jalan->get();

        $sup = DB::table('utils_sup');
        $sup = $sup->where('uptd_id', $pekerjaan->uptd_id);

        $jenis = DB::table('item_pekerjaan');
        $jenis = $jenis->get();

        $mandor = DB::table('users')->where('user_role.role', 'like', 'mandor%');
        $mandor = $mandor->leftJoin('user_role', 'user_role.id', '=', 'users.internal_role_id')->select('users.*', 'user_role.id as id_role');
        if (Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role, 'Mandor') || str_contains(Auth::user()->internalRole->role, 'Pengamat') || str_contains(Auth::user()->internalRole->role, 'Kepala Satuan Unit Pemeliharaan')) {
            $mandor = $mandor->where('sup_id', Auth::user()->sup_id);
        }
        $mandor = $mandor->get();

        $sup = $sup->get();
        $uptd = DB::table('landing_uptd')->get();
        $jenis_laporan_pekerjaan = DB::table('utils_jenis_laporan')->get();

        return view('admin.input.pekerjaan.edit', compact('pekerjaan', 'ruas_jalan', 'sup', 'uptd', 'jenis', 'mandor', 'nama_kegiatan_pekerjaan', 'jenis_laporan_pekerjaan'));
    }

    public function createData(Request $req)
    {

        $pekerjaan = $req->except(['_token', 'tanggal_awal', 'tanggal_akhir', 'uptd_filter', 'lat']);
        if (strpos($req->lat, "-") !== 0) {
            $pekerjaan['lat'] = '-' . $req->lat;
        } else $pekerjaan['lat'] = $req->lat;
        // dd($pekerjaan);
        $pekerjaan['uptd_id'] = $req->uptd_id == '' ? 0 : $req->uptd_id;
        if ($pekerjaan['uptd_id'])
            $pekerjaan['uptd_id'] = str_replace('uptd', '', $pekerjaan['uptd_id']);

        if (Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role, 'Mandor')) {
            $temp[0] = Auth::user()->name;
            $temp[1] = Auth::user()->id;
        } else
            $temp = explode(",", $pekerjaan['nama_mandor']);

        $pekerjaan['ruas_jalan_id'] = $pekerjaan['ruas_jalan'];
        $pekerjaan['sup_id'] = DB::table('utils_sup')->where('kd_sup', $pekerjaan['sup'])->pluck('id')->first();
        $pekerjaan['sup'] = DB::table('utils_sup')->where('id', $pekerjaan['sup_id'])->pluck('name')->first();
        $pekerjaan['ruas_jalan'] = DB::table('master_ruas_jalan')->where('id_ruas_jalan', $pekerjaan['ruas_jalan_id'])->pluck('nama_ruas_jalan')->first();

        $pekerjaan['nama_mandor'] = $temp[0];
        $pekerjaan['user_id'] = $temp[1];


        // dd($pekerjaan['ruas_jalan']);
        $pekerjaan['created_by'] = Auth::user()->id;

        // $pekerjaan['slug'] = Str::slug($req->nama, '');
        if ($req->foto_awal != null) {
            $path = Str::snake(date("YmdHis") . ' ' . uniqid());
            $req->foto_awal->storeAs('public/pekerjaan/', $path);
            $pekerjaan['foto_awal'] = $path;
        }
        if ($req->foto_sedang != null) {
            $path = Str::snake(date("YmdHis") . ' ' . uniqid());
            $req->foto_sedang->storeAs('public/pekerjaan/', $path);
            $pekerjaan['foto_sedang'] = $path;
        }
        if ($req->foto_akhir != null) {
            $path = Str::snake(date("YmdHis") . ' ' . uniqid());
            $req->foto_akhir->storeAs('public/pekerjaan/', $path);
            $pekerjaan['foto_akhir'] = $path;
        }
        if ($req->foto_pegawai != null) {
            $path = Str::snake(date("YmdHis") . ' ' . uniqid());
            $req->foto_pegawai->storeAs('public/pekerjaan/', $path);
            $pekerjaan['foto_pegawai'] = $path;
        }
        if ($req->video != null) {
            $path = Str::snake(date("YmdHis") . ' ' . uniqid());
            $req->video->storeAs('public/pekerjaan/', $path);
            $pekerjaan['video'] = $path;
        }
        $row = DB::table('kemandoran')->select('id_pek')->orderByDesc('id_pek')->limit(1)->first();
        if ($row) {

            $nomor = intval(substr($row->id_pek, strlen('CK-'))) + 1;
        } else
            $nomor = 000001;


        $pekerjaan['tglreal'] = date('Y-m-d H:i:s');
        $pekerjaan['is_deleted'] = 0;

        $pekerjaan['id_pek'] = 'CK-' . str_pad($nomor, 6, "0", STR_PAD_LEFT);

        DB::table('kemandoran')->insert($pekerjaan);
        storeLogActivity(declarLog(1, 'Pemeliharaan Pekerjaan', $pekerjaan['id_pek'], 1));

        $color = "success";
        $msg = "Berhasil Menambah Data Pekerjaan";
        return back()->with(compact('color', 'msg'));
    }
    public function updateData(Request $req)
    {
        $pekerjaan = $req->except('_token', 'id_pek', 'lat');
        if (strpos($req->lat, "-") !== 0) {
            $pekerjaan['lat'] = '-' . $req->lat;
        } else $pekerjaan['lat'] = $req->lat;
        $pekerjaan['uptd_id'] = $req->uptd_id == '' ? 0 : $req->uptd_id;
        if ($pekerjaan['uptd_id'])
            $pekerjaan['uptd_id'] = str_replace('uptd', '', $pekerjaan['uptd_id']);

        if (Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role, 'Mandor')) {
            $temp[0] = Auth::user()->name;
            $temp[1] = Auth::user()->id;
        } else
            $temp = explode(",", $pekerjaan['nama_mandor']);

        $temp1 = explode(",", $pekerjaan['sup']);
        $temp2 = explode(",", $pekerjaan['ruas_jalan']);
        if (count($temp1) == 1) {
            $getsup = DB::table('utils_sup')->where('id', $pekerjaan['sup'])->select('id', 'name')->first();
            $temp1[0] = $getsup->name;
            $temp1[1] = $getsup->id;
        }
        if (count($temp2) == 1) {
            $getruas = DB::table('master_ruas_jalan')->where('id_ruas_jalan', $pekerjaan['ruas_jalan'])->select('id_ruas_jalan', 'nama_ruas_jalan')->first();
            $temp2[0] = $getruas->nama_ruas_jalan;
            $temp2[1] = $getruas->id_ruas_jalan;
        }
        //    dd($temp2);
        $pekerjaan['nama_mandor'] = $temp[0];
        $pekerjaan['user_id'] = $temp[1];
        $pekerjaan['sup'] = $temp1[0];
        $pekerjaan['sup_id'] = $temp1[1];
        $pekerjaan['ruas_jalan'] = $temp2[0];
        $pekerjaan['ruas_jalan_id'] = $temp2[1];
        // dd($pekerjaan['ruas_jalan']);
        $pekerjaan['updated_by'] = Auth::user()->id;

        $old = DB::table('kemandoran')->where('id_pek', $req->id_pek)->first();
        if ($req->foto_awal != null) {
            $old->foto_awal ?? Storage::delete('public/pekerjaan/' . $old->foto_awal);

            $path = Str::snake(date("YmdHis") . ' ' . uniqid());
            $req->foto_awal->storeAs('public/pekerjaan/', $path);
            $pekerjaan['foto_awal'] = $path;
        }
        if ($req->foto_sedang != null) {
            $old->foto_sedang ?? Storage::delete('public/pekerjaan/' . $old->foto_sedang);

            $path = Str::snake(date("YmdHis") . ' ' . uniqid());
            $req->foto_sedang->storeAs('public/pekerjaan/', $path);
            $pekerjaan['foto_sedang'] = $path;
        }
        if ($req->foto_akhir != null) {
            $old->foto_akhir ?? Storage::delete('public/pekerjaan/' . $old->foto_akhir);

            $path = Str::snake(date("YmdHis") . ' ' . uniqid());
            $req->foto_akhir->storeAs('public/pekerjaan/', $path);
            $pekerjaan['foto_akhir'] = $path;
        }
        if ($req->foto_pegawai != null) {
            $old->foto_pegawai ?? Storage::delete('public/pekerjaan/' . $old->foto_pegawai);

            $path = Str::snake(date("YmdHis") . ' ' . uniqid());
            $req->foto_pegawai->storeAs('public/pekerjaan/', $path);
            $pekerjaan['foto_pegawai'] = $path;
        }
        if ($req->video != null) {
            $old->video ?? Storage::delete('public/pekerjaan/' . $old->video);

            $path = Str::snake(date("YmdHis") . ' ' . uniqid());
            $req->video->storeAs('public/pekerjaan/', $path);
            $pekerjaan['video'] = $path;
        }

        DB::table('kemandoran')->where('id_pek', $req->id_pek)->update($pekerjaan);
        $kemandoran =  DB::table('kemandoran');

        $detail_adjustment =  DB::table('kemandoran_detail_status');
        if ($detail_adjustment->where('id_pek', $req->id_pek)->where('status', 'Rejected')->where('pointer', 1)->latest('updated_at')->exists()) {
            $data['pointer'] = 0;
            $update = DB::table('kemandoran_detail_status')->where('id_pek', $req->id_pek)->update($data);
            if (!$detail_adjustment->where('id_pek', $req->id_pek)->where('adjustment_user_id', Auth::user()->id)->latest('updated_at')->exists()) {
                $data['pointer'] = 0;
                $data['adjustment_user_id'] = Auth::user()->id;
                $data['status'] = "Edited";
                $data['id_pek'] = $req->id_pek;
                $data['updated_at'] = Carbon::now();
                $data['created_at'] = Carbon::now();
                $insert = $detail_adjustment->insert($data);
                if ($kemandoran->where('id_pek', $req->id_pek)->exists()) {
                    $mail['mail'] = 1;
                    // dd($mail);
                    $kemandoran->update($mail);
                }
            }
        }

        $color = "success";
        $msg = "Berhasil Mengubah Data";
        storeLogActivity(declarLog(2, 'Pemeliharaan Pekerjaan', $req->id_pek, 1));

        return redirect(route('getDataPekerjaan'))->with(compact('color', 'msg'));
    }
    public function materialData($id)
    {
        $pekerjaan = DB::table('kemandoran')->where('id_pek', $id)->first();
        // $pekerjaan = $pekerjaan->leftJoin('bahan_material', 'bahan_material.id_pek', '=', 'kemandoran.id_pek')->select('kemandoran.*', 'bahan_material.*');
        $ruas_jalan = DB::table('master_ruas_jalan');
        if (Auth::user()->internalRole->uptd) {
            $ruas_jalan = $ruas_jalan->where('uptd_id', Auth::user()->internalRole->uptd);
        }
        $ruas_jalan = $ruas_jalan->get();

        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $sup = $sup->where('uptd_id', Auth::user()->internalRole->uptd);
        }

        $jenis = DB::table('item_pekerjaan');
        $jenis = $jenis->get();

        $material1 = DB::table('bahan_material')->where('id_pek', $id)->get();
        if (count($material1) > 0) {
            $material = DB::table('bahan_material')->where('id_pek', $id)->first();
        } else {
            $material = '';
        }

        $bahan = DB::table('item_bahan');
        $bahan = $bahan->get();

        $satuan = DB::table('item_satuan');
        $satuan = $satuan->get();

        $mandor = DB::table('users')->where('user_role.role', 'like', 'mandor%');
        $mandor = $mandor->leftJoin('user_role', 'user_role.id', '=', 'users.internal_role_id')->select('users.*', 'user_role.id as id_role');
        $mandor = $mandor->where('users.id', $pekerjaan->user_id);

        $mandor = $mandor->get();

        $sup = $sup->get();
        $uptd = DB::table('landing_uptd')->get();
        // dd($pekerjaan);
        $detail_peralatan = DB::table('kemandoran_detail_peralatan')->where('id_pek', $id)->select('nama_peralatan', 'kuantitas', 'satuan')->get()->toArray();
        $detail_bahan_operasional = DB::table('kemandoran_detail_material as a')->where('a.id_pek', $id)
            ->leftJoin('item_bahan as b', 'b.no', '=', 'a.id_material')
            ->select('a.id_material', 'b.nama_item', 'a.kuantitas', 'a.satuan')->get()->toArray();
        $detail_pekerja = DB::table('kemandoran_detail_pekerja')->where('id_pek', $id)->get()->toArray();
        $detail_penghambat = DB::table('kemandoran_detail_penghambat')->where('id_pek', $id)->get()->toArray();
        // dd($detail_penghambat);
        $detail_instruksi = DB::table('kemandoran_detail_instruksi')->where('id_pek', $id)->where('user_id', Auth::user()->id)->pluck('keterangan')->first();


        $item_peralatan = ItemPeralatan::get();
        // dd($detail_instruksi);
        // dd($detail_bahan_operasional);
        return view('admin.input.pekerjaan.material', compact('pekerjaan', 'ruas_jalan', 'sup', 'uptd', 'jenis', 'mandor', 'bahan', 'material', 'satuan', 'detail_peralatan', 'detail_bahan_operasional', 'item_peralatan', 'detail_pekerja', 'detail_penghambat', 'detail_instruksi'));
    }
    public function createDataMaterial(Request $req)
    {
        $pekerjaan = $req
            ->except(
                [
                    '_token',
                    'nama_bahan',
                    'satuan', 'jum_bahan',
                    'nama_peralatan',
                    'jum_peralatan',
                    'satuan_peralatan',
                    'nama_bahan_operasional',
                    'jum_bahan_operasional',
                    'satuan_operasional',
                    'jabatan_pekerja',
                    'jum_pekerja',
                    'jenis_gangguan',
                    'start_time',
                    'end_time',
                    'akibat',
                    'keterangan_instruksi'
                ]
            );
        // dd($pekerjaan['keterangan_instruksi']);
        $pekerjaan['uptd_id'] = $req->uptd_id == '' ? 0 : $req->uptd_id;
        $pekerjaan['updated_by'] = Auth::user()->id;
        $temp = explode(",", $pekerjaan['nama_mandor']);
        $pekerjaan['nama_mandor'] = $temp[0];
        // dd($pekerjaan);
        $x = 1;
        for ($i = 0; $i < count($req->nama_bahan) - 1; $i++) {
            $jum_bahan = "jum_bahan$x";
            $nama_bahan = "nama_bahan$x";
            $satuan = "satuan$x";
            $pekerjaan[$nama_bahan] = $req->nama_bahan[$i];
            $pekerjaan[$jum_bahan] = $req->jum_bahan[$i];
            $pekerjaan[$satuan] = $req->satuan[$i];
            $x++;
        }
        for ($i = 0; $i < count($req->jum_peralatan) - 1; $i++) {
            if ($req->jum_peralatan[$i] != null) {
                $peralatan['id_pek'] = $req->id_pek;
                $temp_peralatan = explode(",", $req->nama_peralatan[$i]);
                $peralatan['id_peralatan'] = $temp_peralatan[0];
                $peralatan['nama_peralatan'] = $temp_peralatan[1];
                $peralatan['kuantitas'] = $req->jum_peralatan[$i];
                $peralatan['satuan'] = $req->satuan_peralatan[$i];
                DB::table('kemandoran_detail_peralatan')->insert($peralatan);
            }
        }
        for ($i = 0; $i < count($req->jum_bahan_operasional) - 1; $i++) {
            if ($req->jum_bahan_operasional[$i] != null) {
                $material['id_pek'] = $req->id_pek;
                $material['id_material'] = $req->nama_bahan_operasional[$i];
                $material['kuantitas'] = $req->jum_bahan_operasional[$i];
                $material['satuan'] = $req->satuan_operasional[$i];
                DB::table('kemandoran_detail_material')->insert($material);
            }
        }
        for ($i = 0; $i < count($req->jabatan_pekerja) - 1; $i++) {
            $pekerja['id_pek'] = $req->id_pek;
            $pekerja['jabatan'] = $req->jabatan_pekerja[$i];
            $pekerja['jumlah'] = $req->jum_pekerja[$i] ?: 0;
            DB::table('kemandoran_detail_pekerja')->insert($pekerja);
        }
        for ($i = 0; $i < count($req->jenis_gangguan) - 1; $i++) {
            if ($req->start_time[$i] != null) {
                $penghambat['id_pek'] = $req->id_pek;
                $penghambat['jenis_gangguan'] = $req->jenis_gangguan[$i];
                $penghambat['start_time'] = $req->start_time[$i];
                $penghambat['end_time'] = $req->end_time[$i];
                $penghambat['akibat'] = $req->akibat[$i];
                DB::table('kemandoran_detail_penghambat')->insert($penghambat);
            }
        }
        // dd($pekerjaan);
        if (str_contains(Auth::user()->internalRole->role, 'Pengamat')) {
            $keterangan_instruksi['id_pek'] = $req->id_pek;
            $keterangan_instruksi['user_id'] = Auth::user()->id;
            $keterangan_instruksi['keterangan'] = $req->keterangan_instruksi;
            DB::table('kemandoran_detail_instruksi')->insert($keterangan_instruksi);
        }
        DB::table('bahan_material')->insert($pekerjaan);
        $kemandoran =  DB::table('kemandoran');

        if ($kemandoran->where('id_pek', $req->id_pek)->where('mail', null)->exists()) {
            $mail['mail'] = 1;

            $kemandoran->update($mail);
            $detail_adjustment =  DB::table('kemandoran_detail_status');
            $data['pointer'] = 0;
            $data['adjustment_user_id'] = Auth::user()->id;
            $data['status'] = "Submitted";
            $data['id_pek'] = $req->id_pek;
            $data['updated_at'] = Carbon::now();
            $data['created_at'] = Carbon::now();
            $data['created_by'] = Auth::user()->id;
            if (str_contains(Auth::user()->internalRole->role, 'Admin')) {

                $data['adjustment_user_id'] = $temp[1];
            }

            $insert = $detail_adjustment->insert($data);
        }
        storeLogActivity(declarLog(1, 'Detail Pemeliharaan Pekerjaan', $req->id_pek, 1));

        $color = "success";
        $msg = "Berhasil Menambah Data Bahan Material";
        return redirect(route('getDataPekerjaan'))->with(compact('color', 'msg'));
    }


    public function updateDataMaterial(Request $req)
    {
        // dd($req->id_pek);
        $pekerjaan = $req
            ->except(
                '_token',
                'id_pek',
                'nama_peralatan',
                'jum_peralatan',
                'satuan_peralatan',
                'nama_bahan_operasional',
                'jum_bahan_operasional',
                'satuan_operasional',
                'jabatan_pekerja',
                'jum_pekerja',
                'jenis_gangguan',
                'start_time',
                'end_time',
                'akibat',
                'keterangan_instruksi'
            );
        $pekerjaan['uptd_id'] = $req->uptd_id == '' ? 0 : $req->uptd_id;
        $pekerjaan['updated_by'] = Auth::user()->id;
        $temp = explode(",", $pekerjaan['nama_mandor']);

        // dd($req->jenis_gangguan);
        DB::table('kemandoran_detail_peralatan')->where('id_pek', $req->id_pek)->delete();
        DB::table('kemandoran_detail_material')->where('id_pek', $req->id_pek)->delete();
        DB::table('kemandoran_detail_pekerja')->where('id_pek', $req->id_pek)->delete();
        DB::table('kemandoran_detail_penghambat')->where('id_pek', $req->id_pek)->delete();

        for ($i = 0; $i < count($req->jum_peralatan) - 1; $i++) {
            if ($req->jum_peralatan[$i] != null) {
                // dd($req->nama_peralatan[$i]);
                $peralatan['id_pek'] = $req->id_pek;
                $temp_peralatan = explode(",", $req->nama_peralatan[$i]);
                $peralatan['id_peralatan'] = $temp_peralatan[0];

                $peralatan['nama_peralatan'] = $temp_peralatan[1];
                $peralatan['kuantitas'] = $req->jum_peralatan[$i];
                $peralatan['satuan'] = $req->satuan_peralatan[$i];
                DB::table('kemandoran_detail_peralatan')->insert($peralatan);
            }
        }
        for ($i = 0; $i < count($req->jum_bahan_operasional) - 1; $i++) {
            if ($req->jum_bahan_operasional[$i] != null) {
                $material['id_pek'] = $req->id_pek;
                $material['id_material'] = $req->nama_bahan_operasional[$i];
                $material['kuantitas'] = $req->jum_bahan_operasional[$i];
                $material['satuan'] = $req->satuan_operasional[$i];
                DB::table('kemandoran_detail_material')->insert($material);
            }
        }
        for ($i = 0; $i < count($req->jabatan_pekerja) - 1; $i++) {
            $pekerja['id_pek'] = $req->id_pek;
            $pekerja['jabatan'] = $req->jabatan_pekerja[$i];
            $pekerja['jumlah'] = $req->jum_pekerja[$i] ?: 0;
            DB::table('kemandoran_detail_pekerja')->insert($pekerja);
        }
        for ($i = 0; $i < count($req->jenis_gangguan) - 1; $i++) {
            if ($req->start_time[$i] != null) {
                $penghambat['id_pek'] = $req->id_pek;
                $penghambat['jenis_gangguan'] = $req->jenis_gangguan[$i];
                $penghambat['start_time'] = $req->start_time[$i];
                $penghambat['end_time'] = $req->end_time[$i];
                $penghambat['akibat'] = $req->akibat[$i];
                DB::table('kemandoran_detail_penghambat')->insert($penghambat);
            }
        }

        if (str_contains(Auth::user()->internalRole->role, 'Pengamat')) {
            $keterangan_instruksi['keterangan'] = $req->keterangan_instruksi;
            $ketIns = DB::table('kemandoran_detail_instruksi')->where('id_pek', $req->id_pek)->where('user_id', Auth::user()->id);
            if ($ketIns->exists()) {
                $ketIns = $ketIns->update($keterangan_instruksi);
            } else {
                $keterangan_instruksi['id_pek'] = $req->id_pek;
                $keterangan_instruksi['user_id'] = Auth::user()->id;
                $ketIns = $ketIns->insert($keterangan_instruksi);
            }
        }
        $kemandoran =  DB::table('kemandoran');
        // dd($pekerjaan);

        DB::table('bahan_material')->where('id_pek', $req->id_pek)->update($pekerjaan);

        $detail_adjustment =  DB::table('kemandoran_detail_status');
        if ($detail_adjustment->where('id_pek', $req->id_pek)->exists()) {
            if ($detail_adjustment->where('status', 'Rejected')->where('pointer', 1)->latest('updated_at')->exists()) {
                $data['pointer'] = 0;
                $update = DB::table('kemandoran_detail_status')->where('id_pek', $req->id_pek)->update($data);
                if (!$detail_adjustment->where('id_pek', $req->id_pek)->where('adjustment_user_id', Auth::user()->id)->latest('updated_at')->exists()) {
                    $data['pointer'] = 0;
                    $data['adjustment_user_id'] = Auth::user()->id;
                    $data['status'] = "Edited";
                    $data['id_pek'] = $req->id_pek;
                    $data['updated_at'] = Carbon::now();
                    $data['created_at'] = Carbon::now();
                    $insert = $detail_adjustment->insert($data);
                    if ($kemandoran->where('id_pek', $req->id_pek)->exists()) {
                        $mail['mail'] = 1;

                        // dd($mail);
                        $kemandoran->update($mail);
                    }
                }
            }
        } else {
            $data['pointer'] = 0;
            $data['adjustment_user_id'] = Auth::user()->id;
            $data['status'] = "Submitted";
            $data['id_pek'] = $req->id_pek;
            $data['updated_at'] = Carbon::now();
            $data['created_at'] = Carbon::now();
            $data['created_by'] = Auth::user()->id;
            if (str_contains(Auth::user()->internalRole->role, 'Admin')) {

                $data['adjustment_user_id'] = $temp[1];
            }

            $insert = $detail_adjustment->insert($data);
        }
        // dd($kemandoran->where('id_pek', $req->id_pek)->where('mail', null)->exists());
        if ($kemandoran->where('id_pek', $req->id_pek)->where('mail', null)->exists()) {
            $mail['mail'] = 1;

            // dd($mail);
            $kemandoran->update($mail);
        }

        $color = "success";
        $msg = "Berhasil Mengubah Data Material";
        storeLogActivity(declarLog(2, 'Detail Pemeliharaan Pekerjaan', $req->id_pek, 1));

        return redirect(route('getDataPekerjaan'))->with(compact('color', 'msg'));
    }
    public function show($id)
    {

        $color = "danger";
        $msg = "Something when wrong!";
        $pekerjaan = DB::table('kemandoran')->where('kemandoran.id_pek', $id);
        if (!$pekerjaan->exists())
            return back()->with(compact('color', 'msg'));

        $pekerjaan = $pekerjaan->leftJoin('master_ruas_jalan', 'master_ruas_jalan.id_ruas_jalan', '=', 'kemandoran.ruas_jalan_id')->select('kemandoran.*', 'master_ruas_jalan.nama_ruas_jalan');
        // $pekerjaan = $pekerjaan->leftJoin('kemandoran_detail_status', 'kemandoran.id_pek', '=','kemandoran_detail_status.id_pek')->select('kemandoran.*', 'master_ruas_jalan.nama_ruas_jalan','kemandoran_detail_status.*');
        if (Auth::user() && Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $pekerjaan = $pekerjaan->where('kemandoran.uptd_id', $uptd_id);
            if (str_contains(Auth::user()->internalRole->role, 'Mandor')) {
                $pekerjaan = $pekerjaan->where('kemandoran.user_id', Auth::user()->id);
            } else if (Auth::user()->sup_id)
                $pekerjaan = $pekerjaan->where('kemandoran.sup_id', Auth::user()->sup_id);
        }

        $pekerjaan = $pekerjaan->whereRaw("YEAR(tanggal) BETWEEN 2021 AND 2021");
        $pekerjaan = $pekerjaan->where('is_deleted', 0)->latest('tglreal')->get();
        foreach ($pekerjaan as $no => $data) {
            // echo "$data->id_pek<br>";
            $data->status = "";

            $detail_adjustment = DB::table('kemandoran_detail_status')->where('id_pek', $data->id_pek);
            $input_material = $this->cekMaterial($data->id_pek);
            if ($input_material) {
                $tempuser = DB::table('users')
                    ->leftJoin('user_role', 'users.internal_role_id', '=', 'user_role.id')->where('users.id', $data->user_id);
                if ($tempuser->exists()) {
                    $tempuser = $tempuser->first();
                    $data->status = $tempuser;

                    $data->status->status = "";
                    // $data->status->status="";

                }
            }
            $data->input_material = $input_material;
            $data->keterangan_status_lap = $detail_adjustment->exists();
            if ($detail_adjustment->exists()) {

                $detail_adjustment = $detail_adjustment
                    ->leftJoin('users', 'users.id', '=', 'kemandoran_detail_status.adjustment_user_id')
                    ->leftJoin('user_role', 'users.internal_role_id', '=', 'user_role.id');

                if ($detail_adjustment->count() > 1) {
                    $detail_adjustment = $detail_adjustment->get();
                    foreach ($detail_adjustment as $num => $data1) {
                        $temp = $data1;
                    }
                    $data->status = $temp;
                    // dd($data);
                } else {
                    $detail_adjustment = $detail_adjustment->first();
                    $data->status = $detail_adjustment;
                }
                $temp = explode(" - ", $data->status->role);
                $data->status->jabatan = $temp[0];
            }

            // echo "$data->id_pek<br>";

        }


        $pekerjaan = $pekerjaan->first();
        // dd($pekerjaan);
        // if($pekerjaan->keterangan_status_lap && $pekerjaan->status->adjustment_user_id != Auth::user()->id){
        //     return back()->with(compact('color', 'msg'));
        // }
        $pekerjaan->email = DB::table('users')->where('id', $pekerjaan->user_id)->pluck('email')->first();

        if (Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role, 'Pengamat'))
            if (Auth::user()->sup_id != $pekerjaan->sup_id) {
                return back()->with(compact('color', 'msg'));
                // return redirect('admin/user/profile/'. auth()->user()->id)->with(['error' => 'Somethink when wrong!']);
            }
        $material = DB::table('bahan_material')->where('id_pek', $id)->first();
        $pekerjaan->nama_bahan = [];
        $pekerjaan->jum_bahan = [];
        $pekerjaan->satuan = [];

        // dd($material);
        for ($i = 1; $i <= 15; $i++) {
            $jum_bahan = "jum_bahan$i";
            $nama_bahan = "nama_bahan$i";
            $satuan = "satuan$i";
            if (isset($material->$jum_bahan)) {
                $pekerjaan->nama_bahan[] = $material->$nama_bahan;
                $pekerjaan->jum_bahan[] = $material->$jum_bahan;
                $pekerjaan->satuan[] = $material->$satuan;
            }
        }
        $detail = "";
        $detail = DB::table('kemandoran_detail_status')->where('id_pek', $id);
        if ($detail->where('adjustment_user_id', Auth::user()->id)->exists() && $pekerjaan->status->adjustment_user_id == Auth::user()->id) {
            $detail = $detail->latest('updated_at')->first();
            $id_pek = $pekerjaan->id_pek;
            $nama_mandor = Str::title($pekerjaan->nama_mandor);
            $jenis_pekerjaan = Str::title($pekerjaan->paket);
            $uptd = Str::upper($pekerjaan->status->uptd);
            $sup_mail = $pekerjaan->sup;
            $status_mail = "di " . $pekerjaan->status->status . "<br> oleh " . $pekerjaan->status->name . " - " . $pekerjaan->status->role;
            $subject = "Status Laporan " . $pekerjaan->id_pek . " - " . $pekerjaan->status->status;
            $pekerjaan->status->next_user = "";

            if (str_contains($pekerjaan->status->status, "Approved") || str_contains($pekerjaan->status->status, "Edited")) {
                if (str_contains($pekerjaan->status->jabatan, "Mandor") || str_contains($pekerjaan->status->jabatan, "Pengamat")) {
                    $next_user = DB::table('users')->where('internal_role_id', $pekerjaan->status->parent)->where('sup_id', $pekerjaan->status->sup_id)->get();
                } else {
                    $next_user = DB::table('users')->where('internal_role_id', $pekerjaan->status->parent)->get();
                }

                // dd($next_user);
                $pekerjaan->status->next_user = $next_user;
                // dd($pekerjaan);
                $keterangan_mandor = "Silahkan menunggu sampai semua di terima / Approved";

                // dd($pekerjaan->status->next_user);

            } else if (str_contains($pekerjaan->status->status, "Rejected")) {
                $before_user = DB::table('kemandoran_detail_status')->where('kemandoran_detail_status.id_pek', $id)->where('kemandoran_detail_status.adjustment_user_id', '!=', $pekerjaan->user_id)->where('kemandoran_detail_status.adjustment_user_id', '!=', $pekerjaan->status->adjustment_user_id)->groupBy('adjustment_user_id')
                    ->leftJoin('users', 'users.id', '=', 'kemandoran_detail_status.adjustment_user_id')->get();

                // dd($before_user);

                $pekerjaan->status->next_user = $before_user;
                $keterangan_mandor = "Silahkan ditindak lanjuti";
            }
            // dd($pekerjaan->status->next_user);
            if ($pekerjaan->status->next_user != "") {
                foreach ($pekerjaan->status->next_user as $no => $temp) {
                    $to_email = $temp->email;
                    $to_name = $temp->name;
                    $keterangan = "Silahkan ditindak lanjuti";

                    $name = Str::title($temp->name);

                    $mail = $this->setSendEmail($name, $id_pek, $nama_mandor, $jenis_pekerjaan, $uptd, $sup_mail, $status_mail, $keterangan, $to_email, $to_name, $subject);
                }
            }
            $name = Str::title($pekerjaan->nama_mandor);
            $to_email = $pekerjaan->email;
            $to_name = $pekerjaan->nama_mandor;
            $mail = $this->setSendEmail($name, $id_pek, $nama_mandor, $jenis_pekerjaan, $uptd, $sup_mail, $status_mail, $keterangan_mandor, $to_email, $to_name, $subject);
        }
        $peralatan = DB::table('kemandoran_detail_peralatan as a')->where('a.id_pek', $id)
            ->leftJoin('item_peralatan as b', 'b.id', '=', 'a.id_peralatan')->select('b.nama_peralatan', 'a.kuantitas', 'a.satuan')->get();
        $detail_bahan_operasional = DB::table('kemandoran_detail_material as a')->where('a.id_pek', $id)
            ->leftJoin('item_bahan as b', 'b.no', '=', 'a.id_material')
            ->select('a.id_material', 'b.nama_item', 'a.kuantitas', 'a.satuan')->get();
        $detail_pekerja = DB::table('kemandoran_detail_pekerja as a')->select('jabatan', 'jumlah')->where('a.id_pek', $id)->get();
        $detail_penghambat = DB::table('kemandoran_detail_penghambat as a')->where('a.id_pek', $id)->get();
        $pekerjaan->jenis_pekerjaan = DB::table('utils_jenis_laporan')->where('id', $pekerjaan->jenis_pekerjaan)->pluck('name')->first();
        $detail_instruksi = DB::table('kemandoran_detail_instruksi')->where('id_pek', $id)->where('user_id', Auth::user()->id)->pluck('keterangan')->first();

        // dd($pekerjaan);
        // dd($pekerjaan);
        return view('admin.input.pekerjaan.show', compact('pekerjaan', 'material', 'detail', 'peralatan', 'detail_bahan_operasional', 'detail_pekerja', 'detail_penghambat', 'detail_instruksi'));
    }
    public function detailPemeliharaan($id)
    {

        $color = "danger";
        $msg = "Something when wrong!";
        $pekerjaan = DB::table('kemandoran')->where('kemandoran.id_pek', $id);
        if (!$pekerjaan->exists())
            return back()->with(compact('color', 'msg'));

        $pekerjaan = $pekerjaan->first();
        $pekerjaan->nama_bahan = [];
        $material = DB::table('bahan_material')->where('id_pek', $id)->first();
        if ($material) {
            for ($i = 1; $i <= 15; $i++) {
                $jum_bahan = "jum_bahan$i";
                $nama_bahan = "nama_bahan$i";
                $satuan = "satuan$i";
                if ($material->$jum_bahan != null) {
                    $pekerjaan->nama_bahan[] = $material->$nama_bahan;
                    $pekerjaan->jum_bahan[] = $material->$jum_bahan;
                    $pekerjaan->satuan[] = $material->$satuan;
                }
            }
        }
        $peralatan = DB::table('kemandoran_detail_peralatan as a')->where('a.id_pek', $id)
            ->leftJoin('item_peralatan as b', 'b.id', '=', 'a.id_peralatan')->select('b.nama_peralatan', 'a.kuantitas', 'a.satuan')->get();
        $detail_bahan_operasional = DB::table('kemandoran_detail_material as a')->where('a.id_pek', $id)
            ->leftJoin('item_bahan as b', 'b.no', '=', 'a.id_material')
            ->select('a.id_material', 'b.nama_item', 'a.kuantitas', 'a.satuan')->get();
        $detail_pekerja = DB::table('kemandoran_detail_pekerja as a')->select('jabatan', 'jumlah')->where('a.id_pek', $id)->get();
        $detail_penghambat = DB::table('kemandoran_detail_penghambat as a')->where('a.id_pek', $id)->get();

        // dd($peralatan);
        $pekerjaan->jenis_pekerjaan = DB::table('utils_jenis_laporan')->where('id', $pekerjaan->jenis_pekerjaan)->pluck('name')->first();

        // dd($pekerjaan->jenis_pekerjaan);
        // dd($pekerjaan);


        return view('admin.input.pekerjaan.detail_pekerjaan', compact('pekerjaan', 'material', 'peralatan', 'detail_bahan_operasional', 'detail_pekerja', 'detail_penghambat'));
    }
    public function jugmentLaporan(Request $request, $id)
    {
        // dd($id);
        if (str_contains($request->input('status'), 'Rejected')) {
            $validator = Validator::make($request->all(), [
                'keterangan' => 'required'
            ]);
            if ($validator->fails()) {
                $color = "danger";
                $msg = "Keterangan Tidak Boleh di Kosongkan!";
                return redirect(route('jugmentDataPekerjaan', $id))->with(compact('color', 'msg'));
            }
            // $this->validate($request,['keterangan' => 'required']);
        }

        $data['status'] = $request->input('status');
        $data['description'] = $request->input('keterangan') ?: null;
        $data['adjustment_user_id'] = Auth::user()->id;
        // dd($request->keterangan_instruksi);
        $kemandoran = DB::table('kemandoran_detail_status');
        if ($kemandoran->where('id_pek', $id)->where('adjustment_user_id', Auth::user()->id)->where('pointer', 1)->exists()) {
            $data['updated_at'] = Carbon::now();
            $kemandoran = $kemandoran->where('id_pek', $id)->latest('updated_at')->update($data);
        } else {

            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
            $data['id_pek'] = $id;
            $data['pointer'] = 1;
            // dd($data);
            $kemandoran = DB::table('kemandoran_detail_status')->insert($data);
        }
        if ($kemandoran) {
            //redirect dengan pesan sukses
            if (str_contains(Auth::user()->internalRole->role, 'Pengamat') || str_contains(Auth::user()->internalRole->role, 'Kepala Satuan Unit Pemeliharaan')) {
                $keterangan_instruksi['keterangan'] = $request->keterangan_instruksi;
                $ketIns = DB::table('kemandoran_detail_instruksi')->where('id_pek', $request->id_pek)->where('user_id', Auth::user()->id);
                if ($ketIns->exists()) {
                    $ketIns = $ketIns->update($keterangan_instruksi);
                } else {
                    $keterangan_instruksi['id_pek'] = $id;
                    $keterangan_instruksi['user_id'] = Auth::user()->id;
                    $ketIns = $ketIns->insert($keterangan_instruksi);
                }
            }
            $color = "success";
            $msg = "Data Berhasil Diupdate!";
            storeLogActivity(declarLog(5, 'Pemeliharaan Pekerjaan', $id, 1));

            return redirect(route('jugmentDataPekerjaan', $id))->with(compact('color', 'msg'));
        } else {
            //redirect dengan pesan error
            $color = "danger";
            $msg = "Data Tidak ada yang Diupdate!";
            storeLogActivity(declarLog(5, 'Pemeliharaan Pekerjaan', $id));

            return redirect(route('jugmentDataPekerjaan', $id))->with(compact('color', 'msg'));
        }


        // dd($request);
    }
    public function deleteData($id)
    {
        // $temp = DB::table('kemandoran')->where('id',$id)->first();

        $old = Pemeliharaan::firstOrNew(['id_pek' => $id]);
        // dd($old);
        $old->is_deleted = 1;
        $old->save();
        storeLogActivity(declarLog(3, 'Pemeliharaan Pekerjaan', $old->id_pek, 1));
        // dd($old->id_pek);

        $color = "success";
        $msg = "Berhasil Menghapus Data Pekerjaan";
        return redirect(route('getDataPekerjaan'))->with(compact('color', 'msg'));
    }
    public function getPekerjaanTrash()
    {
        $pekerjaan = Pemeliharaan::where('is_deleted', 1)->latest('tglreal')->paginate(700);
        // dd($pekerjaan);
        return view('admin.input.pekerjaan.trash', compact('pekerjaan'));
    }
    public function restoreData($id)
    {

        $old = Pemeliharaan::firstOrNew(['id_pek' => $id]);
        //    dd($old);
        $old->is_deleted = 0;
        $old->save();
        storeLogActivity(declarLog(4, 'Pemeliharaan Pekerjaan', $old->id_pek, 1));

        $color = "success";
        $msg = "Berhasil Mengembalikan Data Pekerjaan";
        return redirect(route('getPekerjaanTrash'))->with(compact('color', 'msg'));
    }
    public function submitData($id)
    {
        // $temp = DB::table('kemandoran')->where('id',$id)->first();
        $param['rule'] = 'KSUP';
        $pekerjaan = DB::table('kemandoran')->where('id_pek', $id)->update($param);
        $material = DB::table('bahan_material')->where('id_pek', $id)->update($param);

        $color = "success";
        $msg = "Berhasil Melakukan Submit Data Material";
        return redirect(route('getDataPekerjaan'))->with(compact('color', 'msg'));
    }
    public function laporanPekerjaan()
    {
        $ruas = DB::table('master_ruas_jalan')->select('kd_sppjj', 'nm_sppjj');
        if (Auth::user() && Auth::user()->sup_id) {
            $get_kd = DB::table('utils_sup')->where('id', Auth::user()->sup_id)->select('kd_sup')->first();
            // dd($get_kd->kd_sup);
        }
        return view('admin.input.pekerjaan.laporan-pekerjaan');
    }
    public function laporanEntry(Request $request)
    {
        $data = UPTD::whereBetween('id', [1, 6]);
        if ($request->uptd_filter != null || Auth::user()->internalRole->uptd != null) {
            if (Auth::user()->internalRole->uptd != null) {
                $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
                $data = $data->where('id', $uptd_id);
            } else
                $data = $data->where('id', $request->uptd_filter);
        }
        $data = $data->get();
        // dd($data->library_sup->toArray());
        $filter = [
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir
        ];
        // dd($filter);
        storeLogActivity(declarLog(6, 'Rekap Entry Pemeliharaan', $request->tanggal_awal . ' s/d ' . $request->tanggal_akhir, 1));

        return view('pdf.laporan_summary_pekerjaan', compact('data', 'filter'));
    }
    public function arrOne($var1, $var2, $var3)
    {
        $arrOne = (object)[
            $var1 => [],
            $var2 => [],
            $var3 => []
        ];
        return $arrOne;
    }
    public function arrTwo($var1, $var2, $var3)
    {
        $arrTwo = (object)[
            $var1 => "",
            $var2 => "",
            $var3 => ""

        ];
        return $arrTwo;
    }
    public function generateLaporanPekerjaan(Request $request)
    {
        $color = "danger";
        $msg = "Data tidak ada";
        $this->validate($request, [
            'ruas_jalan' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        $kemandoran = DB::table('kemandoran')->where('ruas_jalan_id', $request->ruas_jalan);
        if ($kemandoran->exists()) {
            $kemandoran = $kemandoran->whereBetween('tanggal', [$request->start_date, $request->end_date])->get()->toArray();
            if (count($kemandoran) == 0) {
                storeLogActivity(declarLog(6, 'Pemeliharaan (BHS)', ''));
                return back()->with(compact('color', 'msg'));
            }
        } else {
            storeLogActivity(declarLog(6, 'Pemeliharaan (BHS)', ''));
            return back()->with(compact('color', 'msg'));
        }
        $x = 0;
        // dd($kemandoran);
        for ($i = 0; $i < count($kemandoran); $i++) {

            $mat = $this->arrTwo('nama_bahan', 'satuan', 'jum_bahan');
            // $inst = $this->arrTwo('user_id','jab','keterangan');

            $kemandoran[$i]->material_dipakai = "";
            $kemandoran[$i]->tenaga_kerja = DB::table('kemandoran_detail_pekerja')->where('id_pek', $kemandoran[$i]->id_pek)->select('jabatan', 'jumlah')->get()->toArray();
            $kemandoran[$i]->satuan_hasil = DB::table('utils_nama_kegiatan_pekerjaan')->where('name', $kemandoran[$i]->paket)->pluck('satuan')->first();
            $kemandoran[$i]->penghambat = DB::table('kemandoran_detail_penghambat')->where('id_pek', $kemandoran[$i]->id_pek)->select('jenis_gangguan', 'start_time', 'end_time', 'akibat')->get()->toArray();
            $kemandoran[$i]->instruksi = DB::table('kemandoran_detail_instruksi')->where('id_pek', $kemandoran[$i]->id_pek)->select('user_id', 'keterangan')->get()->toArray();

            $material = DB::table('bahan_material')->where('id_pek', $kemandoran[$i]->id_pek)->first();
            if ($material) {
                $arrTwo[$i] = [];
                for ($z = 1; $z <= 15; $z++) {
                    $jum_bahan = "jum_bahan$z";
                    $nama_bahan = "nama_bahan$z";
                    $satuan = "satuan$z";
                    if ($material->$jum_bahan != null) {
                        $mat->nama_bahan = $material->$nama_bahan;
                        $mat->jum_bahan = $material->$jum_bahan;
                        $mat->satuan = $material->$satuan;
                        // $kemandoran[$i]->material_dipakai = $mat;

                        $arrTwo[$i][] = (object)[
                            "nama_bahan" => $material->$nama_bahan,
                            "jum_bahan" => $material->$jum_bahan,
                            "satuan" => $material->$satuan
                        ];
                    }
                }
                $kemandoran[$i]->material_dipakai = $arrTwo[$i];
            }
            // dd($arrTwo[$i]);
            $kemandoran[$i]->material_operasional = DB::table('kemandoran_detail_material as a')
                ->leftjoin('item_bahan as b', 'a.id_material', '=', 'b.no')
                ->where('a.id_pek', $kemandoran[$i]->id_pek)->select('b.nama_item', 'a.kuantitas', 'a.satuan')->get()->toArray();

            $kemandoran[$i]->peralatan_operasional = DB::table('kemandoran_detail_peralatan as a')
                ->leftjoin('item_peralatan as b', 'a.id_peralatan', '=', 'b.id')
                ->where('a.id_pek', $kemandoran[$i]->id_pek)->select('b.nama_peralatan', 'a.kuantitas', 'a.satuan')->get()->toArray();

            $kemandoran[$i]->jenis_pekerjaan = DB::table('utils_jenis_laporan')->where('id', $kemandoran[$i]->jenis_pekerjaan)->pluck('name')->first();
            // $laporan->tanggal = $kemandoran[$i]->tanggal;
            $laporan[$kemandoran[$i]->tanggal][] = (object) $kemandoran[$i];
        }
        // dd($kemandoran);
        $temporari = [];
        foreach ($laporan as $item => $item) {
            //declare tenaga kerja
            $tempkerja[$item] = $this->arrOne('jabatan', 'satuan', 'jumlah');
            $tempkerjafix[$item] = $this->arrOne('jabatan', 'satuan', 'jumlah');
            $tempkerjafixed[$item] = [];

            //declare hasil yang di capai
            $temphasilfix[$item] = $this->arrOne('jenis', 'satuan', 'perkiraan');

            //declare penghambat pekerjaan
            $temppenghambat[$item] = $this->arrOne('jenis_gangguan', 'start_time', 'akibat');
            $temppenghambatfix = [];

            //declare material
            $tempmaterial[$item] = $this->arrOne('nama_bahan', 'satuan', 'jum_bahan');
            $tempmaterialfix[$item] = $this->arrOne('nama_bahan', 'satuan', 'jum_bahan');

            //declare bahan operasional
            $tempmaterialoperasional[$item] = $this->arrOne('nama_item', 'satuan', 'kuantitas');
            $tempmaterialoperasionalfix[$item] = $this->arrOne('nama_item', 'satuan', 'kuantitas');

            //declare peralatan operasional
            $tempperalatanoperasional[$item] = $this->arrOne('nama_peralatan', 'satuan', 'kuantitas');
            $tempperalatanoperasionalfix[$item] = $this->arrOne('nama_peralatan', 'satuan', 'kuantitas');

            //declare peralatan operasional
            $tempinstruksi[$item] = $this->arrOne('user_id', 'jabatan', 'keterangan');
            $tempinstruksifix[$item] = $this->arrOne('user_id', 'jabatan', 'keterangan');

            $con = [];
            foreach ($laporan[$item] as $item1 => $item1) {
                $temporari[$item] = (object)[
                    'uptd' => 'III',
                    'sub_kegiatan' => 'BOBONGKAR',
                    'sppj_wilayah' => 'GARUT 2',
                    'tanggal_hari' => '08-08-2021/Rabu',
                    'ruas_jalan' => 'Ahmad Yani',
                    'km' => 12,
                    'hal_ke' => count($laporan),
                    'tenaga_kerja' => '',
                    'hasil_kerja' => ''
                ];

                // $temporari[$item]=$laporan[$item][$item1];
                $temporari[$item]->uptd = $laporan[$item][$item1]->uptd_id;
                $sup = str_replace('SUP ', '', $laporan[$item][$item1]->sup);
                $sup = str_replace('SPP ', '', $sup);
                $temporari[$item]->sub_kegiatan = $laporan[$item][$item1]->sub_kegiatan;
                $temporari[$item]->sppj_wilayah = $sup;
                $timestamp = strtotime($laporan[$item][$item1]->tanggal);
                setlocale(LC_TIME, "id");
                $temporari[$item]->tanggal_hari = $item . '/' . utf8_encode(strftime('%A', $timestamp));
                $temporari[$item]->ruas_jalan = $laporan[$item][$item1]->ruas_jalan;
                $temporari[$item]->km = $laporan[$item][$item1]->lokasi;

                $temphasilfix[$item]->jenis_pekerjaan[] = $laporan[$item][$item1]->jenis_pekerjaan;
                $temphasilfix[$item]->jenis[] = $laporan[$item][$item1]->paket;
                $temphasilfix[$item]->satuan[] = $laporan[$item][$item1]->satuan_hasil;
                $temphasilfix[$item]->perkiraan[] = $laporan[$item][$item1]->perkiraan_kuantitas;

                //gabungkan tenaga kerja
                if ($laporan[$item][$item1]->tenaga_kerja) {
                    for ($v = 0; $v < count($laporan[$item][$item1]->tenaga_kerja); $v++) {
                        $tempkerja[$item]->jabatan[] =  $laporan[$item][$item1]->tenaga_kerja[$v]->jabatan;
                        $tempkerja[$item]->jumlah[] =  $laporan[$item][$item1]->tenaga_kerja[$v]->jumlah;
                    }
                }

                //gabungkan penghambat
                if ($laporan[$item][$item1]->penghambat) {
                    for ($v = 0; $v < count($laporan[$item][$item1]->tenaga_kerja); $v++) {
                        $temppenghambat[$item]->jenis_gangguan[] =  $laporan[$item][$item1]->penghambat[$v]->jenis_gangguan;
                        $temppenghambat[$item]->start_time[] =  $laporan[$item][$item1]->penghambat[$v]->start_time;
                        $temppenghambat[$item]->end_time[] =  $laporan[$item][$item1]->penghambat[$v]->end_time;
                        $temppenghambat[$item]->akibat[] =  $laporan[$item][$item1]->penghambat[$v]->akibat;
                        $temppenghambatfix[$laporan[$item][$item1]->penghambat[$v]->jenis_gangguan] = $this->arrOne('start_time', 'end_time', 'akibat');
                    }
                }

                //gabungkan material
                if ($laporan[$item][$item1]->material_dipakai) {
                    for ($v = 0; $v < count($laporan[$item][$item1]->material_dipakai); $v++) {
                        $tempmaterial[$item]->nama_bahan[] =  $laporan[$item][$item1]->material_dipakai[$v]->nama_bahan;
                        $tempmaterial[$item]->jum_bahan[] =  $laporan[$item][$item1]->material_dipakai[$v]->jum_bahan;
                        $tempmaterial[$item]->satuan[] =  $laporan[$item][$item1]->material_dipakai[$v]->satuan;
                    }
                }

                //gabungkan material operasional
                if ($laporan[$item][$item1]->material_operasional) {
                    for ($v = 0; $v < count($laporan[$item][$item1]->material_operasional); $v++) {
                        $tempmaterialoperasional[$item]->nama_item[] =  $laporan[$item][$item1]->material_operasional[$v]->nama_item;
                        $tempmaterialoperasional[$item]->kuantitas[] =  $laporan[$item][$item1]->material_operasional[$v]->kuantitas;
                        $tempmaterialoperasional[$item]->satuan[] =  $laporan[$item][$item1]->material_operasional[$v]->satuan;
                    }
                }

                //gabungkan peralatan operasional
                if ($laporan[$item][$item1]->peralatan_operasional) {
                    for ($v = 0; $v < count($laporan[$item][$item1]->peralatan_operasional); $v++) {
                        $tempperalatanoperasional[$item]->nama_peralatan[] =  $laporan[$item][$item1]->peralatan_operasional[$v]->nama_peralatan;
                        $tempperalatanoperasional[$item]->kuantitas[] =  $laporan[$item][$item1]->peralatan_operasional[$v]->kuantitas;
                        $tempperalatanoperasional[$item]->satuan[] =  $laporan[$item][$item1]->peralatan_operasional[$v]->satuan;
                    }
                }

                //gabungkan instruksi
                if ($laporan[$item][$item1]->instruksi) {
                    for ($v = 0; $v < count($laporan[$item][$item1]->instruksi); $v++) {
                        $tempinstruksi[$item]->user_id[] =  $laporan[$item][$item1]->instruksi[$v]->user_id;
                        $tempinstruksi[$item]->keterangan[] =  $laporan[$item][$item1]->instruksi[$v]->keterangan;
                    }
                }
            }
            // dd($tempinstruksi[$item]);
            //menghilangkan redundan tenaga kerja
            for ($j = 0; $j < count($tempkerja[$item]->jabatan); $j++) {
                $pointer = null;
                // echo $tempkerja[$item]->jabatan[$j]. '|||';
                for ($x = 0; $x < count($tempkerjafix[$item]->jabatan); $x++) {
                    $pointer = null;
                    if ($tempkerjafix[$item]->jabatan[$x] == $tempkerja[$item]->jabatan[$j]) {
                        $pointer = 1;
                        break;
                    }
                }
                if ($pointer) {
                    $tempkerjafix[$item]->jumlah[$x] = $tempkerjafix[$item]->jumlah[$x] + $tempkerja[$item]->jumlah[$j];
                } else {
                    $tempkerjafix[$item]->jabatan[$x] = $tempkerja[$item]->jabatan[$j];
                    $tempkerjafix[$item]->jumlah[$x] = $tempkerja[$item]->jumlah[$j];
                }
            }

            for ($x = 0; $x < count($temppenghambat[$item]->jenis_gangguan); $x++) {
                $temppenghambatfix[$temppenghambat[$item]->jenis_gangguan[$x]]->start_time[] = $temppenghambat[$item]->start_time[$x];
                $temppenghambatfix[$temppenghambat[$item]->jenis_gangguan[$x]]->end_time[] = $temppenghambat[$item]->end_time[$x];
                $temppenghambatfix[$temppenghambat[$item]->jenis_gangguan[$x]]->akibat[] = $temppenghambat[$item]->akibat[$x];
            }

            //menghilangkan redundan material
            for ($j = 0; $j < count($tempmaterial[$item]->nama_bahan); $j++) {
                $pointer = null;
                // echo $tempmaterial[$item]->nama_bahan[$j]. '|||';
                for ($x = 0; $x < count($tempmaterialfix[$item]->nama_bahan); $x++) {
                    $pointer = null;
                    if ($tempmaterialfix[$item]->nama_bahan[$x] == $tempmaterial[$item]->nama_bahan[$j]) {
                        $pointer = 1;
                        break;
                    }
                }
                if ($pointer) {
                    $tempmaterialfix[$item]->jum_bahan[$x] = $tempmaterialfix[$item]->jum_bahan[$x] + $tempmaterial[$item]->jum_bahan[$j];
                } else {
                    $tempmaterialfix[$item]->nama_bahan[$x] = $tempmaterial[$item]->nama_bahan[$j];
                    $tempmaterialfix[$item]->jum_bahan[$x] = $tempmaterial[$item]->jum_bahan[$j];
                    $tempmaterialfix[$item]->satuan[$x] = $tempmaterial[$item]->satuan[$j];
                }
                // $tempmaterialfixed[$item][$tempmaterialfix[$item]->jabatan] = $tempmaterialfix[$item]->jumlah;
            }

            //menghilangkan redundan material operasional
            for ($j = 0; $j < count($tempmaterialoperasional[$item]->nama_item); $j++) {
                $pointer = null;
                // echo $tempmaterialoperasional[$item]->nama_item[$j]. '|||';
                for ($x = 0; $x < count($tempmaterialoperasionalfix[$item]->nama_item); $x++) {
                    $pointer = null;
                    if ($tempmaterialoperasionalfix[$item]->nama_item[$x] == $tempmaterialoperasional[$item]->nama_item[$j]) {
                        $pointer = 1;
                        break;
                    }
                }
                if ($pointer) {
                    $tempmaterialoperasionalfix[$item]->kuantitas[$x] = $tempmaterialoperasionalfix[$item]->kuantitas[$x] + $tempmaterialoperasional[$item]->kuantitas[$j];
                } else {
                    $tempmaterialoperasionalfix[$item]->nama_item[$x] = $tempmaterialoperasional[$item]->nama_item[$j];
                    $tempmaterialoperasionalfix[$item]->kuantitas[$x] = $tempmaterialoperasional[$item]->kuantitas[$j];
                    $tempmaterialoperasionalfix[$item]->satuan[$x] = $tempmaterialoperasional[$item]->satuan[$j];
                }
            }

            //menghilangkan redundan peralatan operasional
            for ($j = 0; $j < count($tempperalatanoperasional[$item]->nama_peralatan); $j++) {
                $pointer = null;
                // echo $tempperalatanoperasional[$item]->nama_peralatan[$j]. '|||';
                for ($x = 0; $x < count($tempperalatanoperasionalfix[$item]->nama_peralatan); $x++) {
                    $pointer = null;
                    if ($tempperalatanoperasionalfix[$item]->nama_peralatan[$x] == $tempperalatanoperasional[$item]->nama_peralatan[$j]) {
                        $pointer = 1;
                        break;
                    }
                }
                if ($pointer) {
                    $tempperalatanoperasionalfix[$item]->kuantitas[$x] = $tempperalatanoperasionalfix[$item]->kuantitas[$x] + $tempperalatanoperasional[$item]->kuantitas[$j];
                } else {
                    $tempperalatanoperasionalfix[$item]->nama_peralatan[$x] = $tempperalatanoperasional[$item]->nama_peralatan[$j];
                    $tempperalatanoperasionalfix[$item]->kuantitas[$x] = $tempperalatanoperasional[$item]->kuantitas[$j];
                    $tempperalatanoperasionalfix[$item]->satuan[$x] = $tempperalatanoperasional[$item]->satuan[$j];
                }
                // $tempperalatanoperasionalfixed[$item][$tempperalatanoperasionalfix[$item]->jabatan] = $tempperalatanoperasionalfix[$item]->jumlah;
            }

            for ($j = 0; $j < count($tempinstruksi[$item]->user_id); $j++) {
                $pointer = null;
                // echo $tempinstruksi[$item]->user_id[$j]. '|||';
                for ($x = 0; $x < count($tempinstruksifix[$item]->user_id); $x++) {
                    $pointer = null;
                    if ($tempinstruksifix[$item]->user_id[$x] == $tempinstruksi[$item]->user_id[$j]) {
                        $pointer = 1;
                        break;
                    }
                }
                if ($pointer) {
                    $cek = $tempinstruksifix[$item]->keterangan[$x];
                    $tempinstruksifix[$item]->keterangan[$x] = $cek . ', ' . $tempinstruksi[$item]->keterangan[$j];
                } else {
                    $tempinstruksifix[$item]->user_id[$x] = $tempinstruksi[$item]->user_id[$j];
                    $tempinstruksifix[$item]->keterangan[$x] = $tempinstruksi[$item]->keterangan[$j];

                    $getusr = User::where('id', $tempinstruksi[$item]->user_id[$j])->first();
                    if ($getusr->exists()) {
                        // dd($getusr);
                        if (str_contains($getusr->internalRole->role, 'Pengamat')) {
                            $tempinstruksifix[$item]->jabatan[$x] = "PENGAMAT";
                        } else
                            $tempinstruksifix[$item]->jabatan[$x] = "KSPPJJ";

                        $tempinstruksifix[$item]->nama[$x] = $getusr->name;
                    }
                }
            }

            // dd($tempinstruksifix);
            $temporari[$item]->tenaga_kerja = $tempkerjafix[$item];
            $temporari[$item]->hasil_kerja = $temphasilfix[$item];
            $temporari[$item]->penghambat = $temppenghambatfix;
            $temporari[$item]->material_dipakai = $tempmaterialfix[$item];
            $temporari[$item]->material_operasional = $tempmaterialoperasionalfix[$item];
            $temporari[$item]->peralatan_operasional = $tempperalatanoperasionalfix[$item];
            $temporari[$item]->instruksi = $tempinstruksifix[$item];
        }

        // dd($laporan);

        // dd(count($laporan));
        // dd($ruas);
        storeLogActivity(declarLog(6, 'Pemeliharaan (BHS)',  $sup . '/' . $kemandoran[0]->ruas_jalan, 1));

        return view('pdf.laporan_pekerjaan', compact('temporari'));
    }
    public function sqlreportrekap($id)
    {
        $getcountsup = DB::table('kemandoran')
            ->leftjoin('utils_sup', 'kemandoran.sup_id', '=', 'utils_sup.id')
            ->select(DB::raw('kemandoran.uptd_id,kemandoran.sup_id,utils_sup.name,count(*) as data_sup_count'))->where('kemandoran.uptd_id', $id)->groupBy('kemandoran.sup_id')->whereBetween('kemandoran.tglreal', ['2021-03-01', '2021-04-16'])->get();
        return ($getcountsup);
    }
    public function reportrekap()
    {
        $getuptd = DB::table('kemandoran')->select(DB::raw('uptd_id,count(*) as data_uptd_count'))->groupBy('uptd_id')->whereBetween('tglreal', ['2021-03-01', '2021-04-16'])->get();
        $getuptd1 = $this->sqlreportrekap(1);
        $getuptd2 = $this->sqlreportrekap(2);
        $getuptd3 = $this->sqlreportrekap(3);
        $getuptd4 = $this->sqlreportrekap(4);
        $getuptd5 = $this->sqlreportrekap(5);
        $getuptd6 = $this->sqlreportrekap(6);

        $report = [
            'uptd' => $getuptd,
            'uptd1' => $getuptd1,
            'uptd2' => $getuptd2,
            'uptd3' => $getuptd3,
            'uptd4' => $getuptd4,
            'uptd5' => $getuptd5,
            'uptd6' => $getuptd6
        ];

        // dd($report);
    }

    public function json(Request $request)
    {
        $pekerjaan = DB::table('kemandoran');

        $pekerjaan = $pekerjaan->leftJoin('master_ruas_jalan', 'master_ruas_jalan.id', '=', 'kemandoran.ruas_jalan')->select('kemandoran.*', 'master_ruas_jalan.nama_ruas_jalan');

        if (Auth::user() && Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $pekerjaan = $pekerjaan->where('kemandoran.uptd_id', $uptd_id);

            if (str_contains(Auth::user()->internalRole->role, 'Mandor')) {
                $pekerjaan = $pekerjaan->where('kemandoran.user_id', Auth::user()->id);
            } else if (Auth::user()->sup_id)
                $pekerjaan = $pekerjaan->where('kemandoran.sup_id', Auth::user()->sup_id);
        }
        $from = $request->year_from;
        $to = $request->year_to;
        $pekerjaan = $pekerjaan->whereRaw("YEAR(tanggal) BETWEEN $from AND $to");
        $pekerjaan = $pekerjaan->where('is_deleted', 0)->get();

        return DataTables::of($pekerjaan)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">';

                if (hasAccess(Auth::user()->internal_role_id, "Pekerjaan", "Update")) {
                    $btn = $btn . '<a href="' . route('editDataPekerjaan', $row->id_pek) . '"><button data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-pencil"></i></button></a>';
                    $btn = $btn . '<a href="' . route('materialDataPekerjaan', $row->id_pek) . '"><button class="btn btn-warning btn-sm waves-effect waves-light" data-toggle="tooltip" title="Material"><i class="icofont icofont-list"></i></button></a>';
                }

                if (hasAccess(Auth::user()->internal_role_id, "Pekerjaan", "Delete")) {
                    $btn = $btn . '<a href="#delModal" data-id="' . $row->id_pek . '" data-toggle="modal"><button class="btn btn-danger btn-sm waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>';
                }

                if (hasAccess(Auth::user()->internal_role_id, "Pekerjaan", "Update")) {
                    $btn = $btn . '<a href="#submitModal" data-id="' . $row->id_pek . '" data-toggle="modal"><button class="btn btn-success btn-sm waves-effect waves-light" data-toggle="tooltip" title="Submit"><i class="icofont icofont-check-circled"></i></button></a>';
                }

                $btn = $btn . '</div>';

                // $btn = '<a href="javascript:void(0)" class="btn btn-primary">' . $row->id . '</a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
