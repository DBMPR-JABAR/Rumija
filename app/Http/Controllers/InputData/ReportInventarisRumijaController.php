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
            // dd($id_split);
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
}
