<?php

namespace App\Http\Controllers\Backup;

use App\Http\Controllers\Controller;
use App\Model\Push\UserPushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{

    public function debug()
    {
        dd(UserPushNotification::whereNotNull('device_token')->whereIn('user_id',[1])->pluck('device_token')->all());
    }

    public function index()
    {

        $role_id = Auth::user()->internal_role_id;
        $menu = "Executive Dashboard";
        $type = "View";

        $access = hasAccess($role_id, $menu, $type); // return true if exists

        /*
        Available Access:
            Create
            View
            Update
            Delete
        Available Menu:
            Manage
            Disposisi
            Input Data
            Lapor
            Landing Page
            Pesan
            Log
            User
            Ruas Jalan
            Jembatan
            Rawan Bencana
            Pekerjaan
            Kondisi Jalan
            Rekap
            Progress Kerja
            Data Paket
            Executive Dashboard
            Proyek Kontrak
            Kemantapan Jalan
            Laporan Akses
            Anggaran & Realisasi Keuangan
            Survey Kondisi Jalan
            Kirim Disposisi
            Disposisi Masuk
            Disposisi Tindak Lanjut
            Disposisi Instruksi
            Profil
            Slideshow
            Fitur
            UPTD

        */
    }

    public function uptd()
    {
        $jembatan = DB::table('master_jembatan');

        if($uptd = uptdAccess(1, 'jembatan')){
            $jembatan = $jembatan->whereIn('uptd',$uptd);
        }

        $jembatan = $jembatan->get();
        dd($jembatan);
    }
}
