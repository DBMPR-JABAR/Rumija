<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CCTVController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('CCTV', ['create'], ['index','detail','getDataSUP'], ['edit', 'update'], ['delete']);
        foreach ($roles as $role => $permission) {
            $this->middleware($role)->only($permission);
        }
    }
    public function index()
    {
        $cctv = DB::table('cctv');
        $uptd = DB::table('landing_uptd');
        if (Auth::user()->internalRole->uptd) {
            $cctv = $cctv->where('uptd_id', str_replace('uptd', '', Auth::user()->internalRole->uptd));
            $uptd = $uptd->where('id',str_replace('uptd', '', Auth::user()->internalRole->uptd));
        }
        $uptd = $uptd->select('id', 'nama')->get();
        $cctv = $cctv->get();
        // dd($cctv->get());
        return view('admin.master.CCTV.index', compact('cctv', 'uptd'));
    }

    public function create(Request $request)
    {
        $cctv['lokasi'] = $request->lokasi;
        $cctv['lat'] = $request->lat;
        $cctv['long'] = $request->long;
        $cctv['url'] = $request->url;
        $cctv['description'] = $request->description;
        $cctv['category'] = $request->category;
        $cctv['status'] = $request->status;
        // $cctv['enable_vehicle_counting'] = $request->enable_vehicle_counting;
        $cctv['uptd_id'] = $request->uptd_id;
        $cctv['sup'] = $request->sup;
        DB::table('cctv')->insert($cctv);
        $color = "success";
        $msg = "Berhasil Menambah Data CCTV";
        return back()->with(compact('color', 'msg'));
    }

    public function edit($id)
    {
        $cctv = DB::table('cctv')
            ->where('id', $id)
            ->get();
        return response()->json(["cctv" => $cctv], 200);
    }
    public function detail($id)
    {
        $cctv = DB::table('cctv')->where('ID', $id)->get();
        return view('admin.master.CCTV.detail', compact('cctv'));
    }

    public function update(Request $request)
    {
        $cctv['lokasi'] = $request->lokasi;
        $cctv['lat'] = $request->lat;
        $cctv['long'] = $request->long;
        $cctv['url'] = $request->url;
        $cctv['description'] = $request->description;
        $cctv['category'] = $request->category;
        $cctv['status'] = $request->status;
        // $cctv['enable_vehicle_counting'] = $request->enable_vehicle_counting;
        $cctv['uptd_id'] = $request->uptd_id;
        $cctv['sup'] = $request->sup;
        DB::table('cctv')->where('ID', $request->id)->update($cctv);
        $color = "success";
        $msg = "Berhasil Mengupdate Data CCTV";
        return back()->with(compact('color', 'msg'));
    }

    public function delete($id)
    {
        $user_role = DB::table('cctv')
            ->where('id', $id)
            ->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data CCTV";
        return back()->with(compact('color', 'msg'));
    }

    public function getDataSUP($id)
    {
        $sup = DB::table('utils_sup as a')
            ->distinct()
            ->where('a.uptd_id', $id)
            ->get();
        return response()->json(['sup' => $sup], 200);
    }
}
