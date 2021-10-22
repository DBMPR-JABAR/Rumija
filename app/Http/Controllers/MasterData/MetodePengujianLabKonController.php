<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MetodePengujianLabKonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $metode_pengujian_labkon = DB::table('labkon_master_metode_pengujian')->leftJoin('bahan_uji', 'bahan_uji.id', '=', 'labkon_master_metode_pengujian.id_bahan_uji')->select('labkon_master_metode_pengujian.*', 'bahan_uji.nama as nama_bahan')->get();
        // dd($metode_pengujian_labkon);
        return view('admin.master.metode_pengujian_labkon.index', compact('metode_pengujian_labkon'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bahan_uji = DB::table('bahan_uji')->get();
        $action = 'store';
        return view('admin.master.metode_pengujian_labkon.insert', compact('action', 'bahan_uji'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $metode_pengujian = $request->except('_token');
        $metode_pengujian['harga_sni'] = 0;
        $metode_pengujian['status'] = $request->status == 'on' ? 'aktif' : 'nonaktif';
        $metode_pengujian['created_at'] = Carbon::now();
        $metode_pengujian['created_by'] = Auth::user()->id;
        DB::table('labkon_master_metode_pengujian')->insert($metode_pengujian);
        $color = "success";
        $msg = "Berhasil Menambah Data Metode Pengujian";
        return redirect(route('metode_pengujian_labkon.index'))->with(compact('color', 'msg'));
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
        $bahan_uji = DB::table('bahan_uji')->get();
        $metode_pengujian_labkon = DB::table('labkon_master_metode_pengujian')->where('id', $id)->first();
        $action = 'update';
        return view('admin.master.metode_pengujian_labkon.insert', compact('action', 'bahan_uji', 'metode_pengujian_labkon'));
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
        $metode_pengujian = $request->except('_token', '_method');
        $metode_pengujian['status'] = $request->status == 'on' ? 'aktif' : 'nonaktif';
        $metode_pengujian['harga_sni'] = 0;
        $metode_pengujian['updated_at'] = Carbon::now();
        $metode_pengujian['updated_by'] = Auth::user()->id;
        DB::table('labkon_master_metode_pengujian')->where('id', $id)->update($metode_pengujian);
        $color = "success";
        $msg = "Berhasil Memperbaharui Data Metode Pengujian";
        return redirect(route('metode_pengujian_labkon.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('labkon_master_metode_pengujian')->where('id', $id)->delete();
        $color = "success";
        $msg = "Berhasil Menghapus Data Metode Pengujian";
        return redirect(route('metode_pengujian_labkon.index'))->with(compact('color', 'msg'));
    }
}
