<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Model\DWH\LaporanBencana;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\Datatables\DataTables;

class LaporanBencanaController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Laporan Bencana', ['createData'], ['index', 'getData', 'getDataSUP', 'getURL', 'json'], ['editData', 'updateData'], ['deleteData']);
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
        $laporan_bencana = DB::table('laporan_bencana');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $laporan_bencana = $laporan_bencana->where('uptd_id', $uptd_id);
        }
        $laporan_bencana = $laporan_bencana->get();
        return view('admin.master.laporan_bencana.index', compact('laporan_bencana', 'icon'));
    }


    public function getData()
    {
        $laporan_bencana = DB::table('laporan_bencana');
        $ruas = DB::table('master_ruas_jalan');
        $laporan_bencana = $laporan_bencana->leftJoin('master_ruas_jalan', 'master_ruas_jalan.id', '=', 'laporan_bencana.ruas_jalan')->select('laporan_bencana.*', 'master_ruas_jalan.nama_ruas_jalan');
        // print_r(Auth::user()->internalRole->uptd);
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $laporan_bencana = $laporan_bencana->where('laporan_bencana.uptd_id', $uptd_id);
            $ruas = $ruas->where('master_ruas_jalan.uptd_id', $uptd_id);
        }
        $laporan_bencana = $laporan_bencana->get();
        $ruas = $ruas->get();
        $uptd = DB::table('landing_uptd')->get();
        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $sup = $sup->where('uptd_id', $uptd_id);
        }
        $sup = $sup->get();
        $icon = DB::table('icon_titik_rawan_bencana')->get();
        return view('admin.master.laporan_bencana.index', compact('laporan_bencana', 'ruas', 'uptd', 'icon', 'sup'));
    }
    public function getDataSUP($id)
    {
        $sup = DB::table('utils_sup as a')
            ->distinct()
            ->where('a.uptd_id', $id)
            ->get();
        return response()->json(['sup' => $sup], 200);
    }
    public function getURL($id)
    {
        $icon = DB::table('icon_titik_rawan_bencana as a')
            ->distinct()
            ->where('a.id', $id)
            ->get();
        return response()->json(['icon' => $icon], 200);
    }
    public function editData($id)
    {
        $laporan_bencana = DB::table('laporan_bencana')->where('id', $id)
            // ->leftJoin('icon_titik_laporan_bencana_bencana', 'icon_titik_laporan_bencana_bencana.id', '=', 'master_laporan_bencana_bencana.icon_id')
            // ->select('master_laporan_bencana_bencana.*', 'icon_titik_laporan_bencana_bencana.id as icon_id')
            ->first();
        $waktu_kejadian_parse = Carbon::parse($laporan_bencana->waktu_kejadian)->format('Y-m-d\TH:i');
        // dd($laporan_bencana);
        $uptd = DB::table('landing_uptd')->get();

        $ruas = DB::table('master_ruas_jalan');
        $ruas = $ruas->where('master_ruas_jalan.uptd_id', $laporan_bencana->uptd_id);
        $ruas = $ruas->get();

        $sup = DB::table('utils_sup')
            ->where('uptd_id', $laporan_bencana->uptd_id)
            ->get();

        $icon = DB::table('icon_titik_rawan_bencana')->get();

        $icon_curr = DB::table('icon_titik_rawan_bencana as a')
            ->distinct()
            ->where('a.id', $laporan_bencana->icon_id)
            ->first();
        return view('admin.master.laporan_bencana.edit', compact('waktu_kejadian_parse','laporan_bencana', 'ruas', 'uptd', 'sup', 'icon', 'icon_curr'));
    }
    public function createData(Request $req)
    {
        $laporan_bencana = $req->except('_token', 'ruas_jalan');
        // $laporan_bencana['slug'] = Str::slug($req->nama, '');
        $laporan_bencana['uptd_id'] = $req->uptd_id == '' ? 0 : $req->uptd_id;
        $ruas_jalan = DB::table('master_ruas_jalan')->where('id_ruas_jalan', $req->ruas_jalan)->first();
        $laporan_bencana['no_ruas'] = $ruas_jalan->id_ruas_jalan;
        $laporan_bencana['ruas_jalan'] = $ruas_jalan->nama_ruas_jalan;
        if ($req->foto != null) {
            $path =  Str::snake(date("YmdHis") . ' ' . $req->foto->getClientOriginalName());
            $req->foto->storeAs('public/laporan_bencana/', $path);
            $laporan_bencana['foto'] = $path;
        }
        if ($req->video != null) {
            $path =  Str::snake(date("YmdHis") . ' ' . $req->video->getClientOriginalName());
            $req->video->storeAs('public/laporan_bencana/', $path);
            $laporan_bencana['video'] = $path;
        }
        $laporan_bencana['created_at'] = Carbon::now();
        $laporan_bencana['created_by'] = Auth::user()->id;
        $icon_image = DB::table('icon_titik_rawan_bencana')->where('id', $req->icon_id)->get();
        $laporan_bencana['icon_image'] = $icon_image[0]->icon_image;
        DB::table('laporan_bencana')->insert($laporan_bencana);

        $color = "success";
        $msg = "Berhasil Menambah Data Laporan Bencana";
        return back()->with(compact('color', 'msg'));
    }
    public function updateData(Request $req)
    {
        $laporan_bencana = $req->except('_token', 'id');
        $laporan_bencana['uptd_id'] = $req->uptd_id == '' ? 0 : $req->uptd_id;

        $ruas_jalan = DB::table('master_ruas_jalan')->where('id_ruas_jalan', $req->ruas_jalan)->first();
        $laporan_bencana['no_ruas'] = $ruas_jalan->id_ruas_jalan;
        $laporan_bencana['ruas_jalan'] = $ruas_jalan->nama_ruas_jalan;

        // dd($laporan_bencana);
        if ($req->foto != null) {
            $path = Str::snake(date("YmdHis") . ' ' . $req->foto->getClientOriginalName());
            $req->foto->storeAs('public/laporan_bencana/', $path);
            $laporan_bencana['foto'] = $path;
        }
        if ($req->video != null) {
            $path =  Str::snake(date("YmdHis") . ' ' . $req->video->getClientOriginalName());
            $req->video->storeAs('public/laporan_bencana/', $path);
            $laporan_bencana['video'] = $path;
        }

        $old = DB::table('laporan_bencana')->where('id', $req->id)->first();

        $icon_image = DB::table('icon_titik_rawan_bencana')->where('id', $req->icon_id)->get();
        $laporan_bencana['icon_image'] = $icon_image[0]->icon_image;

        $laporan_bencana['updated_at'] = Carbon::now();
        $laporan_bencana['updated_by'] = Auth::user()->id;
        DB::table('laporan_bencana')->where('id', $req->id)->update($laporan_bencana);

        $color = "success";
        $msg = "Berhasil Mengubah Data Laporan Bencana";
        return redirect(route('getDataLaporanBencana'))->with(compact('color', 'msg'));
    }
    public function deleteData($id)
    {
        $old = DB::table('laporan_bencana')->where('id', $id);
        $old->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Laporan Bencana";
        return redirect(route('getDataLaporanBencana'))->with(compact('color', 'msg'));
    }

    public function json()
    {
        $laporan_bencana = DB::table('laporan_bencana');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $laporan_bencana = $laporan_bencana->where('uptd_id', $uptd_id);
        }
        return DataTables::of($laporan_bencana)
            ->addIndexColumn()
            ->addColumn('imgbencana', function ($row) {
                $path_foto = explode('/', $row->foto);
                $img = '<img class="img-fluid" style="max-width: 100px" src="' . url('/storage/laporan_bencana/' . $row->foto) . '"  alt="' . end($path_foto) . '" />';
                return $img;
            })
            ->addColumn('videobencana', function ($row) {
                $path_video = explode('/', $row->video);
                $video = '<video style="max-width: 150px" controls class="img-thumbnail rounded mx-auto d-block" alt="'.end($path_video).'">
                <source src="' .url("storage/laporan_bencana/" . $row->video).'" type="video/mp4" />
            </video>';
                return $video;
            })
            ->addColumn('icon_image', function ($row) {
                $path_icon = explode('/', $row->icon_image);
                $icon = '<img alt="' . end($path_icon) . '" class="img-fluid" style="max-width: 100px" src="' . url('/storage/' . $row->icon_image) . '">';
                return $icon;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">';

                if (hasAccess(Auth::user()->internal_role_id, "Laporan Bencana", "Update")) {
                    $btn = $btn . '<a href="' . route('editDataLaporanBencana', $row->id) . '"><button data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-pencil"></i></button></a>';
                }

                if (hasAccess(Auth::user()->internal_role_id, "Laporan Bencana", "Delete")) {
                    $btn = $btn . '<a href="#delModal" data-id="' . $row->id . '" data-toggle="modal"><button data-toggle="tooltip" title="Hapus" class="btn btn-danger btn-sm waves-effect waves-light"><i class="icofont icofont-trash"></i></button></a>';
                }
                $btn = $btn . '</div>';

                return $btn;
            })
            ->addColumn('uptd', function ($row) {
                return 'UPTD ' . $row->uptd_id;
            })
            ->rawColumns(['action', 'imgbencana', 'videobencana', 'icon_image'])
            ->make(true);
    }
}
