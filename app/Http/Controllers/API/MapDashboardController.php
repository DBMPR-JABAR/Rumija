<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\DWH\Pembangunan;
use App\Model\DWH\RuasJalan;
use App\Model\DWH\Kemandoran;
use App\Model\DWH\ProgressMingguan;
// use App\Model\DWH\Jembatan;
use App\Model\Transactional\Jembatan;
use App\Model\DWH\VehicleCounting;
use App\Http\Resources\GeneralResource;
use App\Http\Resources\MapJembatanResource;
use App\Model\DWH\KemantapanJalan;
use App\Model\Transactional\LaporanMasyarakat;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PDO;

class MapDashboardController extends Controller
{
    private $response;
    public function __construct()
    {
        $this->response = [
            'status' => 'false',
            'data' => []
        ];
    }

    public function getSUP(Request $request)
    {
        try {
            $getId = fn ($uptd) => substr($uptd, -1);
            $uptdIdArr = array_map($getId, $request->uptd);

            $sup = DB::table('utils_sup')->selectRaw('name AS SUP, uptd_id AS UPTD')
                ->whereIn('uptd_id', $uptdIdArr)
                ->get();

            $this->response['status'] = 'success';

            $uptd = $request['uptd'];
            $this->response['data']['uptd'] =  $uptd;
            $this->response['data']['spp'] =  $sup;

            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getJembatan(Request $request)
    {
        try {
            // $jembatan = (new MapJembatanResource());
            $jembatan = MapJembatanResource::collection(Jembatan::whereIn('UPTD', ['uptd3'])->get());
            // $jembatan = Jembatan::whereIn('UPTD',['uptd1'])->get();
            $this->response['status'] = 'success';
            $this->response['data']['jembatan'] = $jembatan;

            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getData(Request $request)
    {
        try {
            if ($request->kegiatan != "" || $request->sup != "") $this->response['status'] = 'success';
            $this->response['data']['jembatan'] = [];
            // $this->response['data']['ruasjalan'] = [];
            $this->response['data']['pembangunan'] = [];
            $this->response['data']['peningkatan'] = [];
            $this->response['data']['pemeliharaan'] = [];
            $this->response['data']['rehabilitasi'] = [];
            $this->response['data']['vehiclecounting'] = [];
            $this->response['data']['kemantapanjalan'] = [];

            if ($request->has('kegiatan')) {
                if (in_array('jembatan', $request->kegiatan)) {
                    // $data = Jembatan::whereIn('SUP',$request->sup)->get();
                    $data = MapJembatanResource::collection(Jembatan::whereIn('SUP', $request->sup)->get());

                    $this->response['data']['jembatan'] = $data;
                }
                if (in_array('pemeliharaan', $request->kegiatan)) {
                    $data = Kemandoran::whereIn('SUP', $request->sup);

                    $data = $data->whereBetween('TANGGAL', [$request->date_from, $request->date_to]);

                    $data = $data->get();
                    $this->response['data']['pemeliharaan'] = $data;
                }
                if (in_array('vehiclecounting', $request->kegiatan)) {
                    $data = VehicleCounting::whereIn('SUP', $request->sup)->get();
                    $this->response['data']['vehiclecounting'] = $data;
                }
                if (in_array('rawanbencana', $request->kegiatan)) {
                    $data = DB::connection('dwh')->table('TBL_TMNJABAR_TRX_MASTER_RAWAN_BENCANA')
                        ->whereIn('SUP', $request->sup)->whereNotNull(['LAT', 'LONG'])->get();
                    $icon = DB::connection('dwh')->table('TBL_TMNJABAR_TRX_MASTER_RAWAN_BENCANA')->select('ICON_NAME', 'ICON_IMAGE')
                        ->whereIn('SUP', $request->sup)->whereNotNull(['LAT', 'LONG'])->whereNotNull('ICON_IMAGE')
                        ->groupBy('ICON_IMAGE')->get();
                    $this->response['data']['rawanbencana'] = $data;
                    $this->response['data']['iconrawanbencana'] = $icon;
                }
                if (in_array('laporanbencana', $request->kegiatan)) {
                    $data = DB::connection('dwh')->table('TBL_LAPORAN_BENCANA')
                        ->whereIn('SUP', $request->sup)->whereNotNull(['LAT', 'LONG'])->get();
                    $icon = DB::connection('dwh')->table('TBL_LAPORAN_BENCANA')->select('KETERANGAN', 'ICON_IMAGE')
                        ->whereIn('SUP', $request->sup)->whereNotNull(['LAT', 'LONG'])->whereNotNull('ICON_IMAGE')
                        ->groupBy('ICON_IMAGE')->get();
                    $this->response['data']['laporanbencana'] = $data;
                    $this->response['data']['iconlaporanbencana'] = $icon;
                }
                if (in_array('cctv', $request->kegiatan)) {
                    $data = DB::connection('dwh')->table('TBL_TMNJABAR_TRX_CCTV')
                        ->whereIn('SUP', $request->sup)->get();
                    $this->response['data']['cctv'] = $data;
                }
                if (in_array('laporanmasyarakat', $request->kegiatan)) {
                    $data = DB::table('monitoring_laporan_masyarakat')
                        ->whereIn('uptd_id', $request->uptd)->get();
                    $this->response['data']['laporanmasyarakat'] = $data;
                }
            }

            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = $th->getMessage();
            // $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getDataProyek(Request $request)
    {
        try {
            $sup = RuasJalan::select('SUP')->whereIn('UPTD', $request->uptd)->distinct()->get();
            $this->response['status'] = 'success';
            $this->response['data']['jembatan'] = [];
            $this->response['data']['ruasjalan'] = [];
            $this->response['data']['pembangunan'] = [];
            $this->response['data']['peningkatan'] = [];
            $this->response['data']['pemeliharaan'] = [];
            $this->response['data']['rehabilitasi'] = [];
            $this->response['data']['progressmingguan'] = [];

            $this->response['data']['sup'] = $sup;
            if ($request->has('kegiatan')) {
                if (in_array('jembatan', $request->kegiatan)) {
                    $data = Jembatan::whereIn('UPTD', $request->uptd)->get();
                    $this->response['data']['jembatan'] = $data;
                }
                if (in_array('pembangunan', $request->kegiatan)) {
                    $data = Pembangunan::whereIn('UPTD', $request->uptd)->where('KATEGORI', 'LIKE', 'pb%')->get();
                    $this->response['data']['pembangunan'] = $data;
                }
                if (in_array('peningkatan', $request->kegiatan)) {
                    $data = Pembangunan::whereIn('UPTD', $request->uptd)->where('KATEGORI', 'LIKE', 'pn%')->get();
                    $this->response['data']['peningkatan'] = $data;
                }
                if (in_array('rehabilitasi', $request->kegiatan)) {
                    $data = Pembangunan::whereIn('UPTD', $request->uptd)->where('KATEGORI', 'LIKE', 'rb%')->get();
                    $this->response['data']['rehabilitasi'] = $data;
                }
                if (in_array('pemeliharaan', $request->kegiatan)) {
                    $data = Kemandoran::whereIn('UPTD', $request->uptd)->get();
                    $this->response['data']['rehabilitasi'] = $data;
                }
                if (in_array('ruasjalan', $request->kegiatan)) {
                    $data = RuasJalan::whereIn('UPTD', $request->uptd)->get();
                    $this->response['data']['ruasjalan'] = $data;
                }
            }
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function filter(Request $request)
    {
        try {
            $jembatan = (new MapJembatanResource(Jembatan::whereIn('UPTD', 'uptd1')->get()));

            $this->response['status'] = 'success';
            $this->response['data']['jembatan'] = $jembatan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function showLaporan()
    {
        return (new GeneralResource(LaporanMasyarakat::all()));
    }

    public function showKemantapanJalan()
    {
        return (new GeneralResource(KemantapanJalan::all()));
    }

    public function showPerbaikan(Request $request)
    {
        try {
            $lat = $request->lat;
            $long = $request->long;
            $notId = $request->exclude;

            // 100m Radius
            $qDistance = "(SQRT(POW(LNG - ($long), 2) + pow(LAT - ($lat), 2)) * 1.1 * 200 * 1000)";
            $data = DB::connection('dwh')->table('TBL_UPTD_TRX_PEMBANGUNAN')
                ->select("KODE_PAKET AS ID", "TGL_KONTRAK", "WAKTU_PELAKSANAAN_HK", "LOKASI_PEKERJAAN AS RUAS_JALAN", "KEGIATAN", "LAT", "LNG", DB::raw("$qDistance AS DISTANCE"))
                ->whereRaw("$qDistance <= 100");

            if ($notId) $data = $data->whereNotIn("ID", $notId);

            $data = $data->orderBy("DISTANCE");

            $firstData = $data->first();
            if ($firstData) {
                $date = Carbon::createFromFormat('Y-m-d', $firstData->TGL_KONTRAK);
                $daysToAdd = (float)$firstData->WAKTU_PELAKSANAAN_HK;
                $dateExpired = $date->addDays($daysToAdd);
                $firstData = Arr::except((array)$firstData, ['TGL_KONTRAK', 'WAKTU_PELAKSANAAN_HK']);
                if ($dateExpired < Carbon::now()) $firstData = null;
            }
            $this->response['status'] = "success";
            $this->response['data'] = $firstData;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error' . $th;
            return response()->json($this->response, 500);
        }
    }

    public function getPemeliharaan(Request $request)
    {
        try {
            if ($request->ruas_jalan) $this->response['status'] = 'success';

            $date_from = Carbon::now()->subMonth()->format("Y-m-d H:i:s");
            $date_to = Carbon::now()->format("Y-m-d H:i:s");

            if ($request->date_from && $request->date_to) {
                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }

            $data = DB::table('kemandoran')->where('ruas_jalan', "LIKE", "%" . $request->ruas_jalan . "%");

            $data = $data->whereBetween('TANGGAL', [$date_from, $date_to]);

            $data = $data->get();
            $this->response['data']['pemeliharaan'] = $data;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getPembangunan(Request $request)
    {
        try {
            if ($request->ruas_jalan) $this->response['status'] = 'success';

            $date_from = Carbon::now()->subYear()->format("Y-m-d H:i:s");
            $date_to = Carbon::now()->format("Y-m-d H:i:s");

            if ($request->date_from && $request->date_to) {
                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }

            $data = DB::connection('talikuat')
                ->table('pembangunan_rencana')
                ->where('lokasi_pekerjaan', "LIKE", $request->ruas_jalan . " - " . $request->id_ruas . "%");

            $data = $data->whereBetween('tgl_kontrak', [$date_from, $date_to]);

            $data = $data->get();
            $this->response['data']['pembangunan'] = $data;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getRumija(Request $request)
    {
        try {
            if ($request->ruas_jalan) $this->response['status'] = 'success';

            // $date_from = Carbon::now()->subMonth()->format("Y-m-d H:i:s");
            // $date_to = Carbon::now()->format("Y-m-d H:i:s");

            // if($request->date_from && $request->date_to){
            //     $date_from = $request->date_from;
            //     $date_to = $request->date_to;
            // }

            $data = DB::table('rumija')->where('ruas_jalan', "LIKE", "%" . $request->ruas_jalan . "%");

            // $data = $data->whereBetween('TANGGAL', [$date_from, $date_to]);

            $data = $data->get();
            $this->response['data']['rumija'] = $data;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getBankeu(Request $request)
    {
        try {
            if ($request->geo_id) $this->response['status'] = 'success';

            $date_from = Carbon::now()->subMonth()->format("Y-m-d H:i:s");
            $date_to = Carbon::now()->format("Y-m-d H:i:s");

            if ($request->date_from && $request->date_to) {
                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }
            $data = DB::table('bankeu');
            if ($request->geo_id !== '-1')
                $data = $data->where('geo_id', $request->geo_id);
            else
                $data = $data->where('ruas_jalan_custom_id', $request->ruas_jalan_custom_id);

            $data = $data->whereBetween('tanggal_spmk', [$date_from, $date_to]);

            $data = $data->get();
            $this->response['data']['bankeu'] = $data;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
}
