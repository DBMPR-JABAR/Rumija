<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Transactional\RumijaInventarisasi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportInventarisRumijaController extends Controller
{

    private $response;
    public function __construct()
    {
        $this->response = [
            'status' => 'false',
            'data' => []
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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

    public function getReportByRuasJalanId($ruasJalanId)
    {
        try {
            $inventaris = RumijaInventarisasi::where('id_ruas_jalan', $ruasJalanId)->get();
            $ruasJalan = DB::table('master_ruas_jalan')->where('id_ruas_jalan', $ruasJalanId)->first();

            $LOKASI_JEMBATAN_DATA = (object)['count' => 0, 'data' => []];
            $GORONG_GORONG = (object)['count' => 0, 'data' => []];
            $TPT = (object)['count' => 0, 'data' => []];
            $DATA_POHON = (object)['count' => 0, 'data' => []];
            $DATA_PATOK_PENGARAH_HM_KM = (object)['count' => 0, 'data' => []];
            $SALURAN = (object)['count' => 0, 'data' => []];
            $BAHU_JALAN = (object)['count' => 0, 'data' => []];

            foreach ($inventaris as $data) {
                switch ($data->rumija_inventarisasi_kategori_id) {
                    case 1: {
                            $LOKASI_JEMBATAN_DATA->count += 1;
                            $mapData = [
                                'a_no' => $LOKASI_JEMBATAN_DATA->count,
                                'a_nama' => $data->detail->name,
                                'a_lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'a_panjang' => $data->detail->panjang,
                                'a_kontruksi' => $data->detail->kontruksi,
                                'a_desa' => $data->detail->desa,
                                'a_keterangan' => $data->detail->keterangan
                            ];
                            array_push($LOKASI_JEMBATAN_DATA->data, $mapData);
                            break;
                        }
                    case 2: {
                            $GORONG_GORONG->count += 1;
                            $mapData = [
                                'b_no' => $GORONG_GORONG->count,
                                'b_lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'b_desa' => $data->detail->desa,
                                'b_keterangan' => $data->detail->keterangan
                            ];
                            array_push($GORONG_GORONG->data, $mapData);
                            break;
                        }
                    case 3: {
                            $TPT->count += 1;
                            $mapData = [
                                'c_no' => $TPT->count,
                                'c_lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'c_ka_ki' => $data->detail->posisi,
                                'c_dp' => $data->detail->panjang,
                                'c_dl' => $data->detail->lebar,
                                'c_dt' => $data->detail->tinggi,
                                'c_keterangan' => $data->detail->keterangan
                            ];
                            array_push($TPT->data, $mapData);
                            break;
                        }
                    case 4: {
                            $DATA_POHON->count += 1;
                            $mapData = [
                                'd_no' => $DATA_POHON->count,
                                'd_lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'd_ka_ki' => $data->detail->posisi,
                                'd_keterangan' => $data->detail->keterangan,
                                'd_jenis_pohon' => $data->detail->jenis,
                                'd_jumlah' => $data->detail->jumlah,
                                'd_diameter' => $data->detail->diameter,
                            ];
                            array_push($DATA_POHON->data, $mapData);
                            break;
                        }
                    case 5: {
                            $DATA_PATOK_PENGARAH_HM_KM->count += 1;
                            $mapData = [
                                'e_no' => $DATA_PATOK_PENGARAH_HM_KM->count,
                                'e_lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'e_ka_ki' => $data->detail->posisi,
                                'e_keterangan' => $data->detail->keterangan,
                                'e_jumlah' => $data->detail->jumlah
                            ];
                            array_push($DATA_PATOK_PENGARAH_HM_KM->data, $mapData);
                            break;
                        }
                    case 6: {
                            $SALURAN->count += 1;
                            $mapData = [
                                'f_no' => $SALURAN->count,
                                'f_lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'f_ka_ki' => $data->detail->posisi,
                                'f_keterangan' => $data->detail->keterangan,
                                'f_dp' => $data->detail->panjang,
                                'f_dl' => $data->detail->lebar,
                                'f_dt' => $data->detail->tinggi,
                            ];
                            array_push($SALURAN->data, $mapData);
                            break;
                        }
                    case 7: {
                            $BAHU_JALAN->count += 1;
                            $mapData = [
                                'g_no' => $BAHU_JALAN->count,
                                'g_lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'g_ka_ki' => $data->detail->posisi,
                                'g_keterangan' => $data->detail->keterangan,
                                'g_lebar' => $data->detail->lebar,
                                'g_panjang' => $data->detail->panjang,
                            ];
                            array_push($BAHU_JALAN->data, $mapData);
                            break;
                        }
                }
            }
            $this->response['data']['jembatan'] = $LOKASI_JEMBATAN_DATA->data;
            $this->response['data']['gorong_gorong'] = $GORONG_GORONG->data;
            $this->response['data']['tpt'] = $TPT->data;
            $this->response['data']['pohon'] = $DATA_POHON->data;
            $this->response['data']['patok_pengarah'] = $DATA_PATOK_PENGARAH_HM_KM->data;
            $this->response['data']['saluran'] = $SALURAN->data;
            $this->response['data']['bahu_jalan'] = $BAHU_JALAN->data;
            $this->response['status'] = (count($inventaris) == 0) ? false : true;
            $this->response['properties'] = $ruasJalan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
}
