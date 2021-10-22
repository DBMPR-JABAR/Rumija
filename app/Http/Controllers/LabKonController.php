<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Object_;

class LabKonController extends Controller
{
    public function index()
    {
        return view('admin.pengujian_bahan.dashboard');
    }

    public function show(Request $request)
    {
        if ($request->msg) {
            $color = "success";
            $msg = "Berhasil Memperbaharui Data";
            return view('admin.pengujian_bahan.input_data')->with(compact('color', 'msg'));
        } else
            return view('admin.pengujian_bahan.input_data');
    }

    public function add()
    {
        return view('admin.pengujian_bahan.add');
    }

    public function bahan_uji(Request $request)
    {
        $datas = $request;
        $datas['no_permohonan'] = 'LABKON-' . mt_rand(1000, 9999) . '-' . mt_rand(1000, 9999) . '-' . mt_rand(1000, 9999);
        return view('admin.pengujian_bahan.bahan_uji', compact('datas'));
    }

    public function cetak_permohonan($no_permohonan)
    {
        return view('pdf.permohonan_pengujian_labkon', compact('no_permohonan'));
    }

    public function pengkajian($no_permohonan)
    {
        return view('admin.pengujian_bahan.pengkajian', compact('no_permohonan'));
    }

    public function daftar_pemohon()
    {
        return view('admin.labkon.daftar_pemohon.index');
    }
}
