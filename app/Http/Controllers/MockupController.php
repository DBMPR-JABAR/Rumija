<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MockupController extends Controller
{
    public function bankeu_create_pre()
    {
        $ruas_jalan = DB::table('ruas_jalan_kabupaten_tarung')->select(['*'])->get();
        $kategori = DB::table('master_kategori_paket')->get();
        $penyedia_jasa = DB::connection('talikuat')->table('master_penyedia_jasa')->get();
        $konsultan = DB::connection('talikuat')->table('master_konsultan')->get();
        $ppk = DB::connection('talikuat')->table('master_ppk')->get();
        $action = 'store';
        return view('admin.input_data.bankeu.mockup.pre', compact('action', 'ruas_jalan', 'kategori', 'penyedia_jasa', 'konsultan', 'ppk'));
    }
}
