<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DropdownAddressController extends Controller
{
    
    public function getCity(Request $request)
    {
        $idProvince = $request->id;
        $city = DB::table('indonesia_cities')->where('province_id', $idProvince)->get();
        return response()->json($city);

    }
    
}
