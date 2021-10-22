<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DPAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dpa = DB::table('master_paket_pekerjaan_dpa')
            ->leftJoin('master_check_dpa', 'master_check_dpa.id', 'master_paket_pekerjaan_dpa.check')
            ->leftJoin('master_pengadaan_dpa', 'master_pengadaan_dpa.id', 'master_paket_pekerjaan_dpa.jenis_pengadaan')
            ->leftJoin('master_pendanaan_dpa', 'master_pendanaan_dpa.id', 'master_paket_pekerjaan_dpa.pendanaan')
            ->leftJoin('master_uptd_dpa', 'master_uptd_dpa.id', 'master_paket_pekerjaan_dpa.uptd')
            ->leftJoin('master_kategori_paket_pekerjaan', 'master_kategori_paket_pekerjaan.id', 'master_paket_pekerjaan_dpa.kategori')
            ->get();
        return view('admin.input_data.dpa.index', compact('dpa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = DB::table('master_kategori_paket_pekerjaan')->get();
        $ruas_jalan = DB::table('master_ruas_jalan')->get();
        $check = DB::table('master_check_dpa')->get();
        $pengadaan = DB::table('master_pengadaan_dpa')->get();
        $pendanaan = DB::table('master_pendanaan_dpa')->get();
        $uptd_dpa = DB::table('master_uptd_dpa')->get();
        $action = "store";
        return view('admin.input_data.dpa.insert', compact('kategori', 'action', 'pendanaan', 'pengadaan', 'check', 'uptd_dpa', 'ruas_jalan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::table('master_paket_pekerjaan_dpa')->insert($request->except('_token'));
        $color = "success";
        $msg = "Berhasil Menambah Data DPA";
        return redirect(route('dpa.index'))->with(compact('color', 'msg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $kategori = DB::table('master_kategori_paket_pekerjaan')->get();
        $ruas_jalan = DB::table('master_ruas_jalan')->get();
        $check = DB::table('master_check_dpa')->get();
        $pengadaan = DB::table('master_pengadaan_dpa')->get();
        $pendanaan = DB::table('master_pendanaan_dpa')->get();
        $uptd_dpa = DB::table('master_uptd_dpa')->get();
        $data = DB::table('master_paket_pekerjaan_dpa')
            ->where('no_urut', $id)
            ->leftJoin('master_kategori_paket_pekerjaan', 'master_kategori_paket_pekerjaan.id', 'master_paket_pekerjaan_dpa.kategori')
            ->leftJoin('master_uptd_dpa', 'master_uptd_dpa.id', 'master_paket_pekerjaan_dpa.uptd')
            ->leftJoin('master_check_dpa', 'master_check_dpa.id', 'master_paket_pekerjaan_dpa.check')
            ->leftJoin('master_pendanaan_dpa', 'master_pendanaan_dpa.id', 'master_paket_pekerjaan_dpa.pendanaan')
            ->leftJoin('master_pengadaan_dpa', 'master_pengadaan_dpa.id', 'master_paket_pekerjaan_dpa.jenis_pengadaan')
            ->leftJoin('master_ruas_jalan', 'master_ruas_jalan.id_ruas_jalan', 'master_paket_pekerjaan_dpa.kode_ruas_jalan')
            ->select(
                [
                    'master_paket_pekerjaan_dpa.*',
                    'master_kategori_paket_pekerjaan.nama_kategori',
                    'master_uptd_dpa.nama_uptd',
                    'master_check_dpa.nama_check',
                    'master_pendanaan_dpa.nama_pendanaan',
                    'master_pengadaan_dpa.nama_pengadaan',
                    'master_ruas_jalan.nama_ruas_jalan'
                ]
            )
            ->first();
        $reports = DB::table('dpa_reports')->where('dpa_id', $id)->get()->toArray();
        return view('admin.input_data.dpa.show', compact('data', 'reports'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dpa = DB::table('master_paket_pekerjaan_dpa')->where('no_urut', $id)->first();
        $kategori = DB::table('master_kategori_paket_pekerjaan')->get();
        $ruas_jalan = DB::table('master_ruas_jalan')->get();
        $check = DB::table('master_check_dpa')->get();
        $pengadaan = DB::table('master_pengadaan_dpa')->get();
        $pendanaan = DB::table('master_pendanaan_dpa')->get();
        $uptd_dpa = DB::table('master_uptd_dpa')->get();
        $action = "update";
        return view('admin.input_data.dpa.insert', compact('dpa', 'kategori', 'action', 'pendanaan', 'pengadaan', 'check', 'uptd_dpa', 'ruas_jalan'));
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
        DB::table('master_paket_pekerjaan_dpa')->where('no_urut', $id)->update($request->except('_token', '_method'));
        $color = "success";
        $msg = "Berhasil Memperbaharui Data DPA";
        return redirect(route('dpa.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('master_paket_pekerjaan_dpa')->where('no_urut', $id)->delete();
        $color = "success";
        $msg = "Berhasil Memnghapus Data DPA";
        return redirect(route('dpa.index'))->with(compact('color', 'msg'));
    }
}
