<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\Datatables\DataTable;

class IconController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Icon Rawan Bencana', ['create'], ['detail','index'], ['edit', 'update'], ['delete']);
        foreach ($roles as $role => $permission) {
            $this->middleware($role)->only($permission);
        }
    }
    public function index(){
    	$icon = DB::table('icon_titik_rawan_bencana')->get();
    	return view('admin.master.icon.index',compact('icon'));
    }

    public function create(Request $req){
    	$icon['icon_name'] = $req->icon_name;
    	if ($req->icon_image != null) {
            $path = 'rawanbencana/icon' . Str::snake(date("YmdHis") . ' ' . $req->icon_image->getClientOriginalName());
            $req->icon_image->storeAs('public/', $path);
            $icon['icon_image'] = $path;
        }
        DB::table('icon_titik_rawan_bencana')->insert($icon);
    	$color = "success";
        $msg = "Berhasil Menambah Data Icon";
        return back()->with(compact('color', 'msg'));
    }

    public function detail($id){
    	$icon = DB::table('icon_titik_rawan_bencana')->where('id',$id)->get();
    	return view('admin.master.icon.detail',compact('icon'));
    }

    public function edit($id){
    	$icon = DB::table('icon_titik_rawan_bencana')->where('id',$id)->get();
    	return response()->json(["icon" => $icon], 200);
    }

    public function update(Request $req){
    	$icon['icon_name'] = $req->icon_name;
    	if ($req->icon_image != null) {
            $path = 'rawanbencana/icon' . Str::snake(date("YmdHis") . ' ' . $req->icon_image->getClientOriginalName());
            $req->icon_image->storeAs('public/', $path);
            $icon['icon_image'] = $path;
            $icon_rawan['icon_image'] = $path;
        }
        DB::table('icon_titik_rawan_bencana')->where('id',$req->id)->update($icon);
        DB::table('master_rawan_bencana')->where('icon_id',$req->id)->update($icon_rawan);
    	$color = "success";
        $msg = "Berhasil Menambah Data Icon";
        return back()->with(compact('color', 'msg'));
    }

    public function delete($id){
    	$icon = DB::table('icon_titik_rawan_bencana')->where('id',$id)->delete();
    	$color = "success";
        $msg = "Berhasil Menghapus Data Icon";
        return back()->with(compact('color', 'msg'));
    }
}
