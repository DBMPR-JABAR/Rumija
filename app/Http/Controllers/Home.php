<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Home extends Controller
{
    public function index()
    {
        $pembangunan_talikuat = DB::connection('talikuat')->table('data_umum')->where('is_deleted', '=', null);

        if (Auth::user()->internalRole->uptd) {
            if (Auth::user()->internalRole->uptd) {
                $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
                $pembangunan_talikuat = $pembangunan_talikuat->where('id_uptd', $uptd_id);
            }
        }
        $pembangunan_talikuat = $pembangunan_talikuat->get();

        $data_talikuat = DB::connection('talikuat')->select('SELECT
        ( SUM( master_laporan_harian.bobot )) as persentase,
        master_laporan_harian.id_data_umum,
        data_umum.nm_paket as nama_paket,
        data_umum.id_uptd
    FROM
        `master_laporan_harian`
        JOIN jadual ON jadual.id = master_laporan_harian.id_jadual
        JOIN data_umum ON data_umum.id = master_laporan_harian.id_data_umum

        WHERE master_laporan_harian.ditolak = 4 AND data_umum.is_deleted IS NULL AND master_laporan_harian.reason_delete IS NULL
    GROUP BY
        master_laporan_harian.id_data_umum');

        $detail_data_talikuat = null;
        foreach($pembangunan_talikuat as $data) {
            $detail_data_talikuat[$data->id] = $this->GetDataUmum($data->id);
        }
        // dd($detail_data_talikuat);

        return view('admin.home', compact('pembangunan_talikuat', 'data_talikuat','detail_data_talikuat'));
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
