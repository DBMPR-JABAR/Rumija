<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\GeneralResource;
use App\Model\DWH\VehicleCounting;
use Illuminate\Support\Facades\Storage;

class VehicleCountingController extends Controller
{

    private $response;
    public function __construct() {
        $this->response = [
            'status' => 'false',
            'data' => []
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return (new GeneralResource(VehicleCounting::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $vehicleCounting = new VehicleCounting;
            $vehicleCounting->fill($request->except(['GAMBAR']));
            if($request->GAMBAR != null){
                // $path = 'vehicle_counting/'.date("YmdHis").'_'.$request->GAMBAR->getClientOriginalName();
                // $request->GAMBAR->storeAs('public/',$path);
                // $vehicleCounting['GAMBAR'] = $path;

                if (preg_match('/^data:image\/(\w+);base64,/', $request->GAMBAR, $type)) {
                    $random = rand(100000,999999);
                    $img = substr($request->GAMBAR, strpos($request->GAMBAR, ',') + 1);
                    $type = strtolower($type[1]); // jpg, png, gif

                    if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                        throw new \Exception('invalid image type');
                    }
                    $img = str_replace( ' ', '+', $img );
                    $img = base64_decode($img);

                    if ($img === false) {
                        throw new \Exception('base64_decode failed');
                    }
                    $path = 'vehicle_counting/'.date("YmdHis").'_'.$random.'.'.$type;
                    Storage::disk('public')->put($path, $img);
                    $vehicleCounting['GAMBAR'] = url('storage/'.$path);
                } else {
                    throw new \Exception('did not match data URI with image data');
                }

            }
            $vehicleCounting->save();
            $this->response['status'] = 'success';
            $this->response['data']['ID'] = $vehicleCounting->id;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
