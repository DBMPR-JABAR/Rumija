<?php

namespace App\Http\Controllers;

use App\Model\DWH\ProgressMingguan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GeneralResource;
use App\Model\DWH\KemantapanJalan;
use DateTime;
use Illuminate\Support\Carbon;


class MonitoringController extends Controller
{

    public function getLaporan()
    {
        return view('admin.monitoring.laporan-kerusakan');
    }
    public function getProgressPekerjaan()
    {
        return view('admin.monitoring.progress-pekerjaan');
    }
    public function getKemantapanJalan()
    {
        $luas = KemantapanJalan::sum('LUAS');
        $kondisi['SANGAT_BAIK'] = KemantapanJalan::sum('SANGAT_BAIK');
        $kondisi['BAIK'] = KemantapanJalan::sum('BAIK');
        $kondisi['SEDANG'] = KemantapanJalan::sum('SEDANG');
        $kondisi['JELEK'] = KemantapanJalan::sum('JELEK');
        $kondisi['PARAH'] = KemantapanJalan::sum('PARAH');
        $kondisi['SANGAT_PARAH'] = KemantapanJalan::sum('SANGAT_PARAH');
        $kondisi['HANCUR'] = KemantapanJalan::sum('HANCUR');

        return view('admin.monitoring.kemantapan-jalan',compact('luas','kondisi'));
    }
    public function getLaporanAPI()
    {
        $response = [
            'status' => 'false',
            'data' => []
        ];
        $laporan = DB::table('monitoring_laporan_masyarakat');
        if(Auth::user()->internalRole->uptd){
            $uptd_id = str_replace('uptd','',Auth::user()->internalRole->uptd);
            $laporan = $laporan->where('uptd_id',$uptd_id);
        }
        $laporan = $laporan->get();
        $response['data'] = $laporan;
        return response()->json($response, 200);
    }

    public function getKemantapanJalanAPI()
    {
        return (new GeneralResource(KemantapanJalan::all()));
    }

    public function getMainDashboard()
    {
        //   $category = DB::connection('dwh')->select("");

        $proyekkontrak = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN')->get();
        return view('admin.monitoring.dashboard', compact('proyekkontrak'));
    }
    public function getSupData(Request $request)
    {
        $uptd = $request['uptd'];
        $query = DB::connection('dwh')->table('TBL_UPTD_TRX_PEMBANGUNAN')->select('SUP', 'UPTD');

        for ($i = 0; $i < count($uptd); $i++) {
            $query->orWhere('UPTD', '=', $uptd[$i]);
        }
        $supData['data']['uptd'] = $uptd;
        $supData['data']['spp'] = $query->distinct()->get();
        return response()->json($supData);
    }

    public function getProyekKontrak()
    {

        $query = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN')
            ->select('NAMA_PAKET', 'TANGGAL', 'PENYEDIA_JASA', 'KEGIATAN', 'RUAS_JALAN', 'LOKASI', 'RENCANA', 'REALISASI', 'DEVIASI', 'JENIS_PEKERJAAN', 'UPTD');
        $query->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN')
            ;
        });

        $queryCritical = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN')
            ->select('NAMA_PAKET', 'TANGGAL', 'PENYEDIA_JASA', 'KEGIATAN', 'RUAS_JALAN', 'LOKASI', 'RENCANA', 'REALISASI', 'DEVIASI', 'JENIS_PEKERJAAN', 'UPTD');
        $queryCritical->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        });
        $queryOnProgress = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        $queryOnProgress->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        });
        $queryoffProgress = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        $queryoffProgress->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        });

        $queryfinish = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN')
            ->select('NAMA_PAKET', 'TANGGAL', 'PENYEDIA_JASA', 'KEGIATAN', 'RUAS_JALAN', 'LOKASI', 'RENCANA', 'REALISASI', 'DEVIASI', 'JENIS_PEKERJAAN', 'UPTD');
        $queryfinish->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        });

        //       SELECT a.kegiatan, c.tgl, b.tgl_kontrak,b.NILAI_KONTRAK, c.volume as rencana,  d.volume as realisasi, c.harga_satuan, c.satuan, c.jumlah_harga
        //FROM `TBL_TALIKUAT_TRX_JADUAL2` as a
        //LEFT JOIN TBL_TALIKUAT_TRX_DATA_UMUM as b ON a.id_data_umum = b.ID
        //LEFT JOIN TBL_TALIKUAT_TRX_DETAIL_JADUAL as c ON c.id = a.id
        //LEFT JOIN TBL_TALIKUAT_TRX_DETAIL_LAPORAN_HARIAN_PEKERJAAN as d ON d.id_kegiatan = c.id_jadual
        //GROUP BY  c.id_jadual

//$ProyekKontrakDetail = DB::connection('dwh')->table('tbl_talikuat_trx_detail_jadual')
        //                                      ->select( 'ID_JADUAL')
        //                                      ->where('TGL','<>','')->get();
        //$today = date('Y-m-d');
        $ProyekKontrakData = array();
//foreach($ProyekKontrakDetail as $detail){
        //$ProyekKontrak = DB::connection('dwh')->table('TBL_TALIKUAT_TRX_JADUAL2 as a')
        //     ->select( 'c.ID_JADUAL','a.KEGIATAN','c.TGL' , 'b.tgl_kontrak','c.VOLUME as RENCANA','d.VOLUME as REALISASI','a.KONSULTAN')
        //   ->join('TBL_TALIKUAT_TRX_DATA_UMUM as b', 'a.id_data_umum', '=', 'b.ID')
        // ->join('TBL_TALIKUAT_TRX_DETAIL_JADUAL as c', 'c.ID', '=', 'a.ID')
        //->join('TBL_TALIKUAT_TRX_DETAIL_LAPORAN_HARIAN_PEKERJAAN as d', 'd.id_kegiatan', '=', 'c.id_jadual')
        //->where('c.ID_JADUAL','=',$detail->ID_JADUAL)
        //->take(1)->get();

    $listProyekKontrak = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN as A')
            ->select(DB::raw('(SELECT MIN(TANGGAL)  FROM TBL_UPTD_TRX_PROGRESS_MINGGUAN WHERE NAMA_PAKET = A.NAMA_PAKET)  as DATE_FROM ' ),
                     DB::raw('(SELECT MAX(TANGGAL)  FROM TBL_UPTD_TRX_PROGRESS_MINGGUAN WHERE NAMA_PAKET =  A.NAMA_PAKET ) as DATE_TO ' ),
                             'A.ID', 'A.NAMA_PAKET', 'A.TANGGAL', 'A.PENYEDIA_JASA', 'A.KEGIATAN', 'A.RUAS_JALAN', 'A.LOKASI', 'A.RENCANA', 'A.REALISASI', 'A.DEVIASI', 'A.JENIS_PEKERJAAN', 'A.UPTD'
                             //   DB::raw('DATEDIFF((SELECT MIN(TANGGAL)  FROM TBL_UPTD_TRX_PROGRESS_MINGGUAN WHERE NAMA_PAKET = A.NAMA_PAKET) ,(SELECT MAX(TANGGAL)  FROM TBL_UPTD_TRX_PROGRESS_MINGGUAN WHERE NAMA_PAKET =  A.NAMA_PAKET )) as SELISIH')

                                )
            ->get();

    foreach ($listProyekKontrak as $proyek) {

      $date_from = date_create($proyek->DATE_FROM);
      $date_to = date_create($proyek->DATE_TO);

            $ProyekKontrakData[] = "
                {
                name: '" . $proyek->NAMA_PAKET . "',
                data: [{
                    name: '" . $proyek->NAMA_PAKET . "',
                    id: '" . $proyek->ID . "',
                    owner:  '" . $proyek->PENYEDIA_JASA . "'
                    },
                        {
                        name: 'Rencana  ',
                        id: 'Rencana" . $proyek->ID . "',
                        parent: '" . $proyek->ID . "',
                        start: Date.UTC(".date_format($date_from,"Y").", ".date_format($date_from,"m").", ".date_format($date_from,"d") ."),
                        end: Date.UTC(".date_format($date_to,"Y").", ".date_format($date_to,"m").", ".date_format($date_to,"d")."),
                          completed: { amount: (" . (!empty($proyek->RENCANA) ? ($proyek->RENCANA / 100) : 0) . ")  }
                    },{
                        name: 'Realisasi',
                        id: 'Realisasi" . $proyek->ID . "',

                        parent: '" . $proyek->ID . "',
                        start: Date.UTC(".date_format($date_from,"Y").", ".date_format($date_from,"m").", ".date_format($date_from,"d")."),
                        end: Date.UTC(".date_format($date_to,"Y").", ".date_format($date_to,"m").", ".date_format($date_to,"d")."),
                        completed: { amount: (" . (!empty($proyek->REALISASI) ? ($proyek->REALISASI / 100) : 0) . ") }
                    },
                    {
                         name : 'deviasi = ".$proyek->DEVIASI."',
                         parent: '" . $proyek->ID . "',

                    }
                    ]

                } ";
     }
        // echo $ProyekKontrakData;
         $queryPaket = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN')
            ->select('NAMA_PAKET', 'TANGGAL', 'PENYEDIA_JASA', 'KEGIATAN', 'RUAS_JALAN', 'LOKASI', 'RENCANA', 'REALISASI', 'DEVIASI', 'JENIS_PEKERJAAN', 'UPTD');
        $queryPaket->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        });
        $listQueryPaket = $queryPaket->get();
        // $listQueryPaket =$queryPaket->toSql();
        //dd($listQueryPaket);

        $paketData = array();
        foreach ($listQueryPaket as $paket) {
            $paketData[] = '
                 {
                  "paket": "' . $paket->NAMA_PAKET . '" ,
                  "rencana": ' . $paket->RENCANA . ' ,
                  "realisasi": ' . $paket->REALISASI . '
                }';

        }
        //   print_r(implode(',',$paketData));
        $deviasi = -5;

        $critical = $queryCritical->where('DEVIASI', '<', $deviasi);
        $onprogress = $queryOnProgress->where('DEVIASI', '>', $deviasi);
        $offProgress = $queryoffProgress->where('DEVIASI', '<', $deviasi);
        $finish = $queryfinish->where('DEVIASI', '=', '0');
        $listProjectContract = $query->get();
        $countCritical = $critical->get()->count();
        $countOnProgress = $onprogress->get()->count();
        $countOffProgress = $offProgress->get()->count();
        //echo "<script>alert('".$countOnProgress."')</script>";
        $countFinish = $finish->get()->count();
        return view('admin.monitoring.proyek-kontrak',
            ['listProjectContract' => $listProjectContract,
                'countCritical' => $countCritical,
                'countOnProgress' => $countOnProgress,
                'countOffProgress' => $countOffProgress,
                'countFinish' => $countFinish,
                'proyekKontrak' => implode(",", $ProyekKontrakData),
                'today' => date('Y-m-d'),
                'anggaranData' => ""

            ]);
    }

    public function getProyekKontrakBackup()
    {

        $query = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN')
            ->select('NAMA_PAKET', 'TANGGAL', 'PENYEDIA_JASA', 'KEGIATAN', 'RUAS_JALAN', 'LOKASI', 'RENCANA', 'REALISASI', 'DEVIASI', 'JENIS_PEKERJAAN', 'UPTD');
        $query->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN')
            ;
        });

        $queryCritical = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN')
            ->select('NAMA_PAKET', 'TANGGAL', 'PENYEDIA_JASA', 'KEGIATAN', 'RUAS_JALAN', 'LOKASI', 'RENCANA', 'REALISASI', 'DEVIASI', 'JENIS_PEKERJAAN', 'UPTD');
        $queryCritical->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        });
        $queryOnProgress = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        $queryOnProgress->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        });
        $queryoffProgress = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        $queryoffProgress->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        });

        $queryfinish = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN')
            ->select('NAMA_PAKET', 'TANGGAL', 'PENYEDIA_JASA', 'KEGIATAN', 'RUAS_JALAN', 'LOKASI', 'RENCANA', 'REALISASI', 'DEVIASI', 'JENIS_PEKERJAAN', 'UPTD');
        $queryfinish->whereIn('TANGGAL', function ($querySubTanggal) {
            $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('TBL_UPTD_TRX_PROGRESS_MINGGUAN');
        });


        $anggaranUPTD = DB::connection('dwh')->table('TBL_UPTD_TRX_PEMBANGUNAN')
        ->select('UPTD', DB::raw('SUM(PAGU_ANGGARAN) AS PAGU_ANGGARAN'), DB::raw('SUM(NILAI_KONTRAK) AS NILAI_KONTRAK'), DB::raw('SUM(TOTAL_SISA_LELANG) AS TOTAL_SISA_LELANG'))
                        ->groupBy('UPTD')->get();
        $anggaranData= array();
        foreach($anggaranUPTD as $anggaran){
            $anggaranData[] ='
                "'.$anggaran->UPTD.'": {
                  "pagu ": '.$anggaran->PAGU_ANGGARAN.',
                  "Kontrak": '.$anggaran->NILAI_KONTRAK.',
                  "Sisa": '.$anggaran->TOTAL_SISA_LELANG.'
                }';

        }
        $deviasi = -5;

        $critical = $queryCritical->where('DEVIASI', '<', $deviasi);
        $onprogress = $queryOnProgress->where('DEVIASI', '>', $deviasi);
        $offProgress = $queryoffProgress->where('DEVIASI', '<', $deviasi);
        $finish = $queryfinish->where('DEVIASI', '=', '0');
        $listProjectContract = $query->get();
        $countCritical = $critical->get()->count();
        $countOnProgress = $onprogress->get()->count();
        $countOffProgress = $offProgress->get()->count();
        //echo "<script>alert('".$countOnProgress."')</script>";
        $countFinish = $finish->get()->count();
        return view('admin.monitoring.proyek-kontrak',
            ['listProjectContract' => $listProjectContract,
                'countCritical' => $countCritical,
                'countOnProgress' => $countOnProgress,
                'countOffProgress' => $countOffProgress,
                'countFinish' => $countFinish,
                'anggaranData' => implode(",",$anggaranData)
            ]);
    }

    public function getProyekDetail($status)
    {
        $proyekdetail = ProgressMingguan::get()->filter(function ($item) use ($status) {
            return $item->STATUS_PROYEK === $status;
        });
        return view('admin.monitoring.proyek-kontrak-detail', compact('proyekdetail'));
    }

}
