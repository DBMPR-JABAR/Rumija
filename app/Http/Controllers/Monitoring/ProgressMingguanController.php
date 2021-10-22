<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressMingguanController extends Controller
{
    public function getProggressMingguan(Request $request)
    {
        // dd($request);
        $jadwal = DB::connection('talikuat')->table('jadual');
        $progress_harian = $jadwal->rightJoin('detail_jadual', 'detail_jadual.id_jadual', '=', 'jadual.id')
            ->select('lama_waktu', 'kegiatan', 'nama_penyedia', 'tgl', 'nilai', 'detail_jadual.volume as volume', 'detail_jadual.uraian', 'detail_jadual.satuan');
        if ($request->has('tahun') == false) $request->tahun = 2020;
        $progress_harian = $progress_harian->where('detail_jadual.tgl', 'like', '%' . $request->tahun . '%')->get();
        $jadwal = DB::connection('talikuat')->table('jadual')->get();
        // dd($jadwal);
        $tahun = $request->tahun;
        return view('admin.monitoring.progress_pekerjaan.mingguan.index', compact('jadwal', 'progress_harian', 'tahun'));
    }

    public function getProggressBulanan(Request $request)
    {
        // dd($request);
        $jadwal = DB::connection('talikuat')->table('jadual');
        $progress_harian = $jadwal->rightJoin('detail_jadual', 'detail_jadual.id_jadual', '=', 'jadual.id')
            ->select('lama_waktu', 'kegiatan', 'nama_penyedia', 'tgl', 'nilai', 'detail_jadual.volume as volume', 'detail_jadual.uraian', 'detail_jadual.satuan');
        if ($request->has('tahun') == false) $request->tahun = 2020;
        $progress_harian = $progress_harian->where('detail_jadual.tgl', 'like', '%' . $request->tahun . '%')->get();
        $jadwal = DB::connection('talikuat')->table('jadual')->get();
        // dd($jadwal);
        $tahun = $request->tahun;
        return view('admin.monitoring.progress_pekerjaan.bulanan.index', compact('jadwal', 'progress_harian', 'tahun'));
    }
}
