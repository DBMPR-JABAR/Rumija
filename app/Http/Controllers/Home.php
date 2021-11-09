<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Transactional\RumijaInventarisasiKategori;
use App\Transactional\RumijaInventarisasi;
class Home extends Controller
{
    public function index()
    {
        $inventarisRumijaCategory = RumijaInventarisasiKategori::all();
        $inventarisRumijaCount = RumijaInventarisasi::all()->count();
        // dd($inventarisRumijaCategory[0]->list_inventaris->count());
        return view('admin.home', compact('inventarisRumijaCategory', 'inventarisRumijaCount'));
    }

    public function downloadFile()
    {
        $path = storage_path('app/public/Manual Book Teman Jabar DBMPR.pdf');
        return response()->download($path);
        // return response()->download($myFile, $newName, $headers);
    }

    public function GetDataUmum($id)
    {
        $tes = array();
        $jadual = DB::connection('talikuat')->table('jadual')->select('id', 'nmp')->where('id_data_umum', $id)->get();
        $data = DB::connection('talikuat')->table('data_umum')->where('id', $id)->get();
        foreach ($jadual as $e) {
            $dataJadual = DB::connection('talikuat')->table('detail_jadual')->where('id_jadual', $e->id)->orderBy('tgl', 'asc')->get();
            foreach ($dataJadual as $q) {
                $str = $q->nilai = str_replace(',', '.', $q->nilai);
                $q->nilai = floatval($str);
            }
            array_push($tes, $dataJadual);
        }
        $laporan = DB::connection('talikuat')->table('master_laporan_harian')->where([
            ['ditolak', 4],
            ['id_data_umum', $id],
            ['reason_delete', null]
        ])->get();
        return (object)[
            "curva" => $tes,
            "data_umum" => $data,
            "laporan" => [$laporan]
        ];
    }
}
