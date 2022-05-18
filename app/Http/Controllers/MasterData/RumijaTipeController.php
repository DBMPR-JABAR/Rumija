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
        $words = preg_split("/\s+/", $request->name);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= $w[0];
        }

        $data = RumijaTipe::firstOrNew(['name' => $request->name]);
        $data->slug = Str::slug($request->name, '-');
        $data->kode = $acronym;
        $data->created_by = Auth::user()->id;

        $data->save();
        storeLogActivity(declarLog(1, 'Rumija Tipe', $request->name,1));
        $color = "success";
        $msg = "Data Berhasil Ditambahkan!";
    	return redirect(route('admin.rumija-tipe.index'))->with(compact('color', 'msg'));
    }
    public function delete($id)
    {
        $color = "success";
        $msg = "Berhasil Menghapus Data Rumija Tipe!";
    	$data = RumijaTipe::find($id);
        // dd($data->library_rumija_permohonan->count());
        if($data->library_rumija_report->count() > 0 || $data->library_rumija_permohonan->count() > 0){
            storeLogActivity(declarLog(3, 'Rumija Tipe', $data->name));
            $color = "danger";
            $msg = "Data Gagal Dihapus! Tipe Sudah Digunakan";
            return redirect(route('admin.rumija-tipe.index'))->with(compact('color', 'msg'));
        }
        storeLogActivity(declarLog(3, 'Rumija Tipe', $data->name,1));
    	$data->delete();
    	return redirect(route('admin.rumija-tipe.index'))->with(compact('color', 'msg'));

    }
}
