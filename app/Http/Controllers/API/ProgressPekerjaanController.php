<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgressPekerjaanController extends Controller
{
    public function __construct()
    {
        $this->user = auth('api')->user();
        if (!$this->user) {
            $this->response['message'] = 'Unauthorized';
            return response()->json($this->response, 200);
        }
        $this->userUptd = str_replace('uptd', '', $this->user->internalRole->uptd);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $progress = DB::table('progress_mingguan')
                ->where('uptd_id', $this->userUptd)
                ->get();

            $this->response['status'] = 'success';
            $this->response['data']['progress'] = $progress;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error' . $th;
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
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required|date',
                'kegiatan' => 'required|string',
                'namaPaket' => 'required|string',
                'penyediaJasa' => 'required|string',
                'sup' => 'required|string',
                'namaRuasJalan' => 'required|string',
                'jenisPekerjaan' => 'required|string|min:3',
                'long' => 'required|string',
                'lat' => 'required|string',
                'rencana' => 'required|string',
                'realisasi' => 'required|string',
                'waktuKontrak' => 'required|string',
                'terpakai' => 'required|string',
                'nilaiKontrak' => 'required|string',
                'keuangan' => 'required|string',
                'fotoDokumentasi' => 'file',
                'videoDokumentasi' => 'mimes:mp4'
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $progress = [];
            $progress['deviasi'] = 0;
            $progress['bayar1'] = 0;
            $progress['sisa'] = 0;
            $progress['prosentase'] = 0;
            $progress['kategori'] = null;
            $progress['status'] = null;
            $progress['tanggal'] = $request->tanggal;
            $progress['kegiatan'] = $request->kegiatan;
            $progress['nama_paket'] = $request->namaPaket;
            $progress['penyedia_jasa'] = $request->penyediaJasa;
            $progress['rencana'] = $request->rencana;
            $progress['realisasi'] = $request->realisasi;
            $progress['waktu_kontrak'] = $request->waktuKontrak;
            $progress['terpakai'] = $request->terpakai;
            $progress['bayar'] = $request->keuangan;
            $progress['lat'] = $request->lat;
            $progress['lng'] = $request->long;
            $progress['jenis_pekerjaan'] = $request->jenisPekerjaan;
            $progress['ruas_jalan'] = $request->namaRuasJalan;
            $progress['sup'] = $request->sup;
            $progress['uptd_id'] = $this->userUptd;

            if ($request->fotoDokumentasi != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->fotoDokumentasi->getClientOriginalName());
                $request->fotoDokumentasi->storeAs('public/progresskerja/', $path);
                $progress['foto'] = $path;
            }
            if ($request->videoDokumentasi != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->videoDokumentasi->getClientOriginalName());
                $request->videoDokumentasi->storeAs('public/progresskerja/', $path);
                $progress['video'] = $path;
            }

            DB::table('progress_mingguan')->insert($progress);
            $this->response['status'] = 'success';
            $this->response['data']['message'] = 'Berhasil menambahkan progress pekerjaan';
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error' . $th;
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
        try {
            $validator = Validator::make($request->all(), [
                'tanggal' => 'date',
                'kegiatan' => 'string',
                'namaPaket' => 'string',
                'penyediaJasa' => 'string',
                'sup' => 'string',
                'namaRuasJalan' => 'string',
                'jenisPekerjaan' => 'string|min:3',
                'long' => 'string',
                'lat' => 'string',
                'rencana' => 'string',
                'realisasi' => 'string',
                'waktuKontrak' => 'string',
                'terpakai' => 'string',
                'nilaiKontrak' => 'string',
                'keuangan' => 'string',
                'fotoDokumentasi' => 'file',
                'videoDokumentasi' => 'mimes:mp4'
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $progress = [];
            $progress['deviasi'] = 0;
            $progress['bayar1'] = 0;
            $progress['sisa'] = 0;
            $progress['prosentase'] = 0;
            $progress['kategori'] = null;
            $progress['status'] = null;
            if ($request->tanggal)
                $progress['tanggal'] = $request->tanggal;
            if ($request->kegiatan)
                $progress['kegiatan'] = $request->kegiatan;
            if ($request->namaPaket)
                $progress['nama_paket'] = $request->namaPaket;
            if ($request->penyediaJasa)
                $progress['penyedia_jasa'] = $request->penyediaJasa;
            if ($request->rencana)
                $progress['rencana'] = $request->rencana;
            if ($request->realisasi)
                $progress['realisasi'] = $request->realisasi;
            if ($request->waktuKontrak)
                $progress['waktu_kontrak'] = $request->waktuKontrak;
            if ($request->terpakai)
                $progress['terpakai'] = $request->terpakai;
            if ($request->keuangan)
                $progress['bayar'] = $request->keuangan;
            if ($request->lat)
                $progress['lat'] = $request->lat;
            if ($request->long)
                $progress['lng'] = $request->long;
            if ($request->jenisPekerjaan)
                $progress['jenis_pekerjaan'] = $request->jenisPekerjaan;
            if ($request->namaRuasJalan)
                $progress['ruas_jalan'] = $request->namaRuasJalan;
            if ($request->sup)
                $progress['sup'] = $request->sup;
            $progress['uptd_id'] = $this->userUptd;
            if ($request->fotoDokumentasi != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->fotoDokumentasi->getClientOriginalName());
                $request->fotoDokumentasi->storeAs('public/progresskerja/', $path);
                $progress['foto'] = $path;
            }
            if ($request->videoDokumentasi != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->videoDokumentasi->getClientOriginalName());
                $request->videoDokumentasi->storeAs('public/progresskerja/', $path);
                $progress['video'] = $path;
            }

            DB::table('progress_mingguan')->where('id', $id)->update($progress);
            $this->response['status'] = 'success';
            $this->response['data']['message'] = 'Berhasil memperbaharui progress pekerjaan';
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error' . $th;
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
        try {
            $old = DB::table('progress_mingguan')->where('id', $id)->first();
            $old->foto ?? Storage::delete('public/progresskerja/' . $old->foto);
            $old->video ?? Storage::delete('public/progresskerja/' . $old->video);

            $temp = DB::table('progress_mingguan')->where('id', $id);
            $temp->delete();

            $this->response['status'] = 'success';
            $this->response['data']['message'] = 'Berhasil menghapus Pekerjaan';
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getPaketDanPenyedia()
    {
        try {
            $paket = array();
            $penyedia = array();
            $data2 = DB::table('pembangunan');
            // $data2 = $data2->where('uptd_id', $this->userUptd);
            $data2 = $data2->get();
            $number1 = 0;
            $number2 = 0;
            foreach ($data2 as $val) {
                if ($val->nama_paket != '') {
                    $number1 += 1;
                    $paketFilter = ['no' => $number1, 'nama' => $val->nama_paket];
                    array_push($paket, $paketFilter);
                }
                if ($val->penyedia_jasa != '') {
                    $number2 += 2;
                    $penyedia_jasa = ['no' => $number2, 'nama' => $val->penyedia_jasa];
                    array_push($penyedia, $penyedia_jasa);
                }
            }

            $tempArray = array_unique(array_column($penyedia, 'nama'));
            $penyedia_jasa = array_values(array_intersect_key($penyedia, $tempArray));
            $tempArray2 = array_unique(array_column($paket, 'nama'));
            $paket = array_values(array_intersect_key($paket, $tempArray2));
            $this->response['status'] = 'success';
            $this->response['data']['paket'] = $paket;
            $this->response['data']['penyedia'] = $penyedia_jasa;

            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
}
