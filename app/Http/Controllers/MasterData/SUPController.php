<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SUPController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('SUP', ['store', ''], ['index'], ['edit', 'update'], ['destroy']);
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
        $sup = DB::table('utils_sup');
        if(Auth::user() && Auth::user()->internalRole->uptd != null){
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $sup = $sup->where('uptd_id',$uptd_id);
        }
        $sup = $sup->get();

        return view('admin.master.sup.index', compact('sup'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $this->validate($request, [
            'name'      => 'required'
        ]);
        $validator = Validator::make($request->all(), [
            'kd_sup' => 'required|unique:utils_sup'
            
        ]);
        if ($validator->fails()) {
            $color = "danger";
            $msg = "Kode sudah ada";
            return back()->with(compact('color', 'msg'));
        }

        $uptd_id = $request->uptd_id;
        $sup['name'] = $request->name;
        $sup['kd_sup'] = $request->kd_sup;


        if(Auth::user() && Auth::user()->internalRole->uptd != null)
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);

        $sup['uptd_id'] = $uptd_id;
        $storesup=DB::table('utils_sup')->insert($sup);
        if($storesup){
            //redirect dengan pesan sukses
            $color = "success";
            $msg = "SUP Berhasil Ditambah!";
            return redirect(route('goSUP'))->with(compact('color','msg'));
        }else{
            //redirect dengan pesan error
            $color = "danger";
            $msg = "Data Gagal Ditambah!";
            return redirect(route('goSUP'))->with(compact('color', 'msg'));

        }

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
        $sup = DB::table('utils_sup')->where('id',$id)->first();
        return view('admin.master.sup.edit', compact('sup'));

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
        $this->validate($request, [
            'name'      => 'required'
        ]);

        $validator = Validator::make($request->all(), [
            'kd_sup' => Rule::unique('utils_sup', 'kd_sup')->ignore($id)
            
        ]);

        if ($validator->fails()) {
            $color = "danger";
            $msg = "Kode telah terdaftar";
            return back()->with(compact('color', 'msg'));
        }

        $uptd_id = $request->uptd_id;
        $sup['name'] = $request->name;
        $sup['kd_sup'] = $request->kd_sup;

        if(Auth::user() && Auth::user()->internalRole->uptd != null)
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);

        $sup['uptd_id'] = $uptd_id;
        $updatesup=DB::table('utils_sup')->where('id', $id)->update($sup);

        if($updatesup){
            //redirect dengan pesan sukses
            $color = "success";
            $msg = "SUP Berhasil Diperbaharui!";
            return redirect(route('goSUP'))->with(compact('color','msg'));
        }else{
            //redirect dengan pesan error
            $color = "danger";
            $msg = "Data Gagal atau Tidak Ada yang Diperbaharui!";
            return redirect(route('goSUP'))->with(compact('color', 'msg'));

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_role = DB::table('utils_sup')
        ->where('id',$id)
        ->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data SUP";
        return back()->with(compact('color', 'msg'));
    }
}
