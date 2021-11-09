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
                                'd_jenis_pohon' => $data->detail->jenis,
                                'd_jumlah' => $data->detail->jumlah,
                                'd_diameter' => $data->detail->diameter,
                                'd_keterangan' => $data->detail->keterangan,
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
                                'e_jumlah' => $data->detail->jumlah,
                                'e_keterangan' => $data->detail->keterangan,
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
                                'f_dp' => $data->detail->panjang,
                                'f_dl' => $data->detail->lebar,
                                'f_dt' => $data->detail->tinggi,
                                'f_keterangan' => $data->detail->keterangan,
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
                                'g_lebar' => $data->detail->lebar,
                                'g_panjang' => $data->detail->panjang,
                                'g_keterangan' => $data->detail->keterangan,
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

    public function getReportByRuasJalanAndCategoriesId(Request $request)
    {
        try {
            $inventaris = RumijaInventarisasi::where([
                ['id_ruas_jalan', $request->ruas_jalan_id],
            ]);

            if ($request->categories_id) {
                $inventaris = $inventaris->where(function ($query) use ($request) {
                    $query->whereIn('rumija_inventarisasi_kategori_id', $request->categories_id);
                });
            }

            $inventaris = $inventaris->get();
            $ruasJalan = DB::table('master_ruas_jalan')->where('id_ruas_jalan', $request->ruas_jalan_id)->first();

            $LOKASI_JEMBATAN_DATA = (object)[
                'count' => 0,
                'data' => [],
                'color' => '#686C38',
                'type' => 'marker',
                'title' => 'JEMBATAN',
                'icon_url' => asset('assets/images/marker/inventaris/jembatan.png')
            ];
            $GORONG_GORONG = (object)[
                'count' => 0,
                'data' => [],
                'color' => '#9B9C8C',
                'type' => 'marker',
                'title' => 'GORONG-GORONG',
                'icon_url' => asset('assets/images/marker/inventaris/gorong-gorong.png')
            ];
            $TPT = (object)[
                'count' => 0,
                'data' => [],
                'color' => '#FFFFFF',
                'type' => 'marker',
                'title' => 'TEMBOK PENAHAN TANAH',
                'icon_url' => asset('assets/images/marker/inventaris/tembok-penahan-tanah.png')
            ];
            $DATA_POHON = (object)[
                'count' => 0,
                'data' => [],
                'color' => '#00FF00',
                'type' => 'marker',
                'title' => 'POHON',
                'icon_url' => asset('assets/images/marker/inventaris/pohon.png')
            ];
            $DATA_PATOK_PENGARAH_HM_KM = (object)[
                'count' => 0,
                'data' => [],
                'color' => '#F9FF33',
                'type' => 'line',
                'title' => 'PATOK',
                'icon_url' => ''
            ];
            $SALURAN = (object)[
                'count' => 0,
                'data' => [],
                'color' => '#0000FF',
                'type' => 'line',
                'title' => 'SALURAN',
                'icon_url' => ''
            ];
            $BAHU_JALAN = (object)[
                'count' => 0,
                'data' => [],
                'color' => '#000000',
                'type' => 'line',
                'title' => 'BAHU JALAN',
                'icon_url' => ''
            ];

            foreach ($inventaris as $data) {
                switch ($data->rumija_inventarisasi_kategori_id) {
                    case 1: {
                            $LOKASI_JEMBATAN_DATA->count += 1;
                            $mapData = [
                                'no' => $LOKASI_JEMBATAN_DATA->count,
                                'nama' => $data->detail->name,
                                'lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'panjang' => $data->detail->panjang,
                                'kontruksi' => $data->detail->kontruksi,
                                'desa' => $data->detail->desa,
                                'keterangan' => $data->detail->keterangan,
                                'lat' => $data->lat,
                                'lng' => $data->lng,
                                'lat_akhir' => $data->lat_akhir,
                                'lng_akhir' => $data->lng_akhir,
                                'popup' => '<div style="max-height:80vh;overflow:auto;">
                                <p class="mb-0"><b>JEMBATAN</b></p>
                                    <table class="table">
                                    <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td>' . $data->detail->name . '</td>
                                    </tr>
                                    <tr>
                                    <td>Lokasi</td>
                                    <td>:</td>
                                    <td>' . $data->kode_lokasi . ' ' . $data->lokasi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Kontruksi</td>
                                    <td>:</td>
                                    <td>' . $data->detail->kontruksi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Desa</td>
                                    <td>:</td>
                                    <td>' . $data->detail->desa . '</td>
                                    </tr>
                                    <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>' . $data->detail->keterangan . '</td>
                                    </tr>
                                    </table>'
                            ];
                            array_push($LOKASI_JEMBATAN_DATA->data, $mapData);
                            break;
                        }
                    case 2: {
                            $GORONG_GORONG->count += 1;
                            $mapData = [
                                'no' => $GORONG_GORONG->count,
                                'lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'desa' => $data->detail->desa,
                                'keterangan' => $data->detail->keterangan,
                                'lat' => $data->lat,
                                'lng' => $data->lng,
                                'lat_akhir' => $data->lat_akhir,
                                'lng_akhir' => $data->lng_akhir,
                                'popup' => '<div style="max-height:80vh;overflow:auto;">
                                <p class="mb-0"><b>GORONG-GORONG</b></p>
                                    <table class="table">
                                    <tr>
                                    <td>Lokasi</td>
                                    <td>:</td>
                                    <td>' . $data->kode_lokasi . ' ' . $data->lokasi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Desa</td>
                                    <td>:</td>
                                    <td>' . $data->detail->desa . '</td>
                                    </tr>
                                    <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>' . $data->detail->keterangan . '</td>
                                    </tr>
                                    </table>'
                            ];
                            array_push($GORONG_GORONG->data, $mapData);
                            break;
                        }
                    case 3: {
                            $TPT->count += 1;
                            $mapData = [
                                'no' => $TPT->count,
                                'lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'ka_ki' => $data->detail->posisi,
                                'dp' => $data->detail->panjang,
                                'dl' => $data->detail->lebar,
                                'dt' => $data->detail->tinggi,
                                'keterangan' => $data->detail->keterangan,
                                'lat' => $data->lat,
                                'lng' => $data->lng,
                                'lat_akhir' => $data->lat_akhir,
                                'lng_akhir' => $data->lng_akhir,
                                'popup' => '<div style="max-height:80vh;overflow:auto;">
                                <p class="mb-0"><b>TPT</b></p>
                                    <table class="table">
                                    <tr>
                                    <td>Lokasi</td>
                                    <td>:</td>
                                    <td>' . $data->kode_lokasi . ' ' . $data->lokasi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Posisi (Kanan/Kiri)</td>
                                    <td>:</td>
                                    <td>' . $data->detail->posisi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Panjang</td>
                                    <td>:</td>
                                    <td>' . $data->detail->panjang . '</td>
                                    </tr>
                                    <tr>
                                    <td>Lebar</td>
                                    <td>:</td>
                                    <td>' . $data->detail->lebar . '</td>
                                    </tr>
                                    <tr>
                                    <td>Tinggi</td>
                                    <td>:</td>
                                    <td>' . $data->detail->tinggi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>' . $data->detail->keterangan . '</td>
                                    </tr>
                                    </table>'
                            ];
                            array_push($TPT->data, $mapData);
                            break;
                        }
                    case 4: {
                            $DATA_POHON->count += 1;
                            $mapData = [
                                'no' => $DATA_POHON->count,
                                'lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'ka_ki' => $data->detail->posisi,
                                'jenis_pohon' => $data->detail->jenis,
                                'jumlah' => $data->detail->jumlah,
                                'diameter' => $data->detail->diameter,
                                'keterangan' => $data->detail->keterangan,
                                'lat' => $data->lat,
                                'lng' => $data->lng,
                                'lat_akhir' => $data->lat_akhir,
                                'lng_akhir' => $data->lng_akhir,
                                'popup' => '<div style="max-height:80vh;overflow:auto;">
                                <p class="mb-0"><b>POHON</b></p>
                                    <table class="table">
                                    <tr>
                                    <td>Lokasi</td>
                                    <td>:</td>
                                    <td>' . $data->kode_lokasi . ' ' . $data->lokasi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Posisi (Kanan/Kiri)</td>
                                    <td>:</td>
                                    <td>' . $data->detail->posisi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Jenis</td>
                                    <td>:</td>
                                    <td>' . $data->detail->jenis . '</td>
                                    </tr>
                                    <tr>
                                    <td>Jumlah</td>
                                    <td>:</td>
                                    <td>' . $data->detail->jumlah . '</td>
                                    </tr>
                                    <tr>
                                    <td>Diameter (cm)</td>
                                    <td>:</td>
                                    <td>' . $data->detail->diameter . '</td>
                                    </tr>
                                    <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>' . $data->detail->keterangan . '</td>
                                    </tr>
                                    </table>'
                            ];
                            array_push($DATA_POHON->data, $mapData);
                            break;
                        }
                    case 5: {
                            $DATA_PATOK_PENGARAH_HM_KM->count += 1;
                            $mapData = [
                                'no' => $DATA_PATOK_PENGARAH_HM_KM->count,
                                'lokasi' => $data->kodlokasi . ' ' . $data->lokasi,
                                'ka_ki' => $data->detail->posisi,
                                'jumlah' => $data->detail->jumlah,
                                'keterangan' => $data->detail->keterangan,
                                'lat' => $data->lat,
                                'lng' => $data->lng,
                                'lat_akhir' => $data->lat_akhir,
                                'lng_akhir' => $data->lng_akhir,
                                'popup' => '<div style="max-height:80vh;overflow:auto;">
                                <p class="mb-0"><b>PATOK</b></p>
                                    <table class="table">
                                    <tr>
                                    <td>Lokasi</td>
                                    <td>:</td>
                                    <td>' . $data->kode_lokasi . ' ' . $data->lokasi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Posisi (Kanan/Kiri)</td>
                                    <td>:</td>
                                    <td>' . $data->detail->posisi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Jumlah</td>
                                    <td>:</td>
                                    <td>' . $data->detail->jumlah . '</td>
                                    </tr>
                                    <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>' . $data->detail->keterangan . '</td>
                                    </tr>
                                    </table>'
                            ];
                            array_push($DATA_PATOK_PENGARAH_HM_KM->data, $mapData);
                            break;
                        }
                    case 6: {
                            $SALURAN->count += 1;
                            $mapData = [
                                'no' => $SALURAN->count,
                                'lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'ka_ki' => $data->detail->posisi,
                                'dp' => $data->detail->panjang,
                                'dl' => $data->detail->lebar,
                                'dt' => $data->detail->tinggi,
                                'keterangan' => $data->detail->keterangan,
                                'lat' => $data->lat,
                                'lng' => $data->lng,
                                'lat_akhir' => $data->lat_akhir,
                                'lng_akhir' => $data->lng_akhir,
                                'popup' => '<div style="max-height:80vh;overflow:auto;">
                                <p class="mb-0"><b>SALURAN</b></p>
                                    <table class="table">
                                    <tr>
                                    <td>Lokasi</td>
                                    <td>:</td>
                                    <td>' . $data->kode_lokasi . ' ' . $data->lokasi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Posisi (Kanan/Kiri)</td>
                                    <td>:</td>
                                    <td>' . $data->detail->posisi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Panjang</td>
                                    <td>:</td>
                                    <td>' . $data->detail->panjang . '</td>
                                    </tr>
                                    <tr>
                                    <td>Lebar</td>
                                    <td>:</td>
                                    <td>' . $data->detail->lebar . '</td>
                                    </tr>
                                    <tr>
                                    <td>Tinggi</td>
                                    <td>:</td>
                                    <td>' . $data->detail->tinggi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>' . $data->detail->keterangan . '</td>
                                    </tr>
                                    </table>'
                            ];
                            array_push($SALURAN->data, $mapData);
                            break;
                        }
                    case 7: {
                            $BAHU_JALAN->count += 1;
                            $mapData = [
                                'no' => $BAHU_JALAN->count,
                                'lokasi' => $data->kode_lokasi . ' ' . $data->lokasi,
                                'ka_ki' => $data->detail->posisi,
                                'lebar' => $data->detail->lebar,
                                'panjang' => $data->detail->panjang,
                                'keterangan' => $data->detail->keterangan,
                                'lat' => $data->lat,
                                'lng' => $data->lng,
                                'lat_akhir' => $data->lat_akhir,
                                'lng_akhir' => $data->lng_akhir,
                                'popup' => '<div style="max-height:80vh;overflow:auto;">
                                <p class="mb-0"><b>BAHU JALAN</b><span style="color:gray">' . $data->id . '</span></p>
                                    <table class="table">
                                    <tr>
                                    <td>Lokasi</td>
                                    <td>:</td>
                                    <td>' . $data->kode_lokasi . ' ' . $data->lokasi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Posisi (Kanan/Kiri)</td>
                                    <td>:</td>
                                    <td>' . $data->detail->posisi . '</td>
                                    </tr>
                                    <tr>
                                    <td>Panjang</td>
                                    <td>:</td>
                                    <td>' . $data->detail->panjang . '</td>
                                    </tr>
                                    <tr>
                                    <td>Lebar</td>
                                    <td>:</td>
                                    <td>' . $data->detail->lebar . '</td>
                                    </tr>
                                    <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>' . $data->detail->keterangan . '</td>
                                    </tr>
                                    </table>'
                            ];
                            array_push($BAHU_JALAN->data, $mapData);
                            break;
                        }
                }
            }

            if ($request->categories_id) {
                foreach ($request->categories_id as $category) {
                    switch ($category) {
                        case 1: {
                                $this->response['data']['jembatan'] = $LOKASI_JEMBATAN_DATA;
                                break;
                            }
                        case 2: {
                                $this->response['data']['gorong_gorong'] = $GORONG_GORONG;
                                break;
                            }
                        case 3: {
                                $this->response['data']['tpt'] = $TPT;
                                break;
                            }
                        case 4: {
                                $this->response['data']['pohon'] = $DATA_POHON;
                                break;
                            }
                        case 5: {
                                $this->response['data']['patok_pengarah'] = $DATA_PATOK_PENGARAH_HM_KM;
                                break;
                            }
                        case 6: {
                                $this->response['data']['saluran'] = $SALURAN;
                                break;
                            }
                        case 7: {
                                $this->response['data']['bahu_jalan'] = $BAHU_JALAN;
                                break;
                            }
                    }
                }
            } else {
                $this->response['data']['gorong_gorong'] = $GORONG_GORONG;
                $this->response['data']['tpt'] = $TPT;
                $this->response['data']['pohon'] = $DATA_POHON;
                $this->response['data']['patok_pengarah'] = $DATA_PATOK_PENGARAH_HM_KM;
                $this->response['data']['saluran'] = $SALURAN;
                $this->response['data']['bahu_jalan'] = $BAHU_JALAN;
                $this->response['data']['jembatan'] = $LOKASI_JEMBATAN_DATA;
            }

            $this->response['status'] = (count($inventaris) == 0) ? false : true;
            $this->response['properties'] = $ruasJalan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error' . $th;
            return response()->json($this->response, 500);
        }
    }

    public function getReportPemanfaatanByRuasJalanId($ruasJalanId)
    {
        try {
            $pemanfaatan = DB::table('rumija')->where('ruas_jalan_id', $ruasJalanId)->get();
            $ruasJalan = DB::table('master_ruas_jalan')->where('id_ruas_jalan', $ruasJalanId)->first();

            $this->response['data']['data'] = $pemanfaatan;
            $this->response['data']['ruas_jalan'] = $ruasJalan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
}
