<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
// use App\Model\DWH\RawanBencana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DataTables;

class RekapController extends Controller
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
        $rekap = DB::table('rekap');
        if(Auth::user()->internalRole->uptd){
            $uptd_id = str_replace('uptd','',Auth::user()->internalRole->uptd);
            $laporan = $rekap->where('UPTD',$uptd_id);
        }
        $rekap = $rekap->get();
        return view('admin.input.rekap.index', compact('rekap'));
    }

     public function json(){
        $rekap = DB::table('rekap');
        $rekap = $rekap->leftJoin('master_ruas_jalan', 'master_ruas_jalan.id', '=', 'rekap.ruas_jalan')->select('rekap.*', 'master_ruas_jalan.nama_ruas_jalan');
        if (Auth::user()->internalRole->uptd) {
            $rekap = $rekap->where('uptd_id',Auth::user()->internalRole->uptd);
        }
        $rekap = $rekap->get();
        return Datatables::of($rekap)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                   $html = '';
                   $html.='<div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">
                                        <a href="http://www.google.com/maps/place/'.$row->lat.','.$row->lng.'" target="_blank" data-toggle="tootip" title="Lokasi" class="btn btn-warning btn-sm waves-effect waves-light"><i class="icofont icofont-location-pin"></i></a>
                                        <a href="https://www.google.com/maps/@?api=1&map_action=pano&viewpoint='.$row->lat.','.$row->lng.'&heading=13&pitch=93&fov=80" target="_blank" data-toggle="tootip" title="StreetView" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-street-view"></i></a>
                                    </div>';
                    return $html;

                })
                ->make(true);
    }


     public function getData()
    {
        $rekap = DB::table('rekap');
        $rekap = $rekap->leftJoin('master_ruas_jalan', 'master_ruas_jalan.id', '=', 'rekap.ruas_jalan')->select('rekap.*', 'master_ruas_jalan.nama_ruas_jalan');
        // print_r(Auth::user()->internalRole->uptd);
        if (Auth::user()->internalRole->uptd) {
            $rekap = $rekap->where('uptd_id',Auth::user()->internalRole->uptd);
        }
        $rekap = $rekap->where('is_deleted',0)->get();

        $ruas_jalan = DB::table('master_ruas_jalan');
         if (Auth::user()->internalRole->uptd) {
            $ruas_jalan = $ruas_jalan->where('uptd_id',Auth::user()->internalRole->uptd);
        }
        $ruas_jalan = $ruas_jalan->get();

        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $sup = $sup->where('uptd_id',Auth::user()->internalRole->uptd);
        }
        $sup = $sup->get();

        $jenis = DB::table('item_pekerjaan');
        $jenis = $jenis->get();


        $mandor = DB::table('users')->where('user_role.id',19);
        $mandor = $mandor->leftJoin('user_role', 'user_role.id', '=', 'users.internal_role_id')->select('users.*', 'user_role.id as id_role');
        $mandor = $mandor->get();

        $uptd = DB::table('landing_uptd')->get();
        return view('admin.input.rekap.index',compact('rekap','ruas_jalan','sup','uptd','mandor','jenis'));
    }
   
}
