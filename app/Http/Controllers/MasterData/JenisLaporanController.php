<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisLaporanController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Jenis Laporan', ['create', 'store'], ['index'], ['edit', 'update'], ['destroy']);
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
        $jenis_laporan = DB::table('utils_jenis_laporan')->get();
        //dd($jenis_laporan[0]->id);
        return view('admin.master.jenis_laporan.index', compact('jenis_laporan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.master.jenis_laporan.insert', ['action' => 'store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jenis_laporan['name'] = $request->jenis_laporan;
        DB::table('utils_jenis_laporan')->insert($jenis_laporan);
        $color = "success";
        $msg = "Berhasil Menambah Data Jenis Laporan";
        return redirect(route('jenis_laporan.index'))->with(compact('color', 'msg'));
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
        $jenis_laporan = DB::table('utils_jenis_laporan')->where('id', $id)->first();
        return view('admin.master.jenis_laporan.insert', ['action' => 'upadte', 'jenis_laporan' => $jenis_laporan]);
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
        $jenis_laporan['name'] = $request->jenis_laporan;
        DB::table('utils_jenis_laporan')->where('id', $id)->update($jenis_laporan);

        $color = "success";
        $msg = "Berhasil Memperbaharui Data Jenis Laporan";
        return redirect(route('jenis_laporan.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jenis_laporan = DB::table('utils_jenis_laporan')->where('id', $id)->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Jenis Laporan";
        return redirect(route('jenis_laporan.index'))->with(compact('color', 'msg'));
    }
}
