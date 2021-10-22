<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MaterialPekerjaanController extends Controller
{
    public function __construct()
    {
        $this->user = auth('api')->user();
        if (!$this->user) {
            $this->response['message'] = 'Unauthorized';
            return response()->json($this->response, 200);
        }
        // $this->userUptd = str_replace('uptd', '', $this->user->internalRole->uptd);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

            $validator = Validator::make($request->all(), [
                'id_pek' => 'required',
                'jenis_pekerjaan' => 'required|string',
                // 'nama_bahan1' => 'required|string',
                // 'jum_bahan1' => 'required|string',
                // 'satuan1' => 'required|string',
                
                'bahan_material' => '',
                'peralatan_operasional' => 'required|string',
                'bahan_operasional' => '',
                'pekerja' => '',
                'penghambat_pelaksanaan' =>'',
                'uptd_id' => 'required|int'
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $sudahAda = DB::table('bahan_material')->where('id_pek', $request->id_pek)->count();

            if ($sudahAda) {
                $this->response['status'] = 'success';
                $this->response['data']['tess'] = json_decode($request->peralatan_operasional);
                
                $this->response['data']['message'] = 'Bahan material sudah ada, silahkan update';
                return response()->json($this->response, 200);
            }

            $request['tanggal'] = Carbon::now();
            $request['nama_mandor'] = $this->user->name;
            $bahan_tiba =$request->except([
                'peralatan',

                'bahan_material',
                'peralatan_operasional',
                'bahan_operasional',
                'pekerja',
                'penghambat_pelaksanaan'

                
            ]);

            $temp_bahan_tiba = json_decode($request->bahan_material);
            $temp_peralatan_operasional = json_decode($request->peralatan_operasional);
            $temp_bahan_operasional = json_decode($request->bahan_operasional);
            $temp_pekerja = json_decode($request->pekerja);
            $temp_penghambat_pelaksanaan = json_decode($request->penghambat_pelaksanaan);
            
            $x=1;
            if($temp_bahan_tiba){
                for($i = 0; $i<count($temp_bahan_tiba) ;$i++){
                    $jum_bahan = "jum_bahan$x";
                    $nama_bahan = "nama_bahan$x";
                    $satuan = "satuan$x";
                    $bahan_tiba[$nama_bahan]=DB::table('item_bahan')->where('no',$temp_bahan_tiba[$i]->nama_bahan)->pluck('nama_item')->first();
                    $bahan_tiba[$jum_bahan]=$temp_bahan_tiba[$i]->jum_bahan;
                    $bahan_tiba[$satuan]=$temp_bahan_tiba[$i]->satuan;
                    $x++;
                }
            }
            $store_material = DB::table('bahan_material')->insert($bahan_tiba);
                if($store_material){
                    
                    for($i = 0; $i<count($temp_peralatan_operasional) ;$i++){
                        if($temp_peralatan_operasional[$i]->jum_peralatan != 0){
                            $peralatan['id_pek'] = $request->id_pek;
                            $peralatan['id_peralatan'] = $temp_peralatan_operasional[$i]->nama_peralatan;
                            $peralatan['kuantitas'] = $temp_peralatan_operasional[$i]->jum_peralatan;
                            $peralatan['satuan'] = $temp_peralatan_operasional[$i]->satuan_peralatan;
                            DB::table('kemandoran_detail_peralatan')->insert($peralatan);
                        }
                    }

                    for($i = 0; $i<count($temp_bahan_operasional) ;$i++){
                        if($temp_bahan_operasional[$i]->jum_bahan_operasional != 0){
                            $material['id_pek'] = $request->id_pek;
                            $material['id_material'] = $temp_bahan_operasional[$i]->nama_bahan_operasional;
                            $material['kuantitas'] = $temp_bahan_operasional[$i]->jum_bahan_operasional;
                            $material['satuan'] = $temp_bahan_operasional[$i]->satuan_operasional;
                            DB::table('kemandoran_detail_material')->insert($material);
                        }
                    }
                    foreach($temp_pekerja as $dat){
                        $pekerja['id_pek'] = $request->id_pek;
                        $pekerja['jabatan'] = $dat->jabatan_pekerja;
                        $pekerja['jumlah'] = $dat ->jum_pekerja;
                        DB::table('kemandoran_detail_pekerja')->insert($pekerja);

                    }
                    // for($i = 0; $i<count($temp_pekerja) ;$i++){
                    //     $pekerja['id_pek'] = $request->id_pek;
                    //     $pekerja['jabatan'] = $temp_pekerja[$i]->jabatan_pekerja;
                    //     $pekerja['jumlah'] = $temp_pekerja[$i]->jum_pekerja;
                    // }
                    for($i = 0; $i<count($temp_penghambat_pelaksanaan) ;$i++){
                        if($temp_penghambat_pelaksanaan[$i]->start_time != 0){
                            $penghambat['id_pek'] = $request->id_pek;
                            $penghambat['jenis_gangguan'] = $temp_penghambat_pelaksanaan[$i]->jenis_gangguan;
                            $penghambat['start_time'] = $temp_penghambat_pelaksanaan[$i]->start_time;
                            $penghambat['end_time'] = $temp_penghambat_pelaksanaan[$i]->end_time;
                            $penghambat['akibat'] = $temp_penghambat_pelaksanaan[$i]->akibat;
                            DB::table('kemandoran_detail_penghambat')->insert($penghambat);
                        }
                    }

                    $kemandoran = DB::table('kemandoran')->where('id_pek', $request->id_pek);
                    $kemandoranUpdate['mail'] = 1;
                    $kemandoran->update($kemandoranUpdate);
                    $this->response['status'] = 'success';

                    $this->response['data']['message'] = 'Berhasil Menambah Material Pekerjaan';
                    return response()->json($this->response, 200);
                }else{
                    $this->response['status'] = 'error';
                    $this->response['data']['message'] = 'data gagal disimpan';
                    $this->response['data']['tess'] = json_decode($request->peralatan_operasional);
                    
                    
                    return response()->json($this->response, 500);
                }

        } catch (\Exception $e) {
           
            $this->response['data']['tess'] = json_decode($request->peralatan_operasional);
            
            $this->response['data']['message'] = 'Internal Error';
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
        try {
            $bahan_material = DB::table('bahan_material')->where('id_pek', $id)->first();
            $this->response['status'] = 'success';
            $this->response['data']['bahan_material'] = $bahan_material;
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error ';
            return response()->json($this->response, 500);
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'jenis_pekerjaan' => 'string',
                'peralatan' => 'required|string',
                'nama_bahan1' => 'string',
                'jum_bahan1' => 'string',
                'satuan1' => 'string',
                'nama_bahan2' => 'string',
                'jum_bahan2' => 'string',
                'satuan2' => 'string',
                'nama_bahan3' => 'string',
                'jum_bahan3' => 'string',
                'satuan3' => 'string',
                'nama_bahan4' => 'string',
                'jum_bahan4' => 'string',
                'satuan4' => 'string',
                'nama_bahan5' => 'string',
                'jum_bahan5' => 'string',
                'satuan5' => 'string',
                'nama_bahan6' => 'string',
                'jum_bahan6' => 'string',
                'satuan6' => 'string',
                'nama_bahan7' => 'string',
                'jum_bahan7' => 'string',
                'satuan7' => 'string',
                'nama_bahan8' => 'string',
                'jum_bahan8' => 'string',
                'satuan8' => 'string',
                'nama_bahan9' => 'string',
                'jum_bahan9' => 'string',
                'satuan9' => 'string',
                'nama_bahan10' => 'string',
                'jum_bahan10' => 'string',
                'satuan10' => 'string',
                'nama_bahan11' => 'string',
                'jum_bahan11' => 'string',
                'satuan11' => 'string',
                'nama_bahan12' => 'string',
                'jum_bahan12' => 'string',
                'satuan12' => 'string',
                'nama_bahan13' => 'string',
                'jum_bahan13' => 'string',
                'satuan13' => 'string',
                'nama_bahan14' => 'string',
                'jum_bahan14' => 'string',
                'satuan14' => 'string',
                'nama_bahan15' => 'string',
                'jum_bahan15' => 'string',
                'satuan15' => 'string',
                'uptd_id' => 'required|int'
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $request['nama_mandor'] = $this->user->name;
            DB::table('bahan_material')->where('id_pek', $id)->update($request->except('_method','peralatan'));

            $kemandoran = DB::table('kemandoran')->where('id_pek', $id);
            $kemandoranUpdate['mail'] = 1;
            $kemandoranUpdate['peralatan'] = $request->peralatan;
            $kemandoran->update($kemandoranUpdate);

            $this->response['status'] = 'success';
            $this->response['data']['message'] = 'Berhasil Memperbaharui Material Pekerjaan';

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error ' . $e;
            return response()->json($this->response, 500);
        }
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

    public function bahanMaterial()
    {
        try {
            $bahan_material = DB::table('item_bahan')->select('no', 'nama_item')->where('keterangan',null)->get();
            $this->response['status'] = 'success';
            $this->response['data']['bahan_material'] = $bahan_material;
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error ';
            return response()->json($this->response, 500);
        }
    }
    public function getAlatOperasional()
    {
        try {
            $alatOperasional = DB::table('item_peralatan')
                ->get();

            $this->response['status'] = 'success';
            $this->response['data']['alat_operasional'] = $alatOperasional;

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
    public function getBahanMaterialOperasional()
    {
        try {
            $jenisPekerjaan = DB::table('item_bahan')->where('keterangan','Bahan Operasional')
                ->get();

            $this->response['status'] = 'success';
            $this->response['data']['jenis_pekerjaan'] = $jenisPekerjaan;

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
    public function satuanMaterial()
    {
        try {
            $satuan_material = DB::table('item_satuan')->get();
            $this->response['status'] = 'success';
            $this->response['data']['satuan_material'] = $satuan_material;
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error ';
            return response()->json($this->response, 500);
        }
    }
}
