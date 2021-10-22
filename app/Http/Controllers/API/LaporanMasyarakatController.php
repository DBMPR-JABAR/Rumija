<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailKerusakanJalanResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\Transactional\LaporanMasyarakat;
use App\Http\Resources\GeneralResource;
use App\Http\Resources\GetPetugasResource;
use App\Http\Resources\GetUPTDResource;
use App\Http\Resources\KerusakanJalanResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\ProgressLaporanResource;
use App\Http\Resources\StatusLaporanResource;
use App\Model\Transactional\LaporanProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaporanMasyarakatController extends Controller
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
    public function index(Request $request)
    {
        if($request->has("skip")){
            return (KerusakanJalanResource::collection(LaporanMasyarakat::where('email',$request->email)->skip($request->skip)->take($request->take)->get())->additional(['status' => 'success']));
        }
        return (KerusakanJalanResource::collection(LaporanMasyarakat::all())->additional(['status' => 'success']));
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
            $rand = rand(100000,999999);
            $kode = "P-".$rand;
            $laporanMasyarakat = new LaporanMasyarakat;
            $laporanMasyarakat->fill($request->except(['gambar']));
            if($request->gambar != null){
                $path = 'laporan_masyarakat/'.date("YmdHis").'_'.$request->gambar->getClientOriginalName();
                $request->gambar->storeAs('public/',$path);
                $laporanMasyarakat['gambar'] = $path;
            }
            $laporanMasyarakat->nomorPengaduan = $kode;
            $laporanMasyarakat->status = 'Submitted';
            $laporanMasyarakat->save();
            $this->response['status'] = 'success';

            $this->response['data']['id'] = $laporanMasyarakat->id;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
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
        return new DetailKerusakanJalanResource(LaporanMasyarakat::findOrFail($id));
    }

    public function approve(Request $request)
    {
        try {
            $data = LaporanMasyarakat::find($request->id);
            $data->status = "Progress";
            $data->save();

            $this->response['status'] = 'success';
            $this->response['data']['id'] = $data->id;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function createProgress(Request $request)
    {
        try {
            $progress = new LaporanProgress;
            $progress->fill($request->except(['dokumentasi']));
            if($request->dokumentasi != null){
                $path = 'laporan_masyarakat_progress/'.date("YmdHis").'_'.$request->dokumentasi->getClientOriginalName();
                $request->dokumentasi->storeAs('public/',$path);
                $progress['dokumentasi'] = $path;
            }
            $progress->save();
            if($progress->persentase >= 100){
                $progress->laporan()->update(['status' => 'Done']);
            }
            $this->response['status'] = 'success';
            $this->response['data']['id'] = $progress->id;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getAll()
    {
        try {
            $data = DB::table('user_pegawai')->get();

            return (GetPetugasResource::collection($data)->additional(['status' => 'success']));
        }catch(\Exception $e){
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getPetugas()
    {
        try {
            $data = DB::table('user_pegawai')->get();

            return (GetPetugasResource::collection($data)->additional(['status' => 'success']));
        }catch(\Exception $e){
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getOnProgress($id)
    {
        try {
            $data = LaporanProgress::where('laporan_id',$id)->latest()->get();
            return (ProgressLaporanResource::collection($data)->additional(['status' => 'success']));
        }catch(\Exception $e){
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getListLaporan(Request $request, $status)
    {
        try {
            if($request->has("skip")){
                $data = LaporanMasyarakat::where('status',$status)->skip($request->skip)->take($request->take)->get();
            }else{
                $data = LaporanMasyarakat::where('status',$status)->get();
            }
            return (StatusLaporanResource::collection($data)->additional(['status' => 'success']));
        }catch(\Exception $e){
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
    public function getJenisLaporan()
    {
        try {
            $jenis = DB::table('utils_jenis_laporan')->get();

            $this->response['status'] = 'success';
            $this->response['data'] = $jenis;
            return response()->json($this->response, 200);
        }catch(\Exception $e){
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getNotifikasi()
    {
        try {
            $data = DB::table('utils_notifikasi')->where('role',auth('api')->user()->role)
                                                 ->orderBy('created_at','desc');
            if($data->count() > 0){
                return (NotificationResource::collection($data->get())->additional(['status' => 'success']));
            }
            return response()->json($this->response, 500);
        }catch(\Exception $e){
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getNotifikasiByUserId($userId)
    {
        try {
            $data = DB::table('utils_notifikasi')->where('user_id',$userId)
                                                 ->orderBy('created_at','desc');
            if($data->count() > 0){
                return (NotificationResource::collection($data->get())->additional(['status' => 'success']));
            }
            return response()->json($this->response, 500);
        }catch(\Exception $e){
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getUPTD()
    {
        try {
            $data = DB::table('landing_uptd')->get();

            return (GetUPTDResource::collection($data)->additional(['status' => 'success']));
        }catch(\Exception $e){
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getLokasi()
    {
        try {
            $lokasi = DB::table('utils_lokasi')->get();

            $this->response['status'] = 'success';
            $this->response['data'] = $lokasi;
            return response()->json($this->response, 200);
        }catch(\Exception $e){
            $this->response['data']['message'] = 'Internal Error';
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
    public function storeBencana(Request $request)
    {
        try {
            $laporan_bencana = $request->except('_token', 'ruas_jalan');
            // $laporan_bencana['slug'] = Str::slug($request->nama, '');
            $laporan_bencana['uptd_id'] = $request->uptd_id == '' ? 0 : $request->uptd_id;
            $ruas_jalan = DB::table('master_ruas_jalan')->where('id_ruas_jalan', $request->ruas_jalan)->first();
            $laporan_bencana['no_ruas'] = $ruas_jalan->id_ruas_jalan;
            $laporan_bencana['ruas_jalan'] = $ruas_jalan->nama_ruas_jalan;
            if ($request->foto != null) {
                $path =  Str::snake(date("YmdHis") . ' ' . $request->foto->getClientOriginalName());
                $request->foto->storeAs('public/laporan_bencana/', $path);
                $laporan_bencana['foto'] = $path;
            }
            if ($request->video != null) {
                $path =  Str::snake(date("YmdHis") . ' ' . $request->video->getClientOriginalName());
                $request->video->storeAs('public/laporan_bencana/', $path);
                $laporan_bencana['video'] = $path;
            }
            $laporan_bencana['created_at'] = Carbon::now();
            $laporan_bencana['created_by'] = Auth::user()->id;
            $icon_image = DB::table('icon_titik_rawan_bencana')->where('id', $request->icon_id)->get();
            $laporan_bencana['icon_image'] = $icon_image[0]->icon_image;
            DB::table('laporan_bencana')->insert($laporan_bencana);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menambahkan Bencana',  
                $this->response
            ]);
    
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
    public function getBencana($userId)
    {
        $laporan_bencana = DB::table('laporan_bencana')->where('created_by',$userId)->get();

        return response()->json([
            "response" => [
                "status"    => 200,
                "message"   => "List Data Bencana"
            ],
            "data" => $laporan_bencana
        ], 200);
        
    }
    public function destroyBencana($id)
    {
        //
        try {
            $old = DB::table('laporan_bencana')->where('id', $id);
            $old->delete();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menghapus Data Bencana',  
                $this->response
            ]);
    
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
    public function getRuasJalan()
    {
        try {
            $ruasJalan = DB::table('master_ruas_jalan')
                ->get();

            $this->response['status'] = 'success';
            $this->response['data']['ruas_jalan'] = $ruasJalan;

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
    public function getIcon()
    {
        try {
            $icon = DB::table('icon_titik_rawan_bencana')->get();

            $this->response['status'] = 'success';
            $this->response['data']['icon'] = $icon;

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
}
