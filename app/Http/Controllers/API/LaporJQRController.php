<?php

namespace App\Http\Controllers\API;

use App\Events\SendMailLaporanMasyarakat;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LaporJQRController extends Controller
{

    public function __construct()
    {
        // Static Token JQR
        $this->x_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJKUVIiLCJpYXQiOjE2Mjg1NjgwOTcsImV4cCI6MjUzMzg5ODczNzAxLCJhdWQiOiJ3d3cuamFiYXJxci5pZCIsInN1YiI6IjA4MTExMzU3Nzc3In0.PQSro3_nr0Yza15yCejeQnae_phZl9sPRBWaqGL6AMU";
        $this->response['status'] = 'failed';
    }

    public function credentialsCheck(Request $request)
    {
        if ($request->header("x-api-key") === null || $request->header("x-api-key") !== $this->x_api_key) {
            $this->response["status"] = "failed";
            $this->response["data"]["message"] = "Invalid Credentials";
            return false;
        } else return true;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if (!$this->credentialsCheck($request)) {
                return response()->json($this->response, 401);
            }
            $laporan_masyarakat = DB::table('monitoring_laporan_masyarakat')
                ->where('nomorPengaduan', 'like', '%QR%')
                ->leftJoin('utils_jenis_laporan', 'utils_jenis_laporan.id', 'monitoring_laporan_masyarakat.jenis')
                ->select([
                    'nomorPengaduan as no_aduan',
                    'utils_jenis_laporan.name as jenis_laporan',
                    'lokasi',
                    'nama',
                    'nik',
                    'telp',
                    'email',
                    'jenis',
                    'gambar',
                    'deskripsi as pengaduan',
                    'status',
                    'created_at',
                    'updated_at'
                ])
                ->get();
            $this->response['status'] = 'success';
            $this->response['data']["laporan_masyarakat"] = $laporan_masyarakat;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
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
            if (!$this->credentialsCheck($request)) {
                return response()->json($this->response, 401);
            }

            $validator = Validator::make($request->all(), [
                'no_aduan' => 'required|unique:monitoring_laporan_masyarakat,nomorPengaduan',
                'jenis_laporan_id' => 'required|integer|exists:utils_jenis_laporan,id',
                'lokasi_id' => 'required|integer|exists:jabar_cities,id',
                'nama' => 'required|string|min:3',
                'nik' => 'required|string|min:16',
                'alamat' => 'required|string|min:5',
                'telp' => 'required|string|min:9',
                'email' => 'required|email',
                'gambar' => 'required|image',
                'pengaduan' => 'required|string',
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 400);
            }

            $laporan_masyarakat = $request->except(['no_aduan', 'lokasi_id', 'gambar', 'jenis_laporan_id', 'pengaduan']);
            $lokasi = DB::table('jabar_cities')->where('id', $request->lokasi_id)->first();
            $laporan_masyarakat['jenis'] = $request->jenis_laporan_id;
            $laporan_masyarakat['nomorPengaduan'] = $request->no_aduan;
            $laporan_masyarakat['deskripsi'] = $request->pengaduan;
            $laporan_masyarakat['uptd_id'] = $lokasi->uptd_id;
            $laporan_masyarakat['lokasi'] = $lokasi->name;
            $laporan_masyarakat['created_at'] = Carbon::now();
            $laporan_masyarakat['updated_at'] = Carbon::now();

            if ($request->file('gambar')) {
                $path = 'laporan_masyarakat/' . date("YmdHis") . '_' . $request->gambar->getClientOriginalName();
                $request->gambar->storeAs('public/', $path);
                $laporan_masyarakat['gambar'] = $path;
            }

            DB::table('monitoring_laporan_masyarakat')->insert($laporan_masyarakat);

            Event::dispatch(new SendMailLaporanMasyarakat($request->no_aduan, 'Submitted'));

            $this->response['status'] = 'success';
            $this->response['data']["message"] = "Berhasil menambahkan laporan";
            // $this->response['data']["laporan_masyarakat"] = $laporan_masyarakat;
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
    public function show(Request $request, $id)
    {
        try {
            if (!$this->credentialsCheck($request)) {
                return response()->json($this->response, 401);
            }

            $exits = DB::table('monitoring_laporan_masyarakat')->where('nomorPengaduan', $id)->count();
            if ($exits === 0) {
                $this->response['data']['message'] = "Nomor Aduan " . $id . " not exits";
                return response()->json($this->response, 400);
            }

            $laporan_masyarakat = DB::table('monitoring_laporan_masyarakat')
                ->where('nomorPengaduan', $id)
                ->leftJoin('utils_jenis_laporan', 'utils_jenis_laporan.id', 'monitoring_laporan_masyarakat.jenis')
                ->select([
                    'nomorPengaduan as no_aduan',
                    'utils_jenis_laporan.name as jenis_laporan',
                    'lokasi',
                    'nama',
                    'nik',
                    'telp',
                    'email',
                    'jenis',
                    'gambar',
                    'deskripsi as pengaduan',
                    'created_at',
                    'updated_at'
                ])
                ->first();
            $this->response['status'] = 'success';
            $this->response['data']["laporan_masyarakat"] = $laporan_masyarakat;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
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
        try {
            if (!$this->credentialsCheck($request)) {
                return response()->json($this->response, 401);
            }

            $exits = DB::table('monitoring_laporan_masyarakat')->where('nomorPengaduan', $id)->count();
            if ($exits === 0) {
                $this->response['data']['message'] = "Nomor Aduan " . $id . " not exits";
                return response()->json($this->response, 400);
            }

            $validator = Validator::make($request->all(), [
                'jenis_laporan_id' => 'required|integer|exists:utils_jenis_laporan,id',
                'lokasi_id' => 'required|integer|exists:jabar_cities,id',
                'nama' => 'required|string|min:3',
                'nik' => 'required|string|min:16',
                'alamat' => 'required|string|min:5',
                'telp' => 'required|string|min:9',
                'email' => 'required|email',
                'gambar' => 'required|image',
                'status' => 'in:Submitted,Progress,Done',
                'pengaduan' => 'required|string',
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 400);
            }

            $laporan_masyarakat = $request->except(['no_aduan', 'lokasi_id', 'gambar', 'jenis_laporan_id', 'pengaduan', '_method']);
            $lokasi = DB::table('jabar_cities')->where('id', $request->lokasi_id)->first();
            $laporan_masyarakat['jenis'] = $request->jenis_laporan_id;
            $laporan_masyarakat['deskripsi'] = $request->pengaduan;
            $laporan_masyarakat['uptd_id'] = $lokasi->uptd_id;
            $laporan_masyarakat['status'] = $request->status;
            $laporan_masyarakat['lokasi'] = $lokasi->name;
            $laporan_masyarakat['updated_at'] = Carbon::now();

            if ($request->file('gambar')) {
                $path = 'laporan_masyarakat/' . date("YmdHis") . '_' . $request->gambar->getClientOriginalName();
                $request->gambar->storeAs('public/', $path);
                $laporan_masyarakat['gambar'] = $path;
            }

            DB::table('monitoring_laporan_masyarakat')->where('nomorPengaduan', $id)->update($laporan_masyarakat);

            $this->response['status'] = 'success';
            $this->response['data']["message"] = "Berhasil memperbaharui laporan " . $id;
            // $this->response['data']["laporan_masyarakat"] = $laporan_masyarakat;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            if (!$this->credentialsCheck($request)) {
                return response()->json($this->response, 401);
            }

            $exits = DB::table('monitoring_laporan_masyarakat')->where('nomorPengaduan', $id)->count();
            if ($exits === 0) {
                $this->response['data']['message'] = "Nomor Aduan " . $id . " not exits";
                return response()->json($this->response, 400);
            }

            DB::table('monitoring_laporan_masyarakat')->where('nomorPengaduan', $id)->delete();

            $this->response['status'] = 'success';
            $this->response['data']["message"] = "Berhasil menghapus laporan " . $id;
            // $this->response['data']["laporan_masyarakat"] = $laporan_masyarakat;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function get_cities()
    {
        try {
            $cities = DB::table('jabar_cities')->get(["id", "name"]);
            $this->response['status'] = 'success';
            $this->response['data']['cities'] = $cities;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['status'] = 'failed';
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function get_jenis_laporan()
    {
        try {
            $jenis_laporan = DB::table('utils_jenis_laporan')->get();
            $this->response['status'] = 'success';
            $this->response['data']['jenis_laporan'] = $jenis_laporan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['status'] = 'failed';
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function status_update(Request $request, $id)
    {
        try {
            if (!$this->credentialsCheck($request)) {
                return response()->json($this->response, 401);
            }

            $exits = DB::table('monitoring_laporan_masyarakat')->where('nomorPengaduan', $id)->count();
            if ($exits === 0) {
                $this->response['data']['message'] = "Nomor Aduan " . $id . " not exits";
                return response()->json($this->response, 400);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:Submitted,Progress,Done',
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 400);
            }

            $laporan_masyarakat = $request->except(['_method']);
            $laporan_masyarakat['status'] = $request->status;
            $laporan_masyarakat['updated_at'] = Carbon::now();

            DB::table('monitoring_laporan_masyarakat')->where('nomorPengaduan', $id)->update($laporan_masyarakat);

            Event::dispatch(new SendMailLaporanMasyarakat($request->no_aduan, $request->status));

            $this->response['status'] = 'success';
            $this->response['data']["message"] = "Berhasil memperbaharui status laporan " . $id;
            // $this->response['data']["laporan_masyarakat"] = $laporan_masyarakat;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error'.$th;
            return response()->json($this->response, 500);
        }
    }
}
