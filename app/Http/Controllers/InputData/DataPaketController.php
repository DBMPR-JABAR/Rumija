<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
use App\Model\DWH\DataPaket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DataTables;

class DataPaketController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Data Paket', ['create','add'], ['index','json'], ['edit','update'], ['delete']);
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

        $dataPaket = DB::table('pembangunan');
        $uptd = DB::table('landing_uptd');
        $sup = DB::table('utils_sup');
        $pekerjaan = DB::table('utils_jenis_pekerjaan');
        // $dataPaket = $dataPaket->leftJoin('utils_sup', 'utils_sup.name', '=', 'pembangunan.sup')->select('pembangunan.*', 'utils_sup.name as supName');
        $sup = DB::table('utils_sup');

        if (Auth::user()->internalRole->uptd) {
            if (Auth::user()->internalRole->uptd) {
                $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
                $dataPaket = $dataPaket->where('uptd_id', $uptd_id);
                $sup = $sup->where('uptd_id', $uptd_id);
                $uptd = $uptd->where('id', $uptd_id);
            }
        }
        $dataPaket = $dataPaket->get();
        $uptd = $uptd->get();
        $sup = $sup->get();
        $pekerjaan = $pekerjaan->get();
        return view('admin.input_data.data_paket.index', compact('dataPaket', 'uptd', 'sup', 'pekerjaan'));
    }

    public function json()
    {
        $dataPaket = DB::table('pembangunan');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $dataPaket = $dataPaket->where('uptd_id', $uptd_id);
        }
        $dataPaket = $dataPaket->get();
        return Datatables::of($dataPaket)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">';

                if (hasAccess(Auth::user()->internal_role_id, "Progress Kerja", "Update")) {
                    $html .= '<a href="' . route('editIDDataPaket', $row->kode_paket) . '"><button data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-pencil"></i></button></a>';
                }
                if (hasAccess(Auth::user()->internal_role_id, "Progress Kerja", "Delete")) {
                    $html .= '<a href="#delModal" data-id="' . $row->kode_paket . '" data-toggle="modal"><button data-toggle="tooltip" title="Hapus" class="btn btn-danger btn-sm waves-effect waves-light"><i class="icofont icofont-trash"></i></button></a>';
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('updated_at_format', function ($row) {
                $formated = Carbon::parse($row->updated_at);
                return $formated;
            })
            ->make(true);
    }

    public function create(Request $req)
    {
        //
        $dataPaket = $req->except('_token', 'gambar');

        DB::table('pembangunan')->insert($dataPaket);

        $color = "success";
        $msg = "Berhasil Menambah Data Ruas Jalan";
        return redirect(route('getIDDataPaket'))->with(compact('color', 'msg'));
    }

    public function edit($kode_paket)
    {
        $dataPaket = DB::table('pembangunan')->where('kode_paket', $kode_paket)->first();
        $sup = DB::table('utils_sup');
        $pekerjaan = DB::table('utils_jenis_pekerjaan');
        $uptd = DB::table('landing_uptd');
        $ruasJalan = DB::table('master_ruas_jalan');

        if (Auth::user()->internalRole->uptd) {
            if (Auth::user()->internalRole->uptd) {
                $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            }
        }

        $sup = $sup->where('uptd_id', $dataPaket->uptd_id);
        $ruasJalan = $ruasJalan->where('uptd_id', $dataPaket->uptd_id);


        $sup = $sup->get();
        $pekerjaan = $pekerjaan->get();
        $ruasJalan = $ruasJalan->get();
        $uptd = $uptd->get();

        return view('admin.input_data.data_paket.edit', compact('dataPaket', 'pekerjaan', 'sup', 'ruasJalan', 'uptd'));
    }

    public function add()
    {
        $sup = DB::table('utils_sup');
        $pekerjaan = DB::table('utils_jenis_pekerjaan');
        $ruasJalan = DB::table('master_ruas_jalan');
        $uptd = DB::table('landing_uptd');

        if (Auth::user()->internalRole->uptd) {
            if (Auth::user()->internalRole->uptd) {
                $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
                $sup = $sup->where('uptd_id', $uptd_id);
                $ruasJalan = $ruasJalan->where('uptd_id', $uptd_id);
            }
        }
        $sup = $sup->get();
        $pekerjaan = $pekerjaan->get();
        $ruasJalan = $ruasJalan->get();
        $uptd = $uptd->get();

        return view('admin.input_data.data_paket.add', compact('pekerjaan', 'sup', 'ruasJalan', 'uptd'));
    }

    public function update(Request $req)
    {
        //
        $dataPaket = $req->except('_token', 'gambar', 'id');

        $old = DB::table('pembangunan')->where('kode_paket', $req->kode_paket)->first();

        // if ($req->gambar != null) {
        //     $old->gambar ?? Storage::delete('public/' . $old->gambar);

        //     $path = 'landing/ruas$dataPaket/' . Str::snake(date("YmdHis") . ' ' . $req->gambar->getClientOriginalName());
        //     $req->gambar->storeAs('public/', $path);
        //     $dataPaket['gambar'] = $path;
        // }

        DB::table('pembangunan')->where('kode_paket', $req->kode_paket)->update($dataPaket);

        $color = "success";
        $msg = "Berhasil Mengubah Data Ruas Jalan";
        return redirect(route('getIDDataPaket'))->with(compact('color', 'msg'));
    }

    public function delete($kode_paket)
    {
        $dataPaket = DB::table('pembangunan');
        $old = $dataPaket->where('kode_paket', $kode_paket);

        $old->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Paket";
        return redirect(route('getIDDataPaket'))->with(compact('color', 'msg'));
    }
}
