<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PelaporanController extends Controller
{
    //
    public function index(Request $request)
    {
    	$pelaporan = \App\Pelaporan::all();
       	return view('admin.input_data.pelaporan.index',['pelaporan' => $pelaporan]);
    }

    public function create(Request $request)
    {
    	\App\Pelaporan::create($request->all());
    	return redirect('/admin/pelaporan')->with('sukses','Data Berhasil Ditambahkan!');
    }

    public function store(Request $request)
    {
        $request->validate([
        ]);
        
    }    
    public function edit($id)
    {
    	$pelaporan = \App\Pelaporan::find($id);
    	return view('admin.input_data.pelaporan.edit',['pelaporan' => $pelaporan]);

    }
    public function update(Request $request,$id)
    {
    	$pelaporan = \App\Pelaporan::find($id);
    	$pelaporan->update($request->all());
    	return redirect('/admin/pelaporan')->with('sukses','Data Berhasil Diubah!');

    }
    public function delete($id)
    {
    	$pelaporan = \App\Pelaporan::find($id);
    	$pelaporan->delete($pelaporan);
    	return redirect('/admin/pelaporan')->with('sukses','Data Berhasil Dihapus!');

    }
}
