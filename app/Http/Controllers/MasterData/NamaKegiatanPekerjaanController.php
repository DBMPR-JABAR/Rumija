<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NamaKegiatanPekerjaanController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Nama Kegiatan Pekerjaan', ['store', ''], ['index'], ['edit', 'update'], ['destroy']);
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
        $nama_kegiatan_pekerjaan = DB::table('utils_nama_kegiatan_pekerjaan')->get();
        return view('admin.master.nama_kegiatan_pekerjaan.index', compact('nama_kegiatan_pekerjaan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.master.nama_kegiatan_pekerjaan.insert', ['action' => 'store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nama_kegiatan_pekerjaan['name'] = $request->nama_kegiatan_pekerjaan;
        DB::table('utils_nama_kegiatan_pekerjaan')->insert($nama_kegiatan_pekerjaan);
        $color = "success";
        $msg = "Berhasil Menambah Data Nama Kegiatan";
        return redirect(route('nama_kegiatan_pekerjaan.index'))->with(compact('color', 'msg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nama_kegiatan_pekerjaan = DB::table('utils_nama_kegiatan_pekerjaan')->where('id', $id)->first();
        return view('admin.master.nama_kegiatan_pekerjaan.insert', ['action' => 'update', 'nama_kegiatan_pekerjaan' => $nama_kegiatan_pekerjaan]);
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
        $nama_kegiatan_pekerjaan['name'] = $request->nama_kegiatan_pekerjaan;
        DB::table('utils_nama_kegiatan_pekerjaan')->where('id', $id)->update($nama_kegiatan_pekerjaan);

        $color = "success";
        $msg = "Berhasil Memperbaharui Data Nama Kegiatan";
        return redirect(route('nama_kegiatan_pekerjaan.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $nama_kegiatan_pekerjaan = DB::table('utils_nama_kegiatan_pekerjaan')->where('id', $id)->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Nama Kegiatan";
        return redirect(route('nama_kegiatan_pekerjaan.index'))->with(compact('color', 'msg'));
    }
}
