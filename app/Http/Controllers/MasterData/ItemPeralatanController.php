<?php

namespace App\Http\Controllers\MasterData;

use App\ItemPeralatan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemPeralatanController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Peralatan', ['create', 'store'], ['index'], ['edit', 'update'], ['destroy']);
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
        //
        $item_peralatan = ItemPeralatan::get();
        // dd($item_peralatan);
        return view('admin.master.item_peralatan.index', compact('item_peralatan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $action="store";
        return view('admin.master.item_peralatan.insert', compact('action'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'nama_peralatan'=> 'required',
            'satuan'         => ''
           
        ]);
        $peralatan = [
            "nama_peralatan"=>$request->nama_peralatan, 
            "satuan"=>$request->satuan

        ];
        $peralatan = ItemPeralatan::create($peralatan)->save();
        if($peralatan){
            $color = "success";
            $msg = "Data Berhasil Disimpan!";
        }else{
            $color = "danger";
            $msg = "Data Gagal Disimpan!";
        }
        return redirect()->route('item_peralatan.index')->with(compact('color', 'msg'));
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
    public function edit(ItemPeralatan $item_peralatan)
    {
        //
        $action = 'edit';
        return view('admin.master.item_peralatan.insert', compact('action','item_peralatan'));

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
        //
        $this->validate($request,[
            'nama_peralatan'=> 'required',
            'satuan'         => ''
        ]);
        $peralatan = [
            "nama_peralatan"=>$request->nama_peralatan, 
            "satuan"=>$request->satuan
        ];
        $peralatan = ItemPeralatan::findOrFail($id)->update($peralatan);
        if($peralatan){
            $color = "success";
            $msg = "Data Berhasil Disimpan!";
        }else{
            $color = "danger";
            $msg = "Data Gagal Disimpan!";
        }
        return redirect()->route('item_peralatan.index')->with(compact('color', 'msg'));
        // dd($request->nama_peralatan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $peralatan = ItemPeralatan::findOrFail($id)->delete();
        if($peralatan){
            $color = "success";
            $msg = "Data Berhasil Dihapus!";
        }else{
            $color = "danger";
            $msg = "Data Gagal Dihapus!";
        }
        return redirect()->route('item_peralatan.index')->with(compact('color', 'msg'));
    }
}
