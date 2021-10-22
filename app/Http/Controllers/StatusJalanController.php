<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StatusJalanController extends Controller
{

    public function index()
    {
        return view('landing.status_jalan.index');
    }

    public function api_index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required',
        ]);

        if ($validator->fails()) {
            $this->response['data']['error'] = $validator->errors();
            return response()->json($this->response, 200);
        }

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius;
        $pemeliharaan = DB::table('kemandoran')
            ->select('*')
            ->selectRaw('( 6371393 * acos( cos( radians(?) ) *
                           cos( radians( lat ) )
                           * cos( radians( lng ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( lat ) ) )
                         ) AS distance', [$latitude, $longitude, $latitude])
            ->havingRaw("distance < ?", [$radius])
            ->orderBy('distance')
            ->get();
        $ruas_jalan = DB::table('master_ruas_jalan')
            ->select('*')
            ->selectRaw('( 6371393 * acos( cos( radians(?) ) *
                       cos( radians( lat_awal ) )
                       * cos( radians( long_awal ) - radians(?)
                       ) + sin( radians(?) ) *
                       sin( radians( lat_awal ) ) )
                     ) AS distance_awal', [$latitude, $longitude, $latitude])
            ->selectRaw('( 6371393 * acos( cos( radians(?) ) *
                     cos( radians( lat_ctr ) )
                     * cos( radians( long_ctr ) - radians(?)
                     ) + sin( radians(?) ) *
                     sin( radians( lat_ctr ) ) )
                   ) AS distance_center', [$latitude, $longitude, $latitude])
            ->selectRaw('( 6371393 * acos( cos( radians(?) ) *
                   cos( radians( lat_akhir ) )
                   * cos( radians( long_akhir ) - radians(?)
                   ) + sin( radians(?) ) *
                   sin( radians( lat_akhir ) ) )
                 ) AS distance_akhir', [$latitude, $longitude, $latitude])
            ->selectRaw('LEAST(( 6371393 * acos( cos( radians(?) ) *
            cos( radians( lat_awal ) )
            * cos( radians( long_awal ) - radians(?)
            ) + sin( radians(?) ) *
            sin( radians( lat_awal ) ) )
    ),( 6371393 * acos( cos( radians(?) ) *
    cos( radians( lat_ctr ) )
    * cos( radians( long_ctr ) - radians(?)
    ) + sin( radians(?) ) *
    sin( radians( lat_ctr ) ) )
),( 6371393 * acos( cos( radians(?) ) *
    cos( radians( lat_akhir ) )
    * cos( radians( long_akhir ) - radians(?)
    ) + sin( radians(?) ) *
    sin( radians( lat_akhir ) ) )
)) AS min_distance', [$latitude, $longitude, $latitude, $latitude, $longitude, $latitude, $latitude, $longitude, $latitude])
            ->havingRaw('min_distance < ?', [1000000])
            ->orderBy('min_distance')
            ->limit(1)
            ->get();
        $response['pemeliharaan'] = $pemeliharaan;
        $response['ruas_jalan'] = $ruas_jalan;
        $response['coords']['latitude'] = $latitude;
        $response['coords']['longitude'] = $longitude;
        return response()->json($response, 200);
    }
}
