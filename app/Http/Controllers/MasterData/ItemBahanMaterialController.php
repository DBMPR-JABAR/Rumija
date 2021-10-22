<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemBahanMaterialController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Bahan Material', ['create', 'store'], ['index'], ['edit', 'update'], ['destroy']);
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
        $item_bahan_material = DB::table('item_bahan')->orderBy('keterangan','desc')->get();
        //dd($item_bahan_material[0]->id);
        return view('admin.master.item_bahan.index', compact('item_bahan_material'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.master.item_bahan.insert', ['action' => 'store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item_bahan_material['nama_item'] = $request->nama_item;
        $item_bahan_material['keterangan'] = $request->keterangan;
        if($item_bahan_material['keterangan'] != null){
            $item_bahan_material['keterangan'] = "Bahan Operasional";
        }else $item_bahan_material['keterangan'] = "";

        // dd($item_bahan_material);
        // $item_bahan_material['satuan'] = $request->satuan;
        DB::table('item_bahan')->insert($item_bahan_material);
        $color = "success";
        $msg = "Berhasil Menambah Data Item Bahan Material";

        Log::info(Auth::user()->name.' menambah master data bahan material '.$item_bahan_material['nama_item']);
        return redirect(route('item_bahan_material.index'))->with(compact('color', 'msg'));
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
        $item_bahan_material = DB::table('item_bahan')->where('no', $id)->first();
        return view('admin.master.item_bahan.insert', ['action' => 'upadate', 'item_bahan_material' => $item_bahan_material]);
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
        $item_bahan_material['nama_item'] = $request->nama_item;
        $item_bahan_material['keterangan'] = $request->keterangan;

        if($item_bahan_material['keterangan'] != null){
            $item_bahan_material['keterangan'] = "Bahan Operasional";
        }else $item_bahan_material['keterangan'] = "";
        // $item_bahan_material['satuan'] = $request->satuan;
        $exits = DB::table('item_bahan')->where('no', $id)->first();
        DB::table('item_bahan')->where('no', $id)->update($item_bahan_material);

        Log::info(Auth::user()->name.' memperbaharui master data bahan material '.$exits->nama_item);
        $color = "success";
        $msg = "Berhasil Memperbaharui Data Item Bahan Material";
        return redirect(route('item_bahan_material.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exits = DB::table('item_bahan')->where('no',$id)->first();
        $item_bahan_material = DB::table('item_bahan')->where('no', $id)->delete();

        Log::info(Auth::user()->name.' menghapus master data bahan material '. $exits->nama_item);
        $color = "success";
        $msg = "Berhasil Menghapus Data Item Bahan Material";
        return redirect(route('item_bahan_material.index'))->with(compact('color', 'msg'));
    }
}
