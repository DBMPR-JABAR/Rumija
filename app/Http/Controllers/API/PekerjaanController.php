<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PekerjaanController extends Controller
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
            $pekerjaan = DB::table('kemandoran')
                // ->rightJoin('utils_pekerjaan', 'utils_pekerjaan.id_pek', '=', 'kemandoran.id_pek')
                ->where('is_deleted', 0)
                ->where('user_id', $this->user->id)
                ->get()
                ->reverse()->values();

            $this->response['status'] = 'success';
            $this->response['data']['pekerjaan'] = $pekerjaan;

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
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required|date',
                'idSup' => 'required',
                'namaPaket' => 'required|string',
                'idRuasJalan' => 'required',
                // 'idJenisPekerjaan' => 'required',
                'lokasi' => 'required|string|min:3',
                'lat' => 'required|string',
                'long' => 'required|string',
                'panjang' => 'required|string',
                // 'perkiraan_kuantitas' => 'required',
                'jumlah_pekerja' => 'required',

                // 'peralatan' => 'required|string',
                'fotoAwal' => 'file',
                'fotoSedang' => 'file',
                'fotoAkhir' => 'file',
                'fotoPegawai' => 'file',
                'video' => 'mimes:mp4'
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $pekerjaan = [];
            $pekerjaan['nama_mandor'] = $this->user->name;
            $pekerjaan['user_id'] = $this->user->id;
            $pekerjaan['created_by'] = $this->user->id;
            $pekerjaan['tanggal'] = $request->tanggal;
            $pekerjaan['sup_id'] = $request->idSup;
            $pekerjaan['sup'] = DB::table('utils_sup')->where('id', $request->idSup)->first()->name;
            $pekerjaan['paket'] = $request->namaPaket;
            $pekerjaan['ruas_jalan_id'] = $request->idRuasJalan;
            $pekerjaan['ruas_jalan'] = DB::table('master_ruas_jalan')
                ->where('id_ruas_jalan', $request->idRuasJalan)->first()->nama_ruas_jalan;
            // $pekerjaan['jenis_pekerjaan'] = DB::table('item_pekerjaan')->where('no', $request->idJenisPekerjaan)->first()->nama_item;
            $pekerjaan['jenis_pekerjaan'] = "Pemeliharaan";
            // $pekerjaan['peralatan'] = $request->peralatan;
            $pekerjaan['panjang'] = $request->panjang;
            // $pekerjaan['perkiraan_kuantitas'] = $request->perkiraan_kuantitas;
            $pekerjaan['jumlah_pekerja'] = $request->jumlah_pekerja;

            $pekerjaan['lokasi'] = $request->lokasi;

            $pekerjaan['lat'] = $request->lat;
            $pekerjaan['lng'] = $request->long;

            if ($request->fotoAwal != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->fotoAwal->getClientOriginalName());
                $request->fotoAwal->storeAs('public/pekerjaan/', $path);
                $pekerjaan['foto_awal'] = $path;
            }
            if ($request->fotoSedang != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->fotoSedang->getClientOriginalName());
                $request->fotoSedang->storeAs('public/pekerjaan/', $path);
                $pekerjaan['foto_sedang'] = $path;
            }
            if ($request->fotoAkhir != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->fotoAkhir->getClientOriginalName());
                $request->fotoAkhir->storeAs('public/pekerjaan/', $path);
                $pekerjaan['foto_akhir'] = $path;
            }
            if ($request->fotoPegawai != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->fotoPegawai->getClientOriginalName());
                $request->fotoPegawai->storeAs('public/pekerjaan/', $path);
                $pekerjaan['foto_pegawai'] = $path;
            }
            if ($request->video != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->video->getClientOriginalName());
                $request->video->storeAs('public/pekerjaan/', $path);
                $pekerjaan['video'] = $path;
            }

            $row = DB::table('kemandoran')->select('id_pek')->orderByDesc('id_pek')->limit(1)->first();

            $pekerjaan['uptd_id'] = $this->userUptd == '' ? 0 : $this->userUptd;
            $pekerjaan['tglreal'] = date('Y-m-d H:i:s');
            $pekerjaan['is_deleted'] = 0;
            $nomor = intval(substr($row->id_pek, strlen('CK-'))) + 1;
            $pekerjaan['id_pek'] = 'CK-' . str_pad($nomor, 6, "0", STR_PAD_LEFT);

            DB::table('kemandoran')->insert($pekerjaan);
            // DB::table('utils_pekerjaan')->insert([
            //     'id_mandor' => $this->user->id,
            //     'id_pek' => $pekerjaan['id_pek']
            // ]);
            $this->response['status'] = 'success';
            $this->response['data']['message'] = 'Berhasil menambahkan pekerjaan';
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
                'idSup' => 'int|required',
                'namaPaket' => 'string|min:5',
                'idRuasJalan' => 'string',
                // 'idJenisPekerjaan' => 'int',
                'lokasi' => 'string|min:3',
                'lat' => 'string',
                'long' => 'string',
                'panjang' => 'string',
                // 'perkiraan_kuantitas' => 'required',
                'jumlah_pekerja' => 'required',

                // 'peralatan' => 'string',
                'fotoAwal' => 'file',
                'fotoSedang' => 'file',
                'fotoAkhir' => 'file',
                'video' => 'mimes:mp4'
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $pekerjaan = [];
            if ($request->tanggal)
                $pekerjaan['tanggal'] = $request->tanggal;
            $pekerjaan['sup'] = DB::table('utils_sup')->where('id', $request->idSup)->first()->name;
            if ($request->namaPaket)
                $pekerjaan['paket'] = $request->namaPaket;
            if ($request->idRuasJalan)
                $pekerjaan['ruas_jalan'] = DB::table('master_ruas_jalan')
                    ->where('id_ruas_jalan', $request->idRuasJalan)->first()->nama_ruas_jalan;
            // if ($request->idJenisPekerjaan)
            //     $pekerjaan['jenis_pekerjaan'] = DB::table('item_pekerjaan')->where('no', $request->idJenisPekerjaan)->first()->nama_item;

            $pekerjaan['jenis_pekerjaan'] = "Pemeliharaan";
            // if ($request->peralatan)
            //     $pekerjaan['peralatan'] = $request->peralatan;
            if ($request->panjang)
                $pekerjaan['panjang'] = $request->panjang;
            if ($request->lat)
                $pekerjaan['lat'] = $request->lat;
            if ($request->long)
                $pekerjaan['lng'] = $request->long;

            if ($request->fotoAwal != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->fotoAwal->getClientOriginalName());
                $request->fotoAwal->storeAs('public/pekerjaan/', $path);
                $pekerjaan['foto_awal'] = $path;
            }
            if ($request->fotoSedang != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->fotoSedang->getClientOriginalName());
                $request->fotoSedang->storeAs('public/pekerjaan/', $path);
                $pekerjaan['foto_sedang'] = $path;
            }
            if ($request->fotoAkhir != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->fotoAkhir->getClientOriginalName());
                $request->fotoAkhir->storeAs('public/pekerjaan/', $path);
                $pekerjaan['foto_akhir'] = $path;
            }
            if ($request->fotoPegawai != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->fotoPegawai->getClientOriginalName());
                $request->fotoPegawai->storeAs('public/pekerjaan/', $path);
                $pekerjaan['foto_pegawai'] = $path;
            }
            if ($request->video != null) {
                $path = Str::snake(date("YmdHis") . ' ' . $request->video->getClientOriginalName());
                $request->video->storeAs('public/pekerjaan/', $path);
                $pekerjaan['video'] = $path;
            }
            // $pekerjaan['perkiraan_kuantitas'] = $request->perkiraan_kuantitas;
            $pekerjaan['jumlah_pekerja'] = $request->jumlah_pekerja;

            $pekerjaan['uptd_id'] = $this->userUptd == '' ? 0 : $this->userUptd;
            $pekerjaan['updated_by'] = $this->user->id;
            DB::table('kemandoran')->where('id_pek', $id)->update($pekerjaan);
            $this->response['status'] = 'success';
            $this->response['data']['message'] = 'Berhasil merubah pekerjaan';
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
    public function destroy($id)
    {
        try {
            DB::table('kemandoran')->where('id_pek', $id)->update(['is_deleted' => 1]);

            $this->response['status'] = 'success';
            $this->response['data']['message'] = 'Berhasil menghapus Pekerjaan';
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getSUP()
    {
        try {
            $sup = DB::table('utils_sup')
                ->where('uptd_id', $this->userUptd)
                ->get();

            $this->response['status'] = 'success';
            $this->response['data']['sup'] = $sup;

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getRuasJalan()
    {
        try {
            $ruasJalan = DB::table('master_ruas_jalan')
                ->where('uptd_id', $this->userUptd)
                ->get();

            $this->response['status'] = 'success';
            $this->response['data']['ruas_jalan'] = $ruasJalan;

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getJenisPekerjaan()
    {
        try {
            $jenisPekerjaan = DB::table('utils_jenis_laporan')
                ->get();

            $this->response['status'] = 'success';
            $this->response['data']['jenis_pekerjaan'] = $jenisPekerjaan;

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
    public function getJenisKegiatan()
    {
        try {
            $jenisKegiatan = DB::table('utils_nama_kegiatan_pekerjaan')
                ->get();

            $this->response['status'] = 'success';
            $this->response['data']['jenis_kegiatan'] = $jenisKegiatan;

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
    
    public function sendEmail($data, $to_email, $to_name, $subject)
    {

        return Mail::send('mail.notifikasiStatusLapMandor', $data, function ($message) use ($to_name, $to_email, $subject) {
            $message->to($to_email, $to_name)->subject($subject);

            $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
        });
    }

    public function setSendEmail($name, $id, $mandor, $jenis_pekerjaan, $uptd, $sup_mail, $status_mail, $keterangan, $to_email, $to_name, $subject)
    {
        $temporari = [
            'name' => Str::title($name),
            'id_pek' => $id,
            'nama_mandor' => Str::title($mandor),
            'jenis_pekerjaan' => Str::title($jenis_pekerjaan),
            'uptd' => Str::upper($uptd),
            'sup' => $sup_mail,
            'status' => $status_mail,
            'keterangan' => $keterangan
        ];
        $this->sendEmail($temporari, $to_email, $to_name, $subject);
    }

    public function getNamaKegiatanPekerjaan()
    {
        try {
            $nama_kegiatan_pekerjaan = DB::table('utils_nama_kegiatan_pekerjaan')->get();

            $this->response['status'] = 'success';
            $this->response['data']['nama_kegiatan_pekerjaan'] = $nama_kegiatan_pekerjaan;

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getDetailStatus($id)
    {
        try {
            $detail_status = DB::table('kemandoran_detail_status')->where('id_pek', $id)->get();
            $this->response['status'] = 'success';
            $this->response['data']['detail_status'] = $detail_status;

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
}
