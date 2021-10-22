<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeoJsonController extends Controller
{
    public function getRuasJalanProvinsi()
    {
        try {
            $data = DB::table('master_ruas_jalan')->get();
            // $features = [];
            // foreach ($data as $geoJson) {
            //     $properties = DB::table('master_ruas_jalan')
            //         ->where('id_ruas_jalan', $geoJson->id_ruas_jalan)->first();
            //     if ($properties) {
            //         $searchProperties = $properties;
            //         $searchProperties->index = $properties->nama_ruas_jalan;
            //         $feature = [
            //             'type' => 'Feature',
            //             'id'=>"RuasJalanPropinsi",
            //             'properties' => $searchProperties,
            //             'geometry'=>json_decode($geoJson->geo_json)
            //         ];
            //         array_push($features, $feature);
            //     }
            // }
            $this->response['message'] = 'success';
            $this->response["data"]["ruas_jalan_propinsi"] = $data;
            // $this->response['data']['geo_json'] = $features;
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error' . $e;
            return response()->json($this->response, 500);
        }
    }

    public function getRuasJalanCustom()
    {
        try {
            $data = DB::table('ruas_jalan_custom')->get();
            // $features = [];
            // foreach ($data as $geoJson) {
            //     $properties = DB::table('master_ruas_jalan')
            //         ->where('id_ruas_jalan', $geoJson->id_ruas_jalan)->first();
            //     if ($properties) {
            //         $searchProperties = $properties;
            //         $searchProperties->index = $properties->nama_ruas_jalan;
            //         $feature = [
            //             'type' => 'Feature',
            //             'id'=>"RuasJalanPropinsi",
            //             'properties' => $searchProperties,
            //             'geometry'=>json_decode($geoJson->geo_json)
            //         ];
            //         array_push($features, $feature);
            //     }
            // }
            $this->response['message'] = 'success';
            $this->response["data"]["ruas_jalan_custom"] = $data;
            // $this->response['data']['geo_json'] = $features;
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error' . $e;
            return response()->json($this->response, 500);
        }
    }
}
