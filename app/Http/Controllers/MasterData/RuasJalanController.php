<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Model\DWH\RuasJalan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\DataTables;


class RuasJalanController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Ruas Jalan', ['create'],['index','json'],['edit','update'],['delete']);
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
        $response = [
            'status' => 'false',
            'data' => []
        ];

        $ruasJalan = DB::table('master_ruas_jalan');
        $uptd = DB::table('landing_uptd');
        $sup = DB::table('utils_sup');

        $ruasJalan = $ruasJalan->leftJoin('utils_sup', 'utils_sup.id', '=', 'master_ruas_jalan.sup')->select('master_ruas_jalan.*', 'utils_sup.name as supName');

        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $ruasJalan = $ruasJalan->where('master_ruas_jalan.uptd_id', $uptd_id);
            $sup = $sup->where('uptd_id', $uptd_id);
            $uptd = $uptd->where('slug', Auth::user()->internalRole->uptd);
        }
        $ruasJalan = $ruasJalan->get();
        // dd($ruasJalan);
        $uptd = $uptd->get();
        $sup = $sup->get();
        return view('admin.master.ruas_jalan.index', compact('ruasJalan', 'uptd', 'sup'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $ruasJalan = $req->except('_token', 'gambar','foto','foto_1','foto_2','video');

        $getsup= DB::table('utils_sup')->where('id', $req->sup)->select('kd_sup','name')->first();
        $ruasJalan['kd_sppjj'] = $getsup->kd_sup;
        $ruasJalan['nm_sppjj'] = $getsup->name;
        $ruasJalan['wil_uptd'] = DB::table('landing_uptd')->where('id', $req->uptd_id)->pluck('nama')->first();
        if ($req->file('foto') != null) {
            $path = 'ruasJalan/' . Str::snake(date("YmdHis") . ' ' . $req->file('foto')->getClientOriginalName());
            $req->file('foto')->storeAs('public/', $path);
            $ruasJalan['foto'] = $path;
        }
        if ($req->file('foto_1') != null) {
            $path = 'ruasJalan/' . Str::snake(date("YmdHis") . ' ' . $req->file('foto_1')->getClientOriginalName());
            $req->file('foto_1')->storeAs('public/', $path);
            $ruasJalan['foto_1'] = $path;
        }
        if ($req->file('foto_2') != null) {
            $path = 'ruasJalan/' . Str::snake(date("YmdHis") . ' ' . $req->file('foto_2')->getClientOriginalName());
            $req->file('foto_2')->storeAs('public/', $path);
            $ruasJalan['foto_2'] = $path;
        }
        if ($req->file('video') != null) {
            $path = 'ruasJalan/' . Str::snake(date("YmdHis") . ' ' . $req->file('video')->getClientOriginalName());
            $req->file('video')->storeAs('public/', $path);
            $ruasJalan['video'] = $path;
        }
        $ruasJalan['created_by'] = Auth::user()->id;
        $ruasJalan['created_date'] = date("YmdHis");

        DB::table('master_ruas_jalan')->insert($ruasJalan);

        $color = "success";
        $msg = "Berhasil Menambah Data Ruas Jalan";
        return back()->with(compact('color', 'msg'));
    }

    public function edit($id)
    {
        $ruasJalan = DB::table('master_ruas_jalan')->where('id', $id)->first();

        $sup = DB::table('utils_sup');
        $uptd = DB::table('landing_uptd');

        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $uptd = $uptd->where('slug', Auth::user()->internalRole->uptd);
        }

        $sup = $sup->where('uptd_id', $ruasJalan->uptd_id);
        $uptd = $uptd->get();
        $sup = $sup->get();
        // print_r($ruasJalan->uptd_id);
        return view('admin.master.ruas_jalan.edit', compact('ruasJalan', 'sup', 'uptd'));
    }

    public function update(Request $req)
    {
        //
        $ruasJalan = $req->except('_token', 'gambar', 'id','foto','foto_1','foto_2','video');
        // $ruasJalan['slug'] = Str::slug($req->nama, '');
        $getsup= DB::table('utils_sup')->where('id', $req->sup)->select('kd_sup','name')->first();
        $ruasJalan['kd_sppjj'] = $getsup->kd_sup;
        $ruasJalan['nm_sppjj'] = $getsup->name;
        $ruasJalan['wil_uptd'] = DB::table('landing_uptd')->where('id', $req->uptd_id)->pluck('nama')->first();
        // dd($ruasJalan);
        if ($req->file('foto') != null) {
            $path = 'ruasJalan/' . Str::snake(date("YmdHis") . ' ' . $req->file('foto')->getClientOriginalName());
            $req->file('foto')->storeAs('public/', $path);
            $ruasJalan['foto'] = $path;
        }
        if ($req->file('foto_1') != null) {
            $path = 'ruasJalan/' . Str::snake(date("YmdHis") . ' ' . $req->file('foto_1')->getClientOriginalName());
            $req->file('foto_1')->storeAs('public/', $path);
            $ruasJalan['foto_1'] = $path;
        }
        if ($req->file('foto_2') != null) {
            $path = 'ruasJalan/' . Str::snake(date("YmdHis") . ' ' . $req->file('foto_2')->getClientOriginalName());
            $req->file('foto_2')->storeAs('public/', $path);
            $ruasJalan['foto_2'] = $path;
        }
        if ($req->file('video') != null) {
            $path = 'ruasJalan/' . Str::snake(date("YmdHis") . ' ' . $req->file('video')->getClientOriginalName());
            $req->file('video')->storeAs('public/', $path);
            $ruasJalan['video'] = $path;
        }
        $ruasJalan['updated_by'] = Auth::user()->id;
        $ruasJalan['updated_date'] = date("YmdHis");

        $old = DB::table('master_ruas_jalan')->where('id', $req->id)->first();

        DB::table('master_ruas_jalan')->where('id', $req->id)->update($ruasJalan);

        $color = "success";
        $msg = "Berhasil Mengubah Data Ruas Jalan";
        return redirect(route('getMasterRuasJalan'))->with(compact('color', 'msg'));
    }

    public function delete($id)
    {
        $ruasJalan = DB::table('master_ruas_jalan');
        $old = $ruasJalan->where('id', $id);

        $old->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Ruas Jalan";
        return redirect(route('getMasterRuasJalan'))->with(compact('color', 'msg'));
    }

    public function getSUP(Request $req)
    {
        $idSup = $req->id;
        $sup = DB::table('utils_sup');
        $sup = $sup->where('uptd_id', $idSup);
        $sup = $sup->get();

        return response()->json($sup);
    }
    public function getCITIES(Request $req)
    {
        $idSup = $req->id;
        $sup = DB::table('indonesia_cities');
        $sup = $sup->where('province_id', $idSup);
        $sup = $sup->get();

        return response()->json($sup);
    }

    public function json()
    {
        $ruasJalan = DB::table('master_ruas_jalan');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $ruasJalan =  $ruasJalan->where('uptd_id', $uptd_id);
        }
        return DataTables::of($ruasJalan)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $textedit = 'editMasterRuasJalan';
                $btn = '<div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">';

                if (hasAccess(Auth::user()->internal_role_id, "Ruas Jalan", "Update")) {
                    $btn = $btn . '<a href="' . route('editMasterRuasJalan', $row->id) . '"><button data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-pencil"></i></button></a>';
                }

                if (hasAccess(Auth::user()->internal_role_id, "Ruas Jalan", "Delete")) {
                    $btn = $btn . '<a href="#delModal" data-id="' . $row->id . '" data-toggle="modal"><button data-toggle="tooltip" title="Hapus" class="btn btn-danger btn-sm waves-effect waves-light"><i class="icofont icofont-trash"></i></button></a>';
                }
                $btn = $btn . '</div>';

                // $btn = '<a href="javascript:void(0)" class="btn btn-primary">' . $row->id . '</a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
