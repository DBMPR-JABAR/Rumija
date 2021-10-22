<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IntegrasiTalikuatController extends Controller
{
    public function curva_s()
    {
        $data_umum = DB::connection('talikuat')->table('data_umum')->where('is_deleted','=',null);

        if (Auth::user()->internalRole->uptd) {
            if (Auth::user()->internalRole->uptd) {
                $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
                $data_umum = $data_umum->where('id_uptd', $uptd_id);
            }
        }
        // dd($data_umum);
        $data_umum = $data_umum->get();
        return view('admin.talikuat.curva_s', compact('data_umum'));
    }
}
