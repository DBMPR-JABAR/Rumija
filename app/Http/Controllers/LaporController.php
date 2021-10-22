<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\DataTables;

class LaporController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Daftar Laporan', ['create', 'store'], ['index', 'json'], ['edit', 'update'], ['delete']);
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

        $aduan = DB::table('monitoring_laporan_masyarakat');

        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $aduan = $aduan->where('uptd_id', $uptd_id);
        }

        $aduan = $aduan->latest()->get();
        // foreach($aduan as $ni){
        //     echo $ni->status;
        // }
        // dd($aduan);
        return view('admin.lapor.index', compact('aduan'));
    }

    public function create()
    {
        $ruasJalan = DB::table('master_ruas_jalan');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $ruasJalan = $ruasJalan->where('uptd_id', $uptd_id);
        }
        $ruasJalan = $ruasJalan->get();

        $uptd = DB::table('landing_uptd')->get();

        return view('admin.lapor.add', compact('ruasJalan', 'uptd'));
    }

    public function edit($id)
    {
        $aduan = DB::table('aduan')->where('id', $id)->first();
        $ruasJalan = DB::table('master_ruas_jalan');
        $ruasJalan = $ruasJalan->where('uptd_id', $aduan->uptd_id);
        $ruasJalan = $ruasJalan->get();
        $uptd = DB::table('landing_uptd')->get();
        $status = array(
            array('id' => 'menunggu', 'name' => 'menunggu'),
            array('id' => 'Dalam proses', 'name' => 'Dalam proses'),
            array('id' => 'selesai', 'name' => 'selesai')
        );

        return view('admin.lapor.edit', compact('aduan', 'ruasJalan', 'uptd', 'status'));
    }


    public function store(Request $request)
    {
        $lapor = $request->except('_token', 'foto');

        if ($request->foto != null) {
            $path = 'lapor/' . Str::snake(date("YmdHis") . ' ' . $request->foto->getClientOriginalName());
            $request->foto->storeAs('public/', $path);
            $lapor['foto_awal'] = $path;
        }
        $lapor['status'] = 'menunggu';
        DB::table('aduan')->insert($lapor);

        $color = "success";
        $msg = "Berhasil Menambah Data Lapor";
        return back()->with(compact('color', 'msg'));
    }

    public function update(Request $req)
    {
        //
        $aduan = $req->except('_token', 'foto_awal', 'id');

        $old = DB::table('aduan')->where('id', $req->id)->first();

        if ($req->foto_awal != null) {
            $old->foto_awal ?? Storage::delete('public/' . $old->foto_awal);

            $path = 'landing/' . Str::snake(date("YmdHis") . ' ' . $req->foto_awal->getClientOriginalName());
            $req->foto_awal->storeAs('public/', $path);
            $aduan['foto_awal'] = $path;
        }

        DB::table('aduan')->where('id', $req->id)->update($aduan);

        $color = "success";
        $msg = "Berhasil Mengubah Data Laporan";
        return redirect(route('getLapor'))->with(compact('color', 'msg'));
    }

    public function delete($id)
    {
        $aduan = DB::table('aduan');
        $old = $aduan->where('id', $id);

        $old->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Laporan";
        return redirect(route('getLapor'))->with(compact('color', 'msg'));
    }

    public function json()
    {
        $aduan = DB::table('monitoring_laporan_masyarakat');
        // ->leftJoin('utils_jenis_laporan', 'utils_jenis_laporan.id', 'monitoring_laporan_masyarakat.jenis')
        // ->select(['monitoring_laporan_masyarakat.*', 'utils_jenis_laporan.name as jenis']);
        // dd($aduan->get());
        return DataTables::of($aduan)
            ->addIndexColumn()
            ->addColumn('imglaporan', function ($row) {
                $path_foto = explode('/', $row->gambar);
                $img = '<img class="img-fluid" style="max-width: 100px" src="' . url('storage/' . $row->gambar) . '"  alt="' . end($path_foto) . '" />';
                return $img;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">';

                if (hasAccess(Auth::user()->internal_role_id, "Lapor", "Update")) {
                    $btn = $btn . '<a href="' . route('editLapor', $row->id) . '"><button data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-pencil"></i></button></a>';
                }

                if (hasAccess(Auth::user()->internal_role_id, "Lapor", "Delete")) {
                    $btn = $btn . '<a href="#delModal" data-id="' . $row->id . '" data-toggle="modal"><button data-toggle="tooltip" title="Hapus" class="btn btn-danger btn-sm waves-effect waves-light"><i class="icofont icofont-trash"></i></button></a>';
                }
                $btn = $btn . '</div>';

                // $btn = '<a href="javascript:void(0)" class="btn btn-primary">' . $row->id . '</a>';

                if (strpos($row->nomorPengaduan, 'QR') !== false) {
                    $btn = $btn . '<a href="#jqrModal" data-status_jqr="' . ($row->status_jqr ? $row->status_jqr : 1) . '" data-no_pengaduan="' . $row->nomorPengaduan . '" data-toggle="modal"><button data-toggle="tooltip" title="Status JQR" class="btn btn-success btn-sm waves-effect waves-light"><i class="icofont icofont-pen"></i></button></a>';
                }

                return $btn;
            })
            ->addColumn('jenis', function ($row) {
                $jenis = DB::table('utils_jenis_laporan')->where('id', $row->jenis)->first();
                return $jenis->name;
            })
            ->addColumn('status', function ($row) {
                if (strpos($row->nomorPengaduan, 'QR') !== false) {
                    $status = '';
                    switch ($row->status_jqr) {
                        case 2:
                            $status = "Diverifikasi";
                            break;
                        case 3:
                            $status = "Aduan Ditolak";
                            break;
                        case 4:
                            $status = "Dalam Proses Survei";
                            break;
                        case 5:
                            $status = "Dalam Rencana Tindakan";
                            break;
                        case 6:
                            $status = "Dalam Proses Tindakan";
                            break;
                        case 7:
                            $status = "Selesai";
                            break;
                        default:
                            $status = 'Submitted';
                    }
                    return $status;
                } else return $row->status;
            })
            ->rawColumns(['action', 'imglaporan', 'jenis'])
            ->make(true);
    }

    public function pemetaanLaporanMasyarakat()
    {
        return view('admin.lapor.maps');
    }

    public function update_jqr($no_aduan, $status)
    {
        $aduan['status_jqr'] = $status;
        DB::table('monitoring_laporan_masyarakat')->where('nomorPengaduan', $no_aduan)->update($aduan);

        // TODO LAPORAN API JQR
        $msg = "Berhasil Memperbaharui Status Laporan. JQR : ";
        if ($status > 1) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://jabarqr.id/api/update_aduan',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('no_aduan' => $no_aduan, 'status' => $status),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: a9a9933ce440323accad1dd73900e85321d19bc8'
                ),
            ));

            $response = (object)curl_exec($curl);

            curl_close($curl);
            $msgJQR = (object)json_decode($response->scalar)->Message;
            // dd($msg->scalar);
            $msg = $msg . ($msgJQR->scalar ? $msgJQR->scalar : '-');
        }

        $color = "success";
        return redirect(route('getLapor'))->with(compact('color', 'msg'));
    }
}
