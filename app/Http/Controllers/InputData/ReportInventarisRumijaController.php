<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
use App\Transactional\RumijaInventarisasi;
use App\Transactional\RumijaInventarisasiKategori;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportInventarisRumijaController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $uptd = DB::table('landing_uptd')->get()->all();
        return view('admin.input_data.rumija_inventarisasi.report.index', compact('data', 'uptd'));
    }

    public function download(Request $request)
    {
        $data = $request->all();
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('template/inventaris_jalan_jembatan.docx'));

        $ur = '-';
        switch ($request->uptd_id) {
            case 1:
                $ur = 'I';
                break;
            case 2:
                $ur = 'II';
                break;
            case 3:
                $ur = 'III';
                break;
            case 4:
                $ur = 'IV';
                break;
            case 5:
                $ur = 'V';
                break;
            case 6:
                $ur = 'VI';
                break;
            default:
                $ur = '-';
                break;
        }

        $ruas_jalan_split = "";
        $panjang_lokasi = 0;
        $ruas_jalan_id = [];
        foreach ($request->id_ruas_jalan as $id) {
            $id_split = explode('___', $id);
            if ($ruas_jalan_split !== "") $ruas_jalan_split = $ruas_jalan_split . '; ' . $id_split[1];
            else  $ruas_jalan_split = $ruas_jalan_split . $id_split[1];
            $panjang_lokasi += $id_split[2];
            array_push($ruas_jalan_id, $id_split[0]);
        }

        $uptd = DB::table('landing_uptd')->where('id', $request->uptd_id)->first();
        $sup = DB::table('utils_sup')->where('kd_sup', $request->sup_id)->first();
        $currentYear = date("Y");

        $my_template->setValue('uptd_romawi', $ur);
        $my_template->setValue('ruas_jalan_split', strtoupper($ruas_jalan_split));
        $my_template->setValue('sppjj_nama', strtoupper($sup->name));
        $my_template->setValue('uptd_lokasi', $uptd->nama);
        $my_template->setValue('lokasi_panjang', $panjang_lokasi);
        $my_template->setValue('tahun', $currentYear);
        $path = 'inventaris_jalan_jembatan/' . uniqid('report_inventarisasi_', true)  . '.docx';

        $inventaris = RumijaInventarisasi::get();

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
                            'c_no' => $GORONG_GORONG->count,
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

        $my_template->cloneRowAndSetValues('a_no', $LOKASI_JEMBATAN_DATA->data);
        $my_template->cloneRowAndSetValues('b_no', $GORONG_GORONG->data);
        $my_template->cloneRowAndSetValues('c_no', $TPT->data);
        $my_template->cloneRowAndSetValues('d_no', $DATA_POHON->data);
        $my_template->cloneRowAndSetValues('e_no', $DATA_PATOK_PENGARAH_HM_KM->data);
        $my_template->cloneRowAndSetValues('f_no', $SALURAN->data);
        $my_template->cloneRowAndSetValues('g_no', $BAHU_JALAN->data);

        try {
            $my_template->saveAs(storage_path($path));
        } catch (Exception $e) {
            $color = "warning";
            $msg = "Terjadi Kegagalan / Data Tidak Tersedia";
            return redirect(route('rumija.inventarisasi.report.index'))->with(compact('color', 'msg'));
        }

        return response()->download(storage_path($path));
    }
}
