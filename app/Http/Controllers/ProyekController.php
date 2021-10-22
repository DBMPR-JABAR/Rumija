<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Model\DWH\ProgressMingguan;
use Illuminate\Support\Facades\Auth;
class ProyekController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Kendali Kontrak', [], ['getKendaliKontrak','getProyekStatus','getProyekDetail','getKendaliKontrakProgress','getProyekKontrakAPI','getTargetRealisasiAPI'],[],[]);
        foreach ($roles as $role => $permission) {
            $this->middleware($role)->only($permission);
        }
    }
    public function getKendaliKontrak()
    {
        $finishquery = DB::connection('dwh')->table('vw_uptd_trx_rekap_proyek_kontrak');
        // $finishquery->whereIn('TANGGAL', function ($querySubTanggal) {
        //     $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('vw_uptd_trx_rekap_proyek_kontrak');
        // });
        if(Auth::user()->internalRole->uptd){
            $uptd_id = str_replace('uptd','',Auth::user()->internalRole->uptd);
            $finishquery = $finishquery->where('UPTD',$uptd_id);
        }

        $criticalquery = clone $finishquery;
        $onprogressquery = clone $finishquery;

        $criticalCount = $criticalquery->whereRaw('BINARY STATUS_PROYEK = "CRITICAL CONTRACT"')->count();
        $onprogressCount = $onprogressquery->whereRaw('BINARY STATUS_PROYEK = "ON PROGRESS"')->count();
        $finishCount = $finishquery->whereRaw('BINARY STATUS_PROYEK = "FINISH"')->count();

        return view('admin.monitoring.proyek-kontrak',
            [   'countCritical' => $criticalCount,
                'countOnProgress' => $onprogressCount,
                'countFinish' => $finishCount,
                'today' => date('Y-m-d'),
                'anggaranData' => ""
            ]);
    }

    public function getProyekStatus($status)
    {
        $getProyekDetail = DB::connection('dwh')->table('vw_uptd_trx_rekap_proyek_kontrak')
                                                ->whereRaw('BINARY STATUS_PROYEK = "'.$status.'"')
                                                ->get();

        return view('admin.monitoring.proyek-kontrak-status',
            ['getProyekDetail' => $getProyekDetail]);
    }
    public function getProyekDetail($id)
    {
        $getProyekDetail = DB::connection('dwh')->table('TBL_TALIKUAT_TRX_PROYEK_KONTRAK_PROGRESS_HARIAN as a')
                                                ->select("*","a.TANGGAL as DETAIL_TANGGAL","b.ID as ID_VIEW")
                                                ->leftJoin('vw_uptd_trx_rekap_proyek_kontrak as b','a.NMP','=','b.NO_PAKET')
                                                ->distinct()
                                                ->where('b.ID',$id)
                                                ->orderBy("a.TANGGAL",'desc')
                                                ->get();
        return view('admin.monitoring.proyek-kontrak-detail',
            ['getProyekDetail' => $getProyekDetail]);
    }


    public function getKendaliKontrakProgress(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = ($request->has('tahun')) ? $request->tahun : '';
        $uptd = ($request->has('uptd')) ? $request->uptd : '';
        $kegiatan = ($request->has('kegiatan')) ? $request->kegiatan : '';

        return view('admin.monitoring.proyek-kontrak-progress',compact('bulan','tahun','uptd','kegiatan'));
    }
    public function getProyekKontrakAPI(Request $request)
    {
        $kontrak = DB::connection('dwh')->table('vw_uptd_trx_proyek_kontrak');

        if ($request->uptd != "") $kontrak = $kontrak->where('UPTD', '=', $request->uptd);
        if ($request->tahun != "") $kontrak = $kontrak->where('TAHUN', '=', $request->tahun);
        if ($request->kegiatan != "") $kontrak = $kontrak->where('NAMA_KEGIATAN', 'LIKE', "%$request->kegiatan%");

        $dataAll['BULAN'] = [];
        $dataAll['TAHUN'] = [];
        $dataAll['RENCANA'] = [];
        $dataAll['REALISASI'] = [];

        foreach ($kontrak->get() as $data) {
            array_push($dataAll['BULAN'],$data->BULAN);
            array_push($dataAll['TAHUN'],$data->TAHUN);
            array_push($dataAll['RENCANA'],$data->PROGRESS_FISIK_RENCANA_KUMULATIF);
            array_push($dataAll['REALISASI'],$data->PROGRESS_FISIK_REALISASI_KUMULATIF);
        }

        return response()->json(["data" => $dataAll], 200);
    }

    public function getProgressProyekKontrakAPI(Request $request)
    {
        $listProyekKontrak = DB::connection('dwh')->table('vw_uptd_trx_progress_proyek_kontrak')
                             ->selectRaw('*, SUM(PROGRESS_FISIK_RENCANA_KUMULATIF) AS TOTAL_RENCANA,
                                     SUM(PROGRESS_FISIK_REALISASI_KUMULATIF) AS TOTAL_REALISASI');

        if ($request->tahun != "") $listProyekKontrak = $listProyekKontrak->whereYear('TANGGAL', '=', $request->tahun);
        if ($request->uptd != "") $listProyekKontrak = $listProyekKontrak->where('UPTD', '=', $request->uptd);
        if ($request->kegiatan != "") $listProyekKontrak = $listProyekKontrak->where('NAMA_KEGIATAN', 'LIKE', "%$request->kegiatan%");
        if ($request->dateFrom != "") $listProyekKontrak = $listProyekKontrak->whereBetween('TANGGAL', [date_create_from_format("Y-m-d",$request->dateFrom),
                                                                                                        date_create_from_format("Y-m-d",$request->dateTo)]);

        $listProyekKontrak = $listProyekKontrak->groupBy('NO_PAKET')->get();

        $proyekKontrak = [];
        foreach ($listProyekKontrak as $proyek) {
            $date_from = ($request->dateFrom != '') ? Carbon::parse($request->dateFrom) : Carbon::parse($proyek->DATE_FROM);
            $date_to = ($request->dateTo != '') ? Carbon::parse($request->dateTo) : Carbon::parse($proyek->DATE_TO);
            $urlDetail = url('admin/monitoring/kendali-kontrak/detail/'.$proyek->ID);
            $ProyekKontrakData = [
                "colors" => ["#f2f4f5"],
                "name" => $proyek->NAMA_KEGIATAN,
                "data" => [
                    [
                        "id" => "".$proyek->ID,
                        "name" => $proyek->NAMA_KEGIATAN,
                        "jenis" => $proyek->JENIS_PEKERJAAN,
                        "owner" => $proyek->PENYEDIA_JASA
                    ],
                    [
                        "id" => "Rencana ".$proyek->ID,
                        "name" => "Rencana",
                        "parent" => "".$proyek->ID,
                        "start" => $date_from->getPreciseTimestamp(3),
                        "end" => $date_to->getPreciseTimestamp(3),
                        "jenis" => $proyek->JENIS_PEKERJAAN,
                        "owner" => $proyek->PENYEDIA_JASA,
                        "completed" => [
                            "fill" => "#7CB5EC",
                            "amount" => (!empty($proyek->TOTAL_RENCANA) ? (round($proyek->TOTAL_RENCANA/100,4)) : 0)
                        ]
                    ],
                    [
                        "id" => "Realisasi ".$proyek->ID,
                        "name" => "Realisasi",
                        "parent" => $proyek->ID,
                        "start" => $date_from->getPreciseTimestamp(3),
                        "end" => $date_to->getPreciseTimestamp(3),
                        "jenis" => $proyek->JENIS_PEKERJAAN,
                        "owner" => $proyek->PENYEDIA_JASA,
                        "completed" => [
                            "fill" => "#7CB5EC",
                            "amount" => (!empty($proyek->TOTAL_REALISASI) ? (round($proyek->TOTAL_REALISASI/100,4)) : 0)
                        ]
                    ],
                    [
                        "name"  => "<span style='font-size:1.2em; font-weight:bold'>Deviasi = ".$proyek->DEVIASI_PROGRESS_FISIK."</span><br>
                                    <a href='".$urlDetail."' style='font-size:1em'>Detail</a>",
                        "parent" => $proyek->ID,
                    ]
                ]
            ];
            array_push($proyekKontrak, $ProyekKontrakData);
        }
        return response()->json(["data" => $proyekKontrak], 200);
    }

    public function getTargetRealisasiAPI(Request $request)
    {
        $uptdList = DB::connection('dwh')->table('TBL_XLS_REKAP_TARGET_REALISASI_KEUANGAN');

        if ($request->uptd != "") $uptdList = $uptdList->where('UPTD', '=', $request->uptd);
        if ($request->tahun != "") $uptdList = $uptdList->where('TAHUN', '=', $request->tahun);

        $dataAll = [];


        foreach ($uptdList->get() as $data) {
            $dataArr = [$data->JANUARI, $data->FEBRUARI, $data->MARET, $data->APRIL, $data->MEI,
                        $data->JUNI, $data->JULI, $data->AGUSTUS, $data->SEPTEMBER, $data->OKTOBER,
                        $data->NOVEMBER, $data->DESEMBER];
            $dataAll[$data->JUDUL][$data->SUB] = $dataArr;
        }

        return response()->json(["data" => $dataAll], 200);
    }
}
