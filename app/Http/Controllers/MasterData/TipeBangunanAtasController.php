<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Model\Transactional\TipeBangunanAtas;
use Illuminate\Http\Request;

class TipeBangunanAtasController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Tipe Bangunan Atas', ['store'], ['index','show'], ['update'], ['edit']);
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
        $tbas = TipeBangunanAtas::all();
        return view("admin.master.tipebangunanatas.index", compact('tbas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tbas = new TipeBangunanAtas;
        $tbas->fill($request->all());
        $tbas->save();

        $color = "success";
        $msg = "Berhasil Menambah Data Tipe Bangunan Atas";
        return back()->with(compact('color', 'msg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Transactional\TipeBangunanAtas  $tipeBangunanAtas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = TipeBangunanAtas::find($id);
        return view('admin.master.tipebangunanatas.detail', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Transactional\TipeBangunanAtas  $tipeBangunanAtas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = TipeBangunanAtas::find($id);
        $data->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Tipe Bangunan Atas";
        return redirect(route('tipebangunanatas.index'))->with(compact('color', 'msg'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Transactional\TipeBangunanAtas  $tipeBangunanAtas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = TipeBangunanAtas::find($id);
        $data->fill($request->all());
        $data->save();

        $color = "success";
        $msg = "Berhasil Mengubah Data Tipe Bangunan Atas";
        return redirect(route('tipebangunanatas.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Transactional\TipeBangunanAtas  $tipeBangunanAtas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
