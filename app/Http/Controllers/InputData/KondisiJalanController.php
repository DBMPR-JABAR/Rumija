<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
use App\Model\DWH\KondisiJalan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DataTables;

class KondisiJalanController extends Controller
{
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

        $kondisiJalan = DB::table('tbl_uptd_trx_master_kondisi_jalan');
        $uptd = DB::table('landing_uptd');
        $sup = DB::table('utils_sup');


        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $kondisiJalan = $kondisiJalan->where('uptd', $uptd_id);
        }
        $kondisiJalan = $kondisiJalan->get();
        $uptd = $uptd->get();
        $sup = $sup->get();
        return view('admin.input_data.kondisi_jalan.index', compact('kondisiJalan', 'uptd', 'sup'));
    }

    public function getRJ()
    {
        $kondisiJalan = DB::table('tbl_uptd_trx_master_kondisi_jalan');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $kondisiJalan = $kondisiJalan->where('uptd', $uptd_id);
        }
        $kondisiJalan = $kondisiJalan->get();
        return Datatables::of($kondisiJalan)
            // ->addIndexColumn()
            // ->addColumn('dokumentasi', function($row){
            //     $path = 'storage/';
            //     $html = '<div><img class="img-fluid" style="max-width: 100px" src="'.url($path.$row->foto_dokumentasi) .'" alt="" srcset=""></div>';
            //     return $html;
            // })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '';
                if (hasAccess(Auth::user()->internal_role_id, "Kondisi Jalan", "Update")) {
                    $html .= ' <a href="' . route('editIDKondisiJalan', $row->id) . '"><button class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="tooltip" title="Edit"><i class="icofont icofont-pencil"></i></button></a>';
                }
                if (hasAccess(Auth::user()->internal_role_id, "Kondisi Jalan", "Delete")) {
                    $html .= '  <a href="#delModal" data-id="' . $row->id . '" data-toggle="modal"><button class="btn btn-danger btn-sm waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>';
                }
                return $html;
            })
            ->make(true);
    }

    public function create(Request $req)
    {
        //
        $kondisiJalan = $req->except('_token', 'foto_dokumentasi');

        if ($req->foto_dokumentasi != null) {
            $path = 'landing/kondisiJalan/' . Str::snake(date("YmdHis") . ' ' . $req->foto_dokumentasi->getClientOriginalName());
            $req->foto_dokumentasi->storeAs('public/', $path);
            $kondisiJalan['foto_dokumentasi'] = $path;
        }

        $kondisiJalan['created_by'] = Auth::user()->id;
        $kondisiJalan['created_at'] = date("YmdHis");

        DB::table('tbl_uptd_trx_master_kondisi_jalan')->insert($kondisiJalan);

        $color = "success";
        $msg = "Berhasil Menambah Data Kondisi Jalan";
        return redirect(route('getIDKondisiJalan'))->with(compact('color', 'msg'));
    }

    public function edit($id)
    {
        $uptd = DB::table('landing_uptd');
        $kondisiJalan = DB::table('tbl_uptd_trx_master_kondisi_jalan')->where('id', $id)->first();
        $uptd = $uptd->get();

        $ruasJalan = DB::table('master_ruas_jalan');
        $ruasJalan = $ruasJalan->where('master_ruas_jalan.uptd_id', $kondisiJalan->uptd);
        $ruasJalan = $ruasJalan->get();

        return view('admin.input_data.kondisi_jalan.edit', compact('kondisiJalan', 'uptd', 'ruasJalan'));
    }

    public function add()
    {

        $uptd = DB::table('landing_uptd');
        $uptd = $uptd->get();

        $ruasJalan = DB::table('master_ruas_jalan');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $ruasJalan = $ruasJalan->where('uptd_id', $uptd_id);
        }
        $ruasJalan = $ruasJalan->get();

        return view('admin.input_data.kondisi_jalan.add', compact('uptd', 'ruasJalan'));
    }

    public function update(Request $req)
    {
        $kondisiJalan = $req->except('_token', 'foto_dokumentasi', 'id');
        $kondisiJalan['updated_by'] = Auth::user()->id;
        $kondisiJalan['updated_at'] = date("YmdHis");

        $old = DB::table('tbl_uptd_trx_master_kondisi_jalan')->where('id', $req->id)->first();

        if ($req->foto_dokumentasi != null) {
            $old->foto_dokumentasi ?? Storage::delete('public/' . $old->foto_dokumentasi);

            $path = 'landing/kondisiJalan/' . Str::snake(date("YmdHis") . ' ' . $req->foto_dokumentasi->getClientOriginalName());
            $req->foto_dokumentasi->storeAs('public/', $path);
            $kondisiJalan['foto_dokumentasi'] = $path;
        }

        if ($req->kondisi == 'Mantap') {
            $kondisiJalan['jlk_lokasi'] = NULL;
            $kondisiJalan['jlk_panjang'] = NULL;
            $kondisiJalan['ja_lat'] = NULL;
            $kondisiJalan['ja_long'] = NULL;
            $kondisiJalan['jak_lat'] = NULL;
            $kondisiJalan['jak_long'] = NULL;
            $kondisiJalan['parah_lokasi'] = NULL;
            $kondisiJalan['parah_panjang'] = NULL;
            $kondisiJalan['pa_lat'] = NULL;
            $kondisiJalan['pa_long'] = NULL;
            $kondisiJalan['pak_lat'] = NULL;
            $kondisiJalan['pak_long'] = NULL;
            $kondisiJalan['sp_lokasi'] = NULL;
            $kondisiJalan['sp_panjang'] = NULL;
            $kondisiJalan['spa_lat'] = NULL;
            $kondisiJalan['spa_long'] = NULL;
            $kondisiJalan['spak_lat'] = NULL;
            $kondisiJalan['spak_long'] = NULL;
            $kondisiJalan['hancur_lokasi'] = NULL;
            $kondisiJalan['hancur_panjang'] = NULL;
            $kondisiJalan['ha_lat'] = NULL;
            $kondisiJalan['ha_long'] = NULL;
            $kondisiJalan['hak_lat'] = NULL;
            $kondisiJalan['hak_long'] = NULL;
        } else {
            $kondisiJalan['sb_lokasi'] = NULL;
            $kondisiJalan['sb_panjang'] = NULL;
            $kondisiJalan['sba_lat'] = NULL;
            $kondisiJalan['sba_long'] = NULL;
            $kondisiJalan['sbak_lat'] = NULL;
            $kondisiJalan['sbak_long'] = NULL;
            $kondisiJalan['b_lokasi'] = NULL;
            $kondisiJalan['b_panjang'] = NULL;
            $kondisiJalan['ba_lat'] = NULL;
            $kondisiJalan['ba_long'] = NULL;
            $kondisiJalan['bak_lat'] = NULL;
            $kondisiJalan['bak_long'] = NULL;
            $kondisiJalan['sd_lokasi'] = NULL;
            $kondisiJalan['sd_panjang'] = NULL;
            $kondisiJalan['sda_lat'] = NULL;
            $kondisiJalan['sda_long'] = NULL;
            $kondisiJalan['sdak_lat'] = NULL;
            $kondisiJalan['sdak_long'] = NULL;
        }

        DB::table('tbl_uptd_trx_master_kondisi_jalan')->where('id', $req->id)->update($kondisiJalan);

        $color = "success";
        $msg = "Berhasil Mengubah Data Kondisi Jalan";
        return redirect(route('getIDKondisiJalan'))->with(compact('color', 'msg'));
    }

    public function delete($id)
    {
        $kondisiJalan = DB::table('tbl_uptd_trx_master_kondisi_jalan');
        $old = $kondisiJalan->where('id', $id);

        $old->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Kondisi Jalan";
        return redirect(route('getIDKondisiJalan'))->with(compact('color', 'msg'));
    }

    public function getRuasJalan(Request $req)
    {
        $idSup = $req->id;
        $sup = DB::table('master_ruas_jalan');
        $sup = $sup->where('uptd_id', $idSup);
        $sup = $sup->get();

        return response()->json($sup);
    }
    public function getRuasJalanBySup(Request $req)
    {
        $idSup = $req->id;
        $sup = DB::table('master_ruas_jalan');
        $sup = $sup->where('kd_sppjj', $idSup);
        $sup = $sup->get();

        return response()->json($sup);
    }
}
