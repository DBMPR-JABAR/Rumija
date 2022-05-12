<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transactional\RumijaTipe;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RumijaTipeController extends Controller
{
    //
    public function index(Request $request)
    {
    	$rumija_tipe = RumijaTipe::all();
        // dd($rumija_tipe);
       	return view('admin.master.rumija_tipe.index',compact('rumija_tipe'));
    }
    public function store(Request $request)
    {
        // dd(Auth::user()->id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:rumija_tipe'
        ]);
        if ($validator->fails()) {
            storeLogActivity(declarLog(1, 'Rumija Tipe', $validator->errors()));
            $color = "danger";
            $msg = $validator->errors();
            return redirect(route('admin.rumija-tipe.index'))->with(compact('color', 'msg'));
        }
        
        $data = RumijaTipe::firstOrNew(['name' => $request->name]);
        $data->slug = Str::slug($request->name, '-');
        $data->created_by = Auth::user()->id;

        $data->save();
        storeLogActivity(declarLog(1, 'Rumija Tipe', $request->name,1));
    	return redirect(route('admin.rumija-tipe.index'))->with('sukses','Data Berhasil Ditambahkan!');
    }
    public function delete($id)
    {
    	$data = RumijaTipe::find($id);
        storeLogActivity(declarLog(3, 'Laporan Rumija', $data->name,1));
    	$data->delete();
    	return redirect(route('admin.rumija-tipe.index'))->with('sukses','Data Berhasil Dihapus!');

    }
}
