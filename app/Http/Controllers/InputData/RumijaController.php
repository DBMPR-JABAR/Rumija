<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RumijaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rumija = DB::table('rumija')
            ->leftJoin('landing_uptd', 'landing_uptd.id', 'rumija.uptd')
            ->select('rumija.*', 'landing_uptd.nama as uptd_name');

        $filter = null;

        if ($request->filter) {
            $filter = (object)[
                "uptd" => $request->uptd,
            ];
           if($request->uptd != 'ALL')  $rumija = $rumija->where('rumija.uptd',$request->uptd);
        }

        $rumija = $rumija->get();
        $uptd = DB::table('landing_uptd')->get();
        return view('admin.input_data.rumija.index', compact('rumija','filter','uptd'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $uptd = DB::table('landing_uptd')->get();
        $ruas_jalan = DB::table('master_ruas_jalan')->get();
        $kab_kota = DB::table('indonesia_cities')->where('province_id', 32)->get();
        $action = 'store';
        return view('admin.input_data.rumija.insert', compact('uptd', 'ruas_jalan', 'kab_kota', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rumija = $request->except('_token', 'foto', 'video', 'foto_1', 'foto_2');
        if ($request->file('foto') != null) {
            $path = 'rumija/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto')->getClientOriginalName());
            $request->file('foto')->storeAs('public/', $path);
            $rumija['foto'] = $path;
        }
        if ($request->file('foto_1') != null) {
            $path = 'rumija/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto_1')->getClientOriginalName());
            $request->file('foto_1')->storeAs('public/', $path);
            $rumija['foto_1'] = $path;
        }
        if ($request->file('foto_2') != null) {
            $path = 'rumija/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto_2')->getClientOriginalName());
            $request->file('foto_2')->storeAs('public/', $path);
            $rumija['foto_2'] = $path;
        }
        if ($request->file('video') != null) {
            $path = 'rumija/' . Str::snake(date("YmdHis") . ' ' . $request->file('video')->getClientOriginalName());
            $request->file('video')->storeAs('public/', $path);
            $rumija['video'] = $path;
        }
        DB::table('rumija')->insert($rumija);
        $color = "success";
        $msg = "Berhasil Menambah Data Pemanfaatan Rumija";
        return redirect(route('rumija.index'))->with(compact('color', 'msg'));
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
        $uptd = DB::table('landing_uptd')->get();
        $rumija = DB::table('rumija')->where('id', $id)->first();
        $ruas_jalan = DB::table('master_ruas_jalan')->get();
        $kab_kota = DB::table('indonesia_cities')->where('province_id', 32)->get();
        $action = 'update';
        return view('admin.input_data.rumija.insert', compact('uptd', 'rumija', 'ruas_jalan', 'kab_kota', 'action'));
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
        $rumija = $request->except('_token', '_method', 'foto', 'video', 'foto_1', 'foto_2');
        if ($request->file('foto') != null) {
            $path = 'rumija/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto')->getClientOriginalName());
            $request->file('foto')->storeAs('public/', $path);
            $rumija['foto'] = $path;
        }
        if ($request->file('video') != null) {
            $path = 'rumija/' . Str::snake(date("YmdHis") . ' ' . $request->file('video')->getClientOriginalName());
            $request->file('video')->storeAs('public/', $path);
            $rumija['video'] = $path;
        }
        if ($request->file('foto_1') != null) {
            $path = 'rumija/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto_1')->getClientOriginalName());
            $request->file('foto_1')->storeAs('public/', $path);
            $rumija['foto_1'] = $path;
        }
        if ($request->file('foto_2') != null) {
            $path = 'rumija/' . Str::snake(date("YmdHis") . ' ' . $request->file('foto_2')->getClientOriginalName());
            $request->file('foto_2')->storeAs('public/', $path);
            $rumija['foto_2'] = $path;
        }
        DB::table('rumija')->where('id', $id)->update($rumija);
        $color = "success";
        $msg = "Berhasil Memperbaharui Data Pemanfaatan Rumija";
        return redirect(route('rumija.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('rumija')->where('id', $id)->delete();
        $color = "success";
        $msg = "Berhasil Memnghapus Data Pemanfaatan Rumija";
        return redirect(route('rumija.index'))->with(compact('color', 'msg'));
    }
}
