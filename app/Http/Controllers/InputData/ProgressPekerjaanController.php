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

class ProgressPekerjaanController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Progress Kerja', ['createDataProgress'], ['index','json','getDataProgress'], ['editDataProgress','updateDataProgress'], ['deleteDataProgress']);
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
        $pekerjaan = new ProgressPekerjaan();
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $laporan = $pekerjaan->where('UPTD', $uptd_id);
        }
        $pekerjaan = $pekerjaan->get();
        return view('admin.input.progresskerja.index', compact('pekerjaan'));
    }


    public function json()
    {
        $progress = DB::table('progress_mingguan');
        if (Auth::user()->internalRole->uptd) {
            $progress = $progress->where('uptd_id', Auth::user()->internalRole->uptd);
        }
        $progress = $progress->get();
        return Datatables::of($progress)
            ->addIndexColumn()
            ->addColumn('rencana_temp', function ($row) {
                return $row->rencana . '<br>' . $row->realisasi . '<br>' . $row->deviasi;
            })
            ->addColumn('waktu_temp', function ($row) {
                return $row->waktu_kontrak . '<br>' . $row->terpakai . '<br>' . $row->sisa . '<br>' . $row->prosentase;
            })
            ->addColumn('nilai_kontrak', function ($row) {
                return number_format($row->nilai_kontrak, 2);
            })
            ->addColumn('keuangan', function ($row) {
                return $row->bayar . '<br>' . number_format($row->bayar, 2);
            })
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">';

                if (hasAccess(Auth::user()->internal_role_id, "Progress Kerja", "Update")) {
                    $html .= '<a href="' . route('editDataProgress', $row->id) . '"><button data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-pencil"></i></button></a>';
                }
                if (hasAccess(Auth::user()->internal_role_id, "Progress Kerja", "Delete")) {
                    $html .= '<a href="#delModal" data-id="' . $row->id . '" data-toggle="modal"><button data-toggle="tooltip" title="Hapus" class="btn btn-danger btn-sm waves-effect waves-light"><i class="icofont icofont-trash"></i></button></a>';
                }
                $html .= '</div>';
                return $html;
            })
            ->make(true);
    }


    public function getDataProgress()
    {
        $pekerjaan = DB::table('progress_mingguan');
        // $pekerjaan = $pekerjaan->leftJoin('master_ruas_jalan', 'master_ruas_jalan.id', '=', 'progress_mingguan.ruas_jalan')->select('progress_mingguan.*', 'master_ruas_jalan.nama_ruas_jalan');
        // print_r(Auth::user()->internalRole->uptd);
        if (Auth::user()->internalRole->uptd) {
            $pekerjaan = $pekerjaan->where('uptd_id', Auth::user()->internalRole->uptd);
        }
        $pekerjaan = $pekerjaan->get();

        $ruas_jalan = DB::table('master_ruas_jalan');
        if (Auth::user()->internalRole->uptd) {
            $ruas_jalan = $ruas_jalan->where('uptd_id', Auth::user()->internalRole->uptd);
        }
        $ruas_jalan = $ruas_jalan->get();

        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $sup = $sup->where('uptd_id', Auth::user()->internalRole->uptd);
        }
        $sup = $sup->get();

        $paket = array();
        $penyedia = array();
        $data2 = DB::table('pembangunan');
        if (Auth::user()->internalRole->uptd) {
            $data2 = $data2->where('uptd_id', Auth::user()->internalRole->uptd);
        }
        $data2 = $data2->get();
        foreach ($data2 as $val) {
            if ($val->nama_paket != '') {
                array_push($paket, $val->nama_paket);
            }
            if ($val->penyedia_jasa != '') {
                array_push($penyedia, $val->penyedia_jasa);
            }
        }

        $jenis = DB::table('utils_jenis_pekerjaan');
        $jenis = $jenis->get();
        $uptd = DB::table('landing_uptd')->get();
        return view('admin.input.progresskerja.index', compact('jenis', 'pekerjaan', 'ruas_jalan', 'sup', 'uptd', 'penyedia', 'paket'));
    }

    public function editDataProgress($id)
    {
        $progressKerja = DB::table('progress_mingguan')->where(array('id' => $id));
        if (Auth::user()->internalRole->uptd) {
            $progressKerja = $progressKerja->where('uptd_id', Auth::user()->internalRole->uptd);
        }
        $progressKerja = $progressKerja->first();

        //dd($progress->id);

        $ruas_jalan = DB::table('master_ruas_jalan');
        if (Auth::user()->internalRole->uptd) {
            $ruas_jalan = $ruas_jalan->where('uptd_id', Auth::user()->internalRole->uptd);
        }
        $ruas_jalan = $ruas_jalan->get();

        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $sup = $sup->where('uptd_id', Auth::user()->internalRole->uptd);
        }
        $sup = $sup->get();

        $paket = array();
        $penyedia = array();
        $data2 = DB::table('pembangunan');
        if (Auth::user()->internalRole->uptd) {
            $data2 = $data2->where('uptd_id', Auth::user()->internalRole->uptd);
        }
        $data2 = $data2->get();
        foreach ($data2 as $val) {
            if ($val->nama_paket != '') {
                array_push($paket, $val->nama_paket);
            }
            if ($val->penyedia_jasa != '') {
                array_push($penyedia, $val->penyedia_jasa);
            }
        }

        $jenis = DB::table('utils_jenis_pekerjaan');
        $jenis = $jenis->get();

        $uptd = DB::table('landing_uptd')->get();
        return view('admin.input.progresskerja.edit', compact('progressKerja','jenis', 'ruas_jalan', 'sup', 'uptd', 'penyedia', 'paket'));
    }

    public function createDataProgress(Request $req)
    {
        $progress = $req->except(['_token']);
        unset($req->_token);
        $progress['deviasi'] = 0;
        $progress['bayar1'] = 0;
        $progress['sisa'] = 0;
        $progress['prosentase'] = 0;
        $progress['kategori'] = null;
        $progress['status'] = null;
        // $progress['slug'] = Str::slug($req->nama, '');
        if ($req->foto != null) {
            $path = Str::snake(date("YmdHis") . ' ' . $req->foto->getClientOriginalName());
            $req->foto->storeAs('public/progresskerja/', $path);
            $progress['foto'] = $path;
        }
        if ($req->video != null) {
            $path = Str::snake(date("YmdHis") . ' ' . $req->video->getClientOriginalName());
            $req->video->storeAs('public/progresskerja/', $path);
            $progress['video'] = $path;
        }

        DB::table('progress_mingguan')->insert($progress);

        $color = "success";
        $msg = "Berhasil Menambah Data Progress Pekerjaan";
        return back()->with(compact('color', 'msg'));
    }
    public function updateDataProgress(Request $req)
    {
        $progress = $req->except('_token', 'id');
        $old = DB::table('progress_mingguan')->where('id', $req->id)->first();
        if ($req->foto != null) {
            $old->foto ?? Storage::delete('public/progresskerja/' . $old->foto);

            $path = Str::snake(date("YmdHis") . ' ' . $req->foto->getClientOriginalName());
            $req->foto->storeAs('public/progresskerja/', $path);
            $progress['foto'] = $path;
        }

        if ($req->video != null) {
            $old->video ?? Storage::delete('public/progresskerja/' . $old->video);

            $path = Str::snake(date("YmdHis") . ' ' . $req->video->getClientOriginalName());
            $req->video->storeAs('public/progresskerja/', $path);
            $progress['video'] = $path;
        }

        DB::table('progress_mingguan')->where('id', $req->id)->update($progress);

        $color = "success";
        $msg = "Berhasil Mengubah Data Progress Pekerjaan";
        return redirect(route('getDataProgress'))->with(compact('color', 'msg'));
    }
    public function deleteDataProgress($id)
    {
        $old = DB::table('progress_mingguan')->where('id', $id)->first();
        $old->foto ?? Storage::delete('public/progresskerja/' . $old->foto);
        $old->video ?? Storage::delete('public/progresskerja/' . $old->video);

        $temp = DB::table('progress_mingguan')->where('id', $id);
        $temp->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Pekerjaan";
        return redirect(route('getDataProgress'))->with(compact('color', 'msg'));
    }
}
