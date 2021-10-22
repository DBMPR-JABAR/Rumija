<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
use App\Model\Transactional\Jembatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kemandoran = DB::table('kemandoran');
        if(Auth::user()->internalRole->uptd){
            $uptd_id = str_replace('uptd','',Auth::user()->internalRole->uptd);
            $kemandoran = $kemandoran->where('uptd_id',$uptd_id);
        }

        $kemandoran = $kemandoran->get();
        return view('admin.input.keuangan.index', compact('kemandoran'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kemandoran = DB::table('kemandoran')->where('id_pek',$id)->first();

        return view('admin.input.keuangan.editspp', compact('kemandoran'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // var_dump($request);
        $kemandoran = $request->except('_token','file', 'id_pek');

        $old = DB::table('kemandoran')->where('id_pek',$request->id_pek)->first();

        if($request->file != null){
            $old->file ?? Storage::delete('public/'.$old->file);
            
            $path = 'pekerjaan/'.Str::snake(date("YmdHis").' '.$request->file->getClientOriginalName());

            $request->file->storeAs('public/',$path);
            $kemandoran ['file'] = $path;
        }

        // $kemandoran['updated_by'] = Auth::user()->id;
        DB::table('kemandoran')->where('id_pek',$request->id_pek)->update($kemandoran);

        $color = "success";
        $msg = "Berhasil Memperbaharui Data Pekerjaan";
        return back()->with(compact('color','msg'));
    }

    public function delete($id)
    {        
        $jembatan = DB::table('master_jembatan');
        $old = $jembatan->where('id',$id);
        $old->first()->foto ?? Storage::delete('public/'.$old->first()->foto);

        $old->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Jembatan";
        return redirect(route('getMasterJembatan'))->with(compact('color','msg'));
    }
}
