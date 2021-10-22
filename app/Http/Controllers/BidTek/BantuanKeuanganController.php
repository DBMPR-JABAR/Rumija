<?php

namespace App\Http\Controllers\BidTek;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BantuanKeuanganController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Bantuan Keuangan', ['create', 'store'], ['index'], ['edit', 'update'], ['destroy']);
        foreach ($roles as $role => $permission) {
            $this->middleware($role)->only($permission);
        }
        $this->email_list_notifications =
            DB::table('users')
            ->leftJoin('master_grant_role_aplikasi', 'master_grant_role_aplikasi.internal_role_id', 'users.internal_role_id')
            ->where('master_grant_role_aplikasi.menu', 'Laporan Bantuan Keuangan')
            ->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $access = hasAccess(
            Auth::user()->internal_role_id,
            'Bantuan Keuangan',
            'Create'
        );
        if ($access)
            $bankeu = DB::table('bankeu')->orderBy('is_verified')->get();
        else
            $bankeu = DB::table('bankeu')
                ->where('ditunjukan_untuk', 'LIKE', "%" . Auth::user()->id . "%")
                ->orderBy('is_verified')
                ->get();

        return view('admin.bidtek.bankeu.index', compact('bankeu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ruas_jalan = DB::table('ruas_jalan_kabupaten_tarung')->select(['*'])->get();
        $kab_kota = DB::table('indonesia_cities')->where('province_id', 32)->get();
        $kategori = DB::table('master_kategori_paket')->get();
        $penyedia_jasa = DB::connection('talikuat')->table('master_penyedia_jasa')->get();
        $konsultan = DB::connection('talikuat')->table('master_konsultan')->get();
        $ppk = DB::connection('talikuat')->table('master_ppk')->get();
        $users = DB::table('users')->leftJoin('user_role', 'user_role.id', 'users.internal_role_id')->select(['users.name', 'user_role.role', 'users.id'])->get();
        // dd($users);
        $access = hasAccess(
            Auth::user()->internal_role_id,
            'Bantuan Keuangan',
            'Create'
        );

        $verified_access = hasAccess(
            Auth::user()->internal_role_id,
            'Verifikasi Bantuan Keuangan',
            'Create'
        );
        $action = 'store';
        return view('admin.bidtek.bankeu.pre', compact('action', 'ruas_jalan', 'kategori', 'penyedia_jasa', 'konsultan', 'ppk', 'kab_kota', 'access', 'users', 'verified_access'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bankeu = $request->only(['pemda', 'opd', 'kab_kota', 'kategori', 'nama_kegiatan', 'no_kontrak', 'tanggal_kontrak', 'nilai_kontrak', 'no_spmk', 'tanggal_spmk', 'panjang', 'waktu_pelaksanaan', 'ppk_kegiatan', 'penyedia_jasa', 'konsultasi_supervisi', 'nama_ppk', 'nama_se', 'nama_gs', 'geo_id', 'nama_lokasi', 'geo_json', 'pembagian_progres', 'is_verified', 'progress']);
        $bankeu["created_by"] = Auth::user()->id;
        $bankeu['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        if ($request->ditunjukan_untuk) $bankeu['ditunjukan_untuk'] = implode('__', $request->ditunjukan_untuk);
        // dd($request->all());
        if ($request->file('shp') != null) {
            $path = 'bankeu/shp/' . Str::snake(date("YmdHis") . ' ' . $request->file('shp')->getClientOriginalName());
            $request->file('shp')->storeAs('public/', $path);
            $bankeu['shp'] = $path;
        }
        $id =  DB::table('bankeu')->insertGetId($bankeu);
        $count = (int)$request->pembagian_progres;
        for ($i = 1; $i <= $count; $i++) {
            $bankeu_progres['id_bankeu'] = $id;
            $bankeu_progres['target'] = $i;
            $bankeu_progres["updated_by"] = Auth::user()->id;
            $bankeu_progres['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
            if ($request->file('foto_' . $i . '_1') != null) {
                $path = 'bankeu/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto_' . $i . '_1')->getClientOriginalName());
                $request->file('foto_' . $i . '_1')->storeAs('public/', $path);
                $bankeu_progres['foto_1'] = $path;
                $bankeu['foto'] = $path;
            } else $bankeu_progres['foto_1'] = '';
            if ($request->file('foto_' . $i . '_2') != null) {
                $path = 'bankeu/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto_' . $i . '_2')->getClientOriginalName());
                $request->file('foto_' . $i . '_2')->storeAs('public/', $path);
                $bankeu_progres['foto_2'] = $path;
                $bankeu['foto_1'] = $path;
            } else $bankeu_progres['foto_2'] = '';
            if ($request->file('foto_' . $i . '_3') != null) {
                $path = 'bankeu/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto_' . $i . '_3')->getClientOriginalName());
                $request->file('foto_' . $i . '_3')->storeAs('public/', $path);
                $bankeu_progres['foto_3'] = $path;
                $bankeu['foto_2'] = $path;
            } else $bankeu_progres['foto_3'] = '';
            if ($request->file('video_' . $i) != null) {
                $path = 'bankeu/' . Str::snake(date("YmdHis") . ' ' . $request->file('video_' . $i)->getClientOriginalName());
                $request->file('video_' . $i)->storeAs('public/', $path);
                $bankeu_progres['video'] = $path;
                $bankeu['video'] = $path;
            } else $bankeu_progres['video'] = '';
            if ($request->file('dokumen_' . $i) != null) {
                $path = 'bankeu/' . Str::snake(date("YmdHis") . ' ' . $request->file('dokumen_' . $i)->getClientOriginalName());
                $request->file('dokumen_' . $i)->storeAs('public/', $path);
                $bankeu_progres['dokumen'] = $path;
            } else $bankeu_progres['dokumen'] = '';
            $bankeu_progres['tanggal'] = $request->input('tanggal_target_' . $i);
            $bankeu_progres['persentase'] = $request->input('persentase_target_' . $i);
            DB::table('bankeu_progres')->insert($bankeu_progres);
            if ($i == $count) DB::table('bankeu')->where('id', $id)->update($bankeu);
        }


        if ($request->geo_json) {
            $geo_json['geo_json'] = json_encode([
                "type" => "MultiLineString",
                "coordinates" => json_decode($request->geo_json),
                "crs" => ["type" => "name", "properties" => ["name" => "EPSG:4326"]]
            ]);
            $geo_json['id_bankeu'] = $id;

            $ruas_jalan_custom['flag_id'] = $id;
            $ruas_jalan_custom['flag'] = 'bankeu';
            $ruas_jalan_custom['keterangan'] = 'Custom ruas jalan dari bankeu';
            $ruas_jalan_custom['nama_lokasi'] = $request->nama_lokasi;
            $ruas_jalan_custom['geo_json'] = $geo_json['geo_json'];
            $id_ruas_jalan_custom = DB::table('ruas_jalan_custom')->insertGetId($ruas_jalan_custom);
            DB::table('bankeu')->where('id', $id)->update(['ruas_jalan_custom_id' => $id_ruas_jalan_custom]);
            DB::table('bankeu_geo_json')->insert($geo_json);
        }

        $historis["id_bankeu"] = $id;
        $historis['progress'] = $request->progress;
        $historis['progress_old'] = 0;
        $historis['updated_by'] = Auth::user()->id;
        $historis['updated_at'] = Carbon::now();
        DB::table('utils_historis_bankeu')->insert($historis);
        $color = "success";
        $msg = "Berhasil Menambah Data Bantuan Keuangan";

        $subject = 'Bantuan Keuangan ' . $request->no_kontrak;
        $view = 'mail.bankeu_new';
        if ($request->ditunjukan_untuk) {
            foreach ($request->ditunjukan_untuk as $id_user) {
                $to = DB::table('users')->where('id', $id_user)->first();
                // dd($to);
                $data = [
                    'no_kontrak' => $request->no_kontrak,
                    'to_name' => $to->name,
                ];
                $this->send_email($to->email, $to->name, $subject, Auth::user()->email, Auth::user()->name, $view, $data);
            }
        }

        return redirect(route('bankeu.index'))->with(compact('color', 'msg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kategori = DB::table('master_kategori_paket')->get();
        $penyedia_jasa = DB::connection('talikuat')->table('master_penyedia_jasa')->get();
        $konsultan = DB::connection('talikuat')->table('master_konsultan')->get();
        $bankeu = DB::table('bankeu')->where('id', $id)->first();
        $ppk = DB::connection('talikuat')->table('master_ppk')->get();
        $historis = DB::table('utils_historis_bankeu')->where('id_bankeu', $id)->get();
        // dd($historis);
        return view('admin.bidtek.bankeu.progress', compact('bankeu', 'kategori', 'penyedia_jasa', 'konsultan', 'ppk', 'historis'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ruas_jalan = DB::table('ruas_jalan_kabupaten_tarung')->select(['*'])->get();
        $kab_kota = DB::table('indonesia_cities')->where('province_id', 32)->get();
        $kategori = DB::table('master_kategori_paket')->get();
        $penyedia_jasa = DB::connection('talikuat')->table('master_penyedia_jasa')->get();
        $konsultan = DB::connection('talikuat')->table('master_konsultan')->get();
        $bankeu = DB::table('bankeu')->where('id', $id)->first();
        $ppk = DB::connection('talikuat')->table('master_ppk')->get();
        $bankeu_progres = null;
        $bankeu_progres = DB::table('bankeu_progres')->where('id_bankeu', $id)->get();
        $access = hasAccess(
            Auth::user()->internal_role_id,
            'Bantuan Keuangan',
            'Create'
        );

        $verified_access = hasAccess(
            Auth::user()->internal_role_id,
            'Verifikasi Bantuan Keuangan',
            'Create'
        );
        $users = DB::table('users')->leftJoin('user_role', 'user_role.id', 'users.internal_role_id')->select(['users.name', 'user_role.role', 'users.id'])->get();
        $ditunjukan_untuk = explode('__', $bankeu->ditunjukan_untuk);
        $ruas_jalan_selected = DB::table('ruas_jalan_kabupaten_tarung')->select(['*'])->where('geo_id', $bankeu->geo_id)->first();
        $bankeu_progres = DB::table('bankeu_progres')->where('id_bankeu', $id)->get();
        $action = 'update';
        return view('admin.bidtek.bankeu.pre', compact('action', 'bankeu', 'kategori', 'penyedia_jasa', 'konsultan', 'ppk', 'ruas_jalan', 'kab_kota', 'bankeu_progres', 'verified_access', 'access', 'ruas_jalan_selected', 'ditunjukan_untuk', 'users', 'bankeu_progres'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $bankeu = $request->only(['pemda', 'opd', 'kab_kota', 'kategori', 'nama_kegiatan', 'no_kontrak', 'tanggal_kontrak', 'nilai_kontrak', 'no_spmk', 'tanggal_spmk', 'panjang', 'waktu_pelaksanaan', 'ppk_kegiatan', 'penyedia_jasa', 'konsultasi_supervisi', 'nama_ppk', 'nama_se', 'nama_gs', 'geo_id', 'nama_lokasi', 'geo_json', 'pembagian_progres', 'is_verified', 'progress']);
        $bankeu["updated_by"] = Auth::user()->id;
        $bankeu['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
        if ($request->ditunjukan_untuk) $bankeu['ditunjukan_untuk'] = implode('__', $request->ditunjukan_untuk);
        // DB::table('bankeu')->where('id', $id)->update($bankeu);
        $count = (int)$request->pembagian_progres;
        // dd($request->all());
        if ($request->file('shp') != null) {
            $path = 'bankeu/shp/' . Str::snake(date("YmdHis") . ' ' . $request->file('shp')->getClientOriginalName());
            $request->file('shp')->storeAs('public/', $path);
            $bankeu['shp'] = $path;
        }
        for ($i = 1; $i <= $count; $i++) {
            $bankeu_progres['id_bankeu'] = $id;
            $bankeu_progres['target'] = $i;
            $bankeu_progres["updated_by"] = Auth::user()->id;
            $bankeu_progres['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $update = false;
            if ($request->file('foto_' . $i . '_1') != null && $request->file('foto_' . $i . '_1')->getSize() > 0) {
                $path = 'bankeu/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto_' . $i . '_1')->getClientOriginalName());
                $request->file('foto_' . $i . '_1')->storeAs('public/', $path);
                $bankeu_progres['foto_1'] = $path;
                $bankeu['foto'] = $path;
                $update = true;
            };
            if ($request->file('foto_' . $i . '_2') != null && $request->file('foto_' . $i . '_2')->getSize() > 0) {
                $path = 'bankeu/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto_' . $i . '_2')->getClientOriginalName());
                $request->file('foto_' . $i . '_2')->storeAs('public/', $path);
                $bankeu_progres['foto_2'] = $path;
                $bankeu['foto_1'] = $path;
                $update = true;
            };
            if ($request->file('foto_' . $i . '_3') != null && $request->file('foto_' . $i . '_3')->getSize() > 0) {
                $path = 'bankeu/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto_' . $i . '_3')->getClientOriginalName());
                $request->file('foto_' . $i . '_3')->storeAs('public/', $path);
                $bankeu_progres['foto_3'] = $path;
                $bankeu['foto_2'] = $path;
                $update = true;
            };
            if ($request->file('video_' . $i) != null && $request->file('video_' . $i)->getSize() > 0) {
                $path = 'bankeu/' . Str::snake(date("YmdHis") . ' ' . $request->file('video_' . $i)->getClientOriginalName());
                $request->file('video_' . $i)->storeAs('public/', $path);
                $bankeu_progres['video'] = $path;
                $bankeu['video'] = $path;
                $update = true;
            };
            if ($request->file('dokumen_' . $i) != null && $request->file('dokumen_' . $i)->getSize() > 0) {
                $path = 'bankeu/' . Str::snake(date("YmdHis") . ' ' . $request->file('dokumen_' . $i)->getClientOriginalName());
                $request->file('dokumen_' . $i)->storeAs('public/', $path);
                $bankeu_progres['dokumen'] = $path;
                $update = true;
            };
            $bankeu_progres['tanggal'] = $request->input('tanggal_target_' . $i);
            $bankeu_progres['persentase'] = $request->input('persentase_target_' . $i);
            $old = DB::table('bankeu_progres')->where('id_bankeu', $id)->where('target', $i);
            if ($old->count() > 0) {
                if ($update) {
                    $bankeu_progres['is_verified'] = 0;
                    $old->update($bankeu_progres);
                    $subject = 'Perbaharuan Laporan Progres Bantuan Keuangan';
                    $view = 'mail.bankeu.laporan_new';
                    // dd($this->email_list_notifications);
                    foreach ($this->email_list_notifications as $user) {
                        $data = [
                            'to_name' => $user->name,
                            'no_kontrak' => $request->no_kontrak,
                        ];
                        $this->send_email($user->email, $user->name, $subject, Auth::user()->email, Auth::user()->name, $view, $data);
                    }
                }
            } else DB::table('bankeu_progres')->insert($bankeu_progres);
            if ($i == $count) DB::table('bankeu')->where('id', $id)->update($bankeu);
        }
        DB::table('bankeu_progres')->where('id_bankeu', $id)->where('target', '>', $count)->delete();


        if ($request->progress !== $request->progress_old) {
            $historis["id_bankeu"] = $id;
            $historis['progress'] = $request->progress;
            $historis['progress_old'] = $request->progress_old;
            $historis['updated_by'] = Auth::user()->id;
            $historis['updated_at'] = Carbon::now();
            DB::table('utils_historis_bankeu')->insert($historis);
        }
        if ($request->geo_json || $request->nama_lokasi) {
            $geo_json['geo_json'] = json_encode([
                "type" => "MultiLineString",
                "coordinates" => json_decode($request->geo_json),
                "crs" => ["type" => "name", "properties" => ["name" => "EPSG:4326"]]
            ]);
            $geo_json['id_bankeu'] = $id;

            $ruas_jalan_custom['keterangan'] = 'Custom ruas jalan dari bankeu';
            $ruas_jalan_custom['nama_lokasi'] = $request->nama_lokasi;
            $ruas_jalan_custom['geo_json'] = $geo_json['geo_json'];
            if (DB::table('bankeu')->where('id', $id)->count() > 1) {
                $id_ruas_jalan_custom = DB::table('ruas_jalan_custom')->insertGetId($ruas_jalan_custom);
                DB::table('bankeu')->where('id', $id)->update(['ruas_jalan_custom_id' => $id_ruas_jalan_custom]);
            } else
                DB::table('ruas_jalan_custom')->where('flag', 'bankeu')
                    ->where('flag_id', $id)->update($ruas_jalan_custom);

            DB::table('bankeu_geo_json')->where('id_bankeu', $id)->update($geo_json);
        }

        $color = "success";
        $msg = "Berhasil Mengubah Data Bantuan Keuangan";
        return redirect(route('bankeu.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!(DB::table('bankeu')->where('id', $id)->count() > 1))
            DB::table('ruas_jalan_custom')->where('flag', 'bankeu')
                ->where('flag_id', $id)->delete();
        $temp = DB::table('bankeu')->where('id', $id)->delete();
        $color = "success";
        $msg = "Berhasil Menghapus Data Bantuan Keuangan";
        return redirect(route('bankeu.index'))->with(compact('color', 'msg'));
    }

    public function getRuasJalanByGeoId($id)
    {
        $ruas_jalan = DB::table('ruas_jalan_kabupaten_tarung')->where('geo_id', $id)->first();
        return response()->json(["coordinates" => json_decode($ruas_jalan->geo_json)->coordinates[0]], 200);
    }

    private function send_email($to_email, $to_name, $subject, $form_email, $from_name, $view, $data)
    {
        try {
            // Mail::send($view, $data, function ($message) use ($to_name, $to_email, $subject, $form_email, $from_name) {
            //     $message->to($to_email, $to_name)->subject($subject);
            //     $message->from($form_email, $from_name);
            // });
            return true;
        } catch (Error $e) {
            $error = $e;
        }
    }

    public function progres_index()
    {
        $bankeu_progres = DB::table('bankeu_progres')
            ->rightJoin('bankeu', 'bankeu_progres.id_bankeu', 'bankeu.id')
            ->where('bankeu_progres.is_verified', '>=', 0)
            ->select([
                'bankeu_progres.*',
                'bankeu.no_kontrak',
                'bankeu.tanggal_kontrak',
                'bankeu.nama_kegiatan',
                'bankeu.kategori',
                'bankeu.progress',
                'bankeu.id',
                'bankeu.pembagian_progres'
            ])
            ->get();
        // dd($bankeu_progres);

        $bankeu = $bankeu_progres;
        return view('admin.bidtek.bankeu.progres.index', compact('bankeu'));
    }

    public function progres_verifikasi($id, $target)
    {
        $bankeu_progres = DB::table('bankeu_progres')
            ->rightJoin('bankeu', 'bankeu_progres.id_bankeu', 'bankeu.id')
            ->where('bankeu_progres.is_verified', '>=', 0)
            ->where('bankeu_progres.id_bankeu', $id)
            ->where('bankeu_progres.target', $target)
            ->select([
                'bankeu_progres.*',
                'bankeu.no_kontrak',
                'bankeu.tanggal_kontrak',
                'bankeu.nama_kegiatan',
                'bankeu.kategori',
                'bankeu.progress',
                'bankeu.id',
                'bankeu.pembagian_progres'
            ])
            ->first();
        // dd($bankeu_progres);

        $bankeu = $bankeu_progres;
        return view('admin.bidtek.bankeu.progres.verifikasi', compact('bankeu'));
    }

    public function progres_verifikasi_update(Request $request, $id, $target)
    {
        // dd($request->all());
        $bankeu_progres = $request->except('_token');
        DB::table('bankeu_progres')->where('id_bankeu', $id)
            ->where('target', $target)->update($bankeu_progres);
        $bankeu = DB::table('bankeu')->where('id', $id)->first();
        $ditunjukan_untuk = explode('__', $bankeu->ditunjukan_untuk);

        $subject = 'Hasil tinjauan Laporan Progres Bantuan Keuangan';
        $view = 'mail.bankeu.verifikasi';
        // dd($ditunjukan_untuk);
        foreach ($ditunjukan_untuk as $userId) {
            // dd($userId);
            $user = DB::table('users')->where('id', $userId)->first();
            $data = [
                'to_name' => $user->name,
                'no_kontrak' => $bankeu->no_kontrak,
                'is_verified' => $request->is_verified,
                'catatan' => $request->catatan
            ];
            $this->send_email($user->email, $user->name, $subject, Auth::user()->email, Auth::user()->name, $view, $data);
        }
        $color = "success";
        $msg = $request->is_verified == 1 ? 'Berhasil menyetujui laporan Bantuan Keuangan' : 'Berhasil menolak laporan Bantuan Keuangan';
        return redirect(route('bankeu.progres'))->with(compact('color', 'msg'));
    }
}
