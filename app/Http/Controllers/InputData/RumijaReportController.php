<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transactional\RumijaReport;

class RumijaReportController extends Controller
{
    //
    public function index(Request $request)
    {
    	$pelaporan = RumijaReport::all();
        dd($pelaporan);
       	return view('admin.input_data.pelaporan.index',['pelaporan' => $pelaporan]);
    }

    public function create(Request $request)
    {
    	RumijaReport::create($request->all());
    	return redirect('/admin/pelaporan')->with('sukses','Data Berhasil Ditambahkan!');
    }

    public function store(Request $request)
    {
        $request->validate([
        ]);
        
    }    
    public function edit($id)
    {
    	$pelaporan = RumijaReport::find($id);
    	return view('admin.input_data.pelaporan.edit',['pelaporan' => $pelaporan]);

    }
    public function update(Request $request,$id)
    {
    	$pelaporan = RumijaReport::find($id);
    	$pelaporan->update($request->all());
    	return redirect('/admin/pelaporan')->with('sukses','Data Berhasil Diubah!');

    }
    public function delete($id)
    {
    	$pelaporan = RumijaReport::find($id);
    	$pelaporan->delete($pelaporan);
    	return redirect('/admin/pelaporan')->with('sukses','Data Berhasil Dihapus!');

    }
}
