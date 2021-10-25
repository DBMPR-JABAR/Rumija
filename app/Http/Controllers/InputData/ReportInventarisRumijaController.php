<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
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
        // dd($data);
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
        foreach ($request->id_ruas_jalan as $id) {
            $id_split = explode('___', $id);
            if ($ruas_jalan_split !== "") $ruas_jalan_split = $ruas_jalan_split . '; ' . $id_split[1];
            else  $ruas_jalan_split = $ruas_jalan_split . $id_split[1];
        }

        $uptd = DB::table('landing_uptd')->where('id', $request->uptd_id)->first();
        $sup = DB::table('utils_sup')->where('kd_sup', $request->sup_id)->first();
        $currentYear = date("Y");

        $my_template->setValue('uptd_romawi', $ur);
        $my_template->setValue('ruas_jalan_split', strtoupper($ruas_jalan_split));
        $my_template->setValue('sppjj_nama', strtoupper($sup->name));
        $my_template->setValue('uptd_lokasi', $uptd->nama);
        $my_template->setValue('tahun', $currentYear);
        $path = 'inventaris_jalan_jembatan/' . uniqid('report_inventarisasi_', true)  . '.docx';
        try {
            $my_template->saveAs(storage_path($path));
        } catch (Exception $e) {
            $color = "warning";
            $msg = "Terjadi Kegagalan / Data Tidak Tersedia";
            return redirect(route('rumija.inventarisasi.report.index'))->with(compact('color', 'msg'));
        }

        return response()->download(storage_path($path));
    }

    public function getTemplateData($data)
    {
        $KODE_KM = 'kode_km';
        $LOKASI_JEMBATAN = ['a_no', 'a_nama', 'a_lokasi', 'a_panjang', 'a_kontruksi', 'a_desa', 'a_keterangan'];
        $GORONG_GORONG = ['b_no', 'b_lokasi', 'b_desa', 'b_keterangan'];
        $TPT = ['c_no', 'c_lokasi_awal', 'c_lokasi_akhir', 'c_ka_ki', 'c_dp', 'c_dl', 'c_dt', 'c_keterangan'];
        $DATA_POHON = ['d_no', 'd_lokasi_awal', 'd_lokasi_akhir', 'd_jenis_pohon', 'd_jumlah', 'd_ka_ki', 'd_diameter', 'd_keterangan'];
        $DATA_PATOK_PENGARAH_HM_KM = ['e_no', 'e_lokasi_awal', 'e_lokasi_akhir', 'e_jumlah', 'e_ka_ki', 'e_keterangan}'];
        $SALURAN = ['f_no', 'f_lokasi_awal', 'f_lokasi_akhir', 'f_ka_ki', 'f_dp', 'f_dl', 'f_dt', 'f_keterangan'];
        $BAHU_JALAN = ['g_no', 'g_lokasi_awal', 'g_lokasi_akhir', 'g_lebar', 'g_panjang', 'g_ka_ki', 'g_keterangan'];
    }
}
