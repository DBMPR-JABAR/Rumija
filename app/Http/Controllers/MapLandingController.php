<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapLandingController extends Controller
{
    public function mapMasyarakat() {
        $profil = DB::table('landing_profil')->where('id', 1)->first();
        return view('landing.map.map-dashboard-masyarakat', compact('profil'));
    }
    public function mapUptd($uptd_id) {
        $profil = DB::table('landing_profil')->where('id', 1)->first();
        return view('landing.map.map-dashboard-uptd', compact('profil','uptd_id'));
    }
}
