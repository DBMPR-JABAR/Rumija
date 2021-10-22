<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailProyekResource;
use App\Http\Resources\ProyekResource;
use App\Model\DWH\Pembangunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $response;
    public function __construct() {
        $this->response = [
            'status' => 'false',
            'data' => []
        ];
    }
    public function index(Request $request)
    {
        try{
            $proyekKontrak = DB::connection('dwh')->table('TBL_UPTD_TRX_PROGRESS_MINGGUAN as A')
            ->select(DB::raw('(SELECT MIN(TANGGAL)  FROM TBL_UPTD_TRX_PROGRESS_MINGGUAN WHERE NAMA_PAKET = A.NAMA_PAKET)  as DATE_FROM'),
                     DB::raw('(SELECT MAX(TANGGAL)  FROM TBL_UPTD_TRX_PROGRESS_MINGGUAN WHERE NAMA_PAKET =  A.NAMA_PAKET ) as DATE_TO'),
                             'A.ID', 'A.NAMA_PAKET', 'A.TANGGAL', 'A.PENYEDIA_JASA', 'A.KEGIATAN', 'A.RUAS_JALAN', 'A.LOKASI', 'A.RENCANA', 'A.REALISASI', 'A.DEVIASI', 'A.JENIS_PEKERJAAN', 'A.UPTD');
            if($request->has('q')){
                $proyekKontrak = $proyekKontrak->where('NAMA_PAKET','LIKE',"%$request->q%");
            }

            if($request->has("skip")){
                $proyekKontrak = $proyekKontrak->skip($request->skip)->take($request->take);
            }

            $proyekKontrak = $proyekKontrak->orderBy('TANGGAL','DESC')->get();
            return (ProyekResource::collection($proyekKontrak)->additional(['status' => 'success']));

        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }

    }

    public function count()
    {
        try{
            $finishquery = DB::connection('dwh')->table('vw_uptd_trx_rekap_proyek_kontrak')
            ->select('NAMA_PAKET', 'TANGGAL', 'PENYEDIA_JASA', 'KEGIATAN', 'RUAS_JALAN', 'LOKASI', 'RENCANA', 'REALISASI', 'DEVIASI', 'JENIS_PEKERJAAN', 'UPTD','STATUS_PROYEK');
            // $finishquery->whereIn('TANGGAL', function ($querySubTanggal) {
            //     $querySubTanggal->select(DB::raw('MAX(TANGGAL)'))->from('vw_uptd_trx_rekap_proyek_kontrak');
            // });

            $criticalquery = clone $finishquery;
            $onprogressquery = clone $finishquery;

            $criticalCount = $criticalquery->whereRaw('BINARY STATUS_PROYEK = "CRITICAL CONTRACT"')->count();
            $onprogressCount = $onprogressquery->whereRaw('BINARY STATUS_PROYEK = "ON PROGRESS"')->count();
            $finishCount = $finishquery->whereRaw('BINARY STATUS_PROYEK = "FINISH"')->count();

            $this->response['data'] = [
                "onProgress" => $onprogressCount,
                "criticalContract" => $criticalCount,
                "finish" => $finishCount
            ];
            $this->response['status'] = "success";
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getByStatus(Request $request, $status)
    {
        try {
            if($request->has("skip")){
                $data = DB::connection('dwh')->table('vw_uptd_trx_rekap_proyek_kontrak')
                        ->whereRaw("BINARY STATUS_PROYEK = '$status'")->skip($request->skip)->take($request->take)->get();
            }else{
                $data = DB::connection('dwh')->table('vw_uptd_trx_rekap_proyek_kontrak')
                        ->whereRaw("BINARY STATUS_PROYEK = '$status'")->get();
            }
            return (DetailProyekResource::collection($data)->additional(['status' => 'success']));
        }catch(\Exception $e){
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return (new ProyekResource(Pembangunan::findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
