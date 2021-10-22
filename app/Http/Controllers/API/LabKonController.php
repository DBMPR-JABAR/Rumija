<?php

namespace App\Http\Controllers\API;

use App\Exports\KuesionerLabkonExport;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class LabKonController extends Controller
{
    public function __construct()
    {
        $this->email_list_notifications =
            DB::table('users')
            ->leftJoin('master_grant_role_aplikasi', 'master_grant_role_aplikasi.internal_role_id', 'users.internal_role_id')
            ->where('master_grant_role_aplikasi.menu', 'Email Notifikasi Permohonan')
            ->get();
    }

    public function daftar_pemohon()
    {
        try {
            $daftar_pemohon = DB::table('labkon_master_pemohon')->where('created_by', auth('api')->user()->id)
            // ->orderBy('id_pemohon', 'desc')
            ->get();
            if (hasAccess(auth('api')->user()->internal_role_id, "Semua Data Laboratorium Konstruksi", "View"))
                $daftar_pemohon = DB::table('labkon_master_pemohon')
                // ->orderBy('id_pemohon', 'desc')
                ->get();
            $this->response['status'] = 'success';
            $this->response['daftar_pemohon'] = $daftar_pemohon;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function show_pemohon($id)
    {
        try {
            $pemohon = DB::table('labkon_master_pemohon')->where('id_pemohon', $id)->first();
            $this->response['status'] = 'success';
            $this->response['pemohon'] = $pemohon;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function create_pemohon(Request $request)
    {
        try {
            $default = [
                'alamat_penanggung_jawab' => 'required|string',
                'nama_penanggung_jawab' => 'required|string',
                'email_penanggung_jawab' => 'required|email',
                'no_telp_penanggung_jawab' => 'required|string',
                'nip' => 'required|string'
            ];
            $masyarakat = array_merge($default, [
                'alamat_perusahaan' => 'required|string',
                'nama_perusahaan' => 'required|string',
                'email_perusahaan' => 'required|email',
                'no_telp_perusahaan' => 'required|string',
            ]);
            $internal = array_merge($default, [
                'uptd_id' => 'required|int'
            ]);
            $validator = Validator::make($request->all(), auth('api')->user()->role == 'internal' ? $internal : $masyarakat);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $pemohon = $request->all();
            // $pemohon['status'] = 1;
            $pemohon['created_at'] = Carbon::now();
            $pemohon['created_by'] = auth('api')->user()->id;

            DB::table('labkon_master_pemohon')->insert($pemohon);
            $this->response['status'] = 'success';
            $this->response['message'] = 'Berhasil menambahkan data pemohon';
            $this->response['data_pemohon'] = $pemohon;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function edit_pemohon(Request $request, $id)
    {
        try {
            $default = [
                'alamat_penanggung_jawab' => 'required|string',
                'nama_penanggung_jawab' => 'required|string',
                'email_penanggung_jawab' => 'required|email',
                'no_telp_penanggung_jawab' => 'required|string',
                'nip' => 'required|string'
            ];
            $masyarakat = array_merge($default, [
                'alamat_perusahaan' => 'required|string',
                'nama_perusahaan' => 'required|string',
                'email_perusahaan' => 'required|email',
                'no_telp_perusahaan' => 'required|string',
            ]);
            $internal = array_merge($default, [
                'uptd_id' => 'required|int'
            ]);
            $validator = Validator::make($request->all(), auth('api')->user()->role == 'internal' ? $internal : $masyarakat);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $pemohon = $request->all();
            $pemohon['updated_at'] = Carbon::now();
            $pemohon['updated_by'] = auth('api')->user()->id;

            DB::table('labkon_master_pemohon')->where('id_pemohon', $id)->update($pemohon);
            $this->response['status'] = 'success';
            $this->response['message'] = 'Berhasil memperbaharui data pemohon';
            $this->response['data_pemohon'] = $pemohon;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function delete_pemohon($id)
    {
        try {
            $pemohon['status'] = 'nonaktif';
            DB::table('labkon_master_pemohon')->where('id_pemohon', $id)->update($pemohon);
            $this->response['status'] = 'success';
            $this->response['message'] = 'Berhasil menghapus data pemohon';
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function nama_pengujian()
    {
        try {
            $nama_uji_labkon = DB::table('bahan_uji_detail')->leftJoin('bahan_uji', 'bahan_uji.id', '=', 'bahan_uji_detail.id_bahan_uji')->select('bahan_uji.nama as nama_bahan', 'bahan_uji_detail.*', 'bahan_uji.status as status_bahan')->get();
            $this->response['status'] = 'success';
            $this->response['data']['nama_pengujian'] = $nama_uji_labkon;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function metode_pengujian()
    {
        try {
            $metode_pengujian_labkon = DB::table('labkon_master_metode_pengujian')->leftJoin('bahan_uji', 'bahan_uji.id', '=', 'labkon_master_metode_pengujian.id_bahan_uji')->select('labkon_master_metode_pengujian.*', 'bahan_uji.nama as nama_bahan')->get();
            $this->response['status'] = 'success';
            $this->response['data']['metode_pengujian'] = $metode_pengujian_labkon;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function daftar_permohonan()
    {
        try {
            $daftar_permohonan = DB::table('labkon_trans_progress')
                ->leftJoin(
                    'labkon_master_pemohon',
                    'labkon_master_pemohon.id_pemohon',
                    'labkon_trans_progress.id_pemohon',
                )
                ->leftJoin(
                    'labkon_persyaratan_permohonan',
                    'labkon_persyaratan_permohonan.id_permohonan',
                    'labkon_trans_progress.id_pemohon',
                )
                ->where(
                    'labkon_trans_progress.created_by',
                    auth('api')->user()->id
                )
                ->select(
                    'labkon_trans_progress.*',
                    'labkon_master_pemohon.nama_penanggung_jawab',
                    'labkon_master_pemohon.email_penanggung_jawab',
                    'labkon_master_pemohon.no_telp_penanggung_jawab',
                    'labkon_master_pemohon.nip',
                    'labkon_master_pemohon.uptd_id',
                    'labkon_persyaratan_permohonan.formulir_permohonan',
                    'labkon_persyaratan_permohonan.surat_permohonan'
                )
                // ->orderBy('labkon_trans_progress.id_permohonan', 'desc')
                ->get();
            if (hasAccess(auth('api')->user()->internal_role_id, "Semua Data Laboratorium Konstruksi", "View"))
                $daftar_permohonan = DB::table('labkon_trans_progress')
                    ->leftJoin(
                        'labkon_master_pemohon',
                        'labkon_master_pemohon.id_pemohon',
                        'labkon_trans_progress.id_pemohon'
                    )
                    ->leftJoin(
                        'labkon_persyaratan_permohonan',
                        'labkon_persyaratan_permohonan.id_permohonan',
                        'labkon_trans_progress.id_permohonan',
                    )
                    ->select(
                        'labkon_trans_progress.*',
                        'labkon_master_pemohon.nama_penanggung_jawab',
                        'labkon_master_pemohon.email_penanggung_jawab',
                        'labkon_master_pemohon.no_telp_penanggung_jawab',
                        'labkon_master_pemohon.nip',
                        'labkon_master_pemohon.uptd_id',
                        'labkon_persyaratan_permohonan.formulir_permohonan',
                        'labkon_persyaratan_permohonan.surat_permohonan'
                    )
                    // ->orderBy('labkon_trans_progress.id_permohonan', 'desc')
                    ->get();
            $this->response['status'] = 'success';
            $this->response['data']['permohonan'] = $daftar_permohonan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function create_permohonan(Request $request)
    {
        try {
            $form = [
                'id_pemohon' => 'required|int',
                // 'jumlah_bahan_uji' => 'required|string',
                'bahan_uji' => 'required|string',
                // 'metode_pengujian' => 'required|string',
                'latitude' => 'required|string',
                'longitude' => 'required|string',
                'tanggal_pengambilan_sampel' => 'required|date',
                'lokasi_pengambilan_sampel' => 'required|string'
            ];
            $validator = Validator::make($request->all(), $form);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $permohonan = $request->all();
            $permohonan['status'] = 1;
            // Tanggal otomatis
            // $permohonan['created_at'] = Carbon::now();
            $permohonan['created_by'] = auth('api')->user()->id;

            // Nomor otomatis
            // $now = Carbon::now();
            // $month = '';
            // switch ($now->month) {
            //     case 1:
            //         $month = 'I';
            //         break;
            //     case 2:
            //         $month = 'II';
            //         break;
            //     case 3:
            //         $month = 'III';
            //         break;
            //     case 4:
            //         $month = 'IV';
            //         break;
            //     case 5:
            //         $month = 'V';
            //         break;
            //     case 6:
            //         $month = 'VI';
            //         break;
            //     case 7:
            //         $month = 'VII';
            //         break;
            //     case 8:
            //         $month = 'VIII';
            //         break;
            //     case 9:
            //         $month = 'IX';
            //         break;
            //     case 10:
            //         $month = 'X';
            //         break;
            //     case 11:
            //         $month = 'XI';
            //         break;
            //     default:
            //         $month = 'XII';
            //         break;
            // }
            // $row = DB::table('labkon_trans_progress')->select('id_permohonan')->orderByDesc('id_permohonan')->limit(1)->first();
            // if ($row) {
            //     $nomor = intval(explode('-', $row->id_permohonan)[0]);
            //     for ($i = 1; $i <= $nomor; $i++) {
            //         $row = DB::table('labkon_trans_progress')->select('id_permohonan')->where('id_permohonan', $i . '-' . $month . '-LABKON-' . $now->year)->count();
            //         if ($row == 0) {
            //             $nomor = $i;
            //         } else $nomor = $nomor + 1;
            //     }
            // } else $nomor = 1;
            // $permohonan['id_permohonan'] = $nomor . '-' .  $month . '-LABKON-' . $now->year;

            DB::table('labkon_trans_progress')->insert($permohonan);
            $historis['id_permohonan'] = $permohonan['id_permohonan'];
            $historis['type_keterangan'] = 'Pendaftaran';
            $historis['keterangan'] = 'Melakukan pendaftaran permohonan';
            // $historis['created_at'] = Carbon::now();
            $historis['created_at'] = $request->created_at;
            $historis['created_by'] = auth('api')->user()->id;
            $historis['json'] = json_encode($permohonan);
            DB::table('labkon_utils_historis')->insert($historis);
            $this->response['status'] = 'success';
            $this->response['message'] = 'Berhasil menambahkan data permohonan';
            $this->response['data']['permohonan'] = $permohonan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function show_permohonan($id)
    {
        try {
            $permohonan = DB::table('labkon_trans_progress')->where('id_permohonan', $id)->first();
            $this->response['status'] = 'success';
            $this->response['data']['permohonan'] = $permohonan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function edit_permohonan(Request $request, $id)
    {
        try {
            $form = [
                'id_pemohon' => 'required|int',
                // 'jumlah_bahan_uji' => 'required|string',
                'bahan_uji' => 'required|string',
                // 'metode_pengujian' => 'required|string',
                'latitude' => 'required|string',
                'longitude' => 'required|string',
                'tanggal_pengambilan_sampel' => 'required|date',
                'lokasi_pengambilan_sampel' => 'required|string'
            ];
            $validator = Validator::make($request->all(), $form);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $permohonan = $request->all();
            $permohonan['updated_at'] = Carbon::now();
            $permohonan['updated_by'] = auth('api')->user()->id;

            DB::table('labkon_trans_progress')->where('id_permohonan', $id)->update($permohonan);

            $historis['id_permohonan'] = $id;
            $historis['type_keterangan'] = 'Perubahan Data Permohonan';
            $historis['keterangan'] = 'Merubahan data permohonan';
            // $historis['created_at'] = Carbon::now();
            $historis['created_at'] = $request->created_at;
            $historis['created_by'] = auth('api')->user()->id;
            $historis['json'] = json_encode($permohonan);
            DB::table('labkon_utils_historis')->insert($historis);
            $this->response['status'] = 'success';
            $this->response['message'] = 'Berhasil memperbaharui data permohonan';
            $this->response['data']['permohonan'] = $permohonan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function delete_permohonan($id)
    {
        try {
            $permohonan = DB::table('labkon_trans_progress')->where('id_permohonan', $id);
            $historis = DB::table('labkon_utils_historis')->where('id_permohonan', $id);
            $pengkajian = DB::table('labkon_pengkajian_permohonan')->where('id_permohonan', $id);
            $persyaratan = DB::table('labkon_persyaratan_permohonan')->where('id_permohonan', $id);
            $data = [
                'permohonan' => $permohonan->get(),
                'pengkajian' => $pengkajian->get(),
                'persyaratan' => $persyaratan->get(),
                'historis' => $historis->get()
            ];
            $historis_deleted['all_data'] = json_encode($data);
            if (DB::table('labkon_utils_historis_deleted')->insert($historis_deleted)) {
                foreach ($persyaratan->get() as $data) {
                    if (File::exists('public/' . $data->surat_permohonan)) {
                        Storage::move('public/' . $data->surat_permohonan, 'public/deleted/' . $data->surat_permohonan);
                        File::delete('public/' . $data->surat_permohonan);
                    }
                    if (File::exists('public/' . $data->formulir_permohonan)) {
                        Storage::move('public/' . $data->formulir_permohonan, 'public/deleted/' . $data->formulir_permohonan);
                        File::delete('public/' . $data->formulir_permohonan);
                    }
                }
                $permohonan->delete();
                $pengkajian->delete();
                $persyaratan->delete();
                $historis->delete();
            }
            $this->response['status'] = 'success';
            $this->response['message'] = 'Berhasil menghapus data permohonan';
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    private function send_email($to_email, $to_name, $subject, $form_email, $from_name, $view, $data)
    {
        Mail::send($view, $data, function ($message) use ($to_name, $to_email, $subject, $form_email, $from_name) {
            $message->to($to_email, $to_name)->subject($subject);
            $message->from($form_email, $from_name);
        });
    }

    public function upload_dokumen_persyaratan_permohonan(Request $request, $id)
    {
        try {
            $form = [
                'surat_permohonan' => 'required|file',
                'formulir_permohonan' => 'required|file'
            ];
            $validator = Validator::make($request->all(), $form);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $persyaratan_permohonan['id_permohonan'] = $id;
            if ($request->surat_permohonan != null) {
                $name = 'Surat_Permohonan_' . $id . '.' . $request->surat_permohonan->getClientOriginalExtension();
                $path = 'persyaratan_permohonan_labkon/' . $name;
                if (File::exists('public/' . $path)) File::delete('public/' . $path);
                $request->surat_permohonan->storeAs('public/', $path);
                $persyaratan_permohonan['surat_permohonan'] = $path;
            }
            if ($request->formulir_permohonan != null) {
                $name = 'Formulir_Permohonan_' . $id . '.' . $request->formulir_permohonan->getClientOriginalExtension();
                $path = 'persyaratan_permohonan_labkon/' . $name;
                if (File::exists('public/' . $path)) File::delete('public/' . $path);
                $request->formulir_permohonan->storeAs('public/', $path);
                $persyaratan_permohonan['formulir_permohonan'] = $path;
            }

            $persyaratan = DB::table('labkon_persyaratan_permohonan');
            $update = $persyaratan->where('id_permohonan', $id);
            if ($update->count() > 0) {
                $persyaratan_permohonan['updated_at'] = Carbon::now();
                $persyaratan_permohonan['updated_by'] = auth('api')->user()->id;
                $update->update($persyaratan_permohonan);
            } else {
                $persyaratan_permohonan['created_at'] = Carbon::now();
                $persyaratan_permohonan['created_by'] = auth('api')->user()->id;
                $persyaratan->insert($persyaratan_permohonan);
            }
            $proggress['status'] = 2;
            DB::table('labkon_trans_progress')->where('id_permohonan', $id)->update($proggress);

            $historis['id_permohonan'] = $id;
            $historis['type_keterangan'] = 'Upload Dokumen Persyaratan Permohonan';
            $historis['keterangan'] = 'Melakukan upload dokumen persyaratan permohonan';
            $historis['created_at'] = Carbon::now();
            $historis['created_by'] = auth('api')->user()->id;
            $historis['json'] = json_encode($persyaratan_permohonan);
            DB::table('labkon_utils_historis')->insert($historis);

            $subject = 'Permohonan Pengujian Baru';
            $view = 'mail.permohonan_labkon_baru';
            foreach ($this->email_list_notifications as $user) {
                $data = [
                    'penguji_name' => $user->name,
                    'pemohon_name' => auth('api')->user()->name,
                    'no_permohonan' => str_replace('-', '/', $id)
                ];
                $this->send_email($user->email, $user->name, $subject, auth('api')->user()->email, auth('api')->user()->name, $view, $data);
            }
            $this->response['status'] = 'success';
            $this->response['message'] = 'Berhasil mengunggah persyaratan permohonan';
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error' . $th;
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function upload_dokumen_hasil_pengujian(Request $request, $id)
    {
        try {
            $form = [
                'dokumen_hasil_pengujian' => 'required|file',
            ];
            $validator = Validator::make($request->all(), $form);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $hasil_pengujian['id_permohonan'] = $id;
            if ($request->dokumen_hasil_pengujian != null) {
                $name = 'Hasil_Uji_' . uniqid(date('mdYHis'), true) . $id . '.' . $request->dokumen_hasil_pengujian->getClientOriginalExtension();
                $path = 'hasil_uji_labkon/' . $name;
                if (File::exists('public/' . $path)) File::delete('public/' . $path);
                $request->dokumen_hasil_pengujian->storeAs('public/', $path);
                $hasil_pengujian['dokumen_hasil_pengujian'] = $path;
            }

            $hasil_pengujian_tb = DB::table('labkon_dokumen_hasil_pengujian');
            $update = $hasil_pengujian_tb->where('id_permohonan', $id);
            if ($update->count() > 0) {
                $hasil_pengujian['updated_at'] = Carbon::now();
                $hasil_pengujian['updated_by'] = auth('api')->user()->id;
                $update->update($hasil_pengujian);
            } else {
                $hasil_pengujian['created_at'] = Carbon::now();
                $hasil_pengujian['created_by'] = auth('api')->user()->id;
                $hasil_pengujian_tb->insert($hasil_pengujian);
            }

            $historis['id_permohonan'] = $id;
            $historis['type_keterangan'] = 'Upload Dokumen Hasil Uji';
            $historis['keterangan'] = 'Melakukan upload dokumen hasil uji';
            $historis['created_at'] = Carbon::now();
            $historis['created_by'] = auth('api')->user()->id;
            $historis['json'] = json_encode($hasil_pengujian);
            DB::table('labkon_utils_historis')->insert($historis);

            $this->response['status'] = 'success';
            $this->response['message'] = 'Berhasil mengunggah dokumen hasil uji';
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error' . $th;
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function cetak_formulir_data($id)
    {
        try {
            $permohonan = DB::table('labkon_trans_progress')->select('labkon_trans_progress.*', 'labkon_master_pemohon.nama_penanggung_jawab', 'labkon_master_pemohon.nama_perusahaan', 'labkon_master_pemohon.alamat_perusahaan', 'labkon_master_pemohon.alamat_penanggung_jawab')->leftJoin('labkon_master_pemohon', 'labkon_master_pemohon.id_pemohon', 'labkon_trans_progress.id_pemohon')->where('id_permohonan', $id)->first();
            $this->response['status'] = 'success';
            $this->response['data']['permohonan'] = $permohonan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function dokumen_persyaratan_permohonan($id)
    {
        try {
            $dokumen_persyaratan = DB::table('labkon_persyaratan_permohonan')->where('id_permohonan', $id)->first();
            $this->response['status'] = 'success';
            $this->response['data']['dokumen_persyaratan'] = $dokumen_persyaratan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function dokumen_hasil_pengujian($id)
    {
        try {
            $dokumen_hasil_pengujian = DB::table('labkon_dokumen_hasil_pengujian');
            if (hasAccess(auth('api')->user()->internal_role_id, "Semua Data Laboratorium Konstruksi", "View")) {
                $dokumen_hasil_pengujian = $dokumen_hasil_pengujian->where('id_permohonan', $id)->first();
            } else {
                $dokumen_hasil_pengujian = $dokumen_hasil_pengujian->leftJoin('labkon_trans_progress', 'labkon_trans_progres.id_permohonan', 'labkon_dokumen_hasil_pengujian.id_permohonan')->leftJoin('labkon_master_pemohon.id_pemohon', 'labkon_trans_progres.id_pemohon')->where('labkon_master_pemohon.created_by', auth('api')->user->id)->where('labkon_dokumen_hasil_pengujian.id_permohonan', $id)->first();
            }
            if ($dokumen_hasil_pengujian)
                return response()->download(public_path('storage/' . $dokumen_hasil_pengujian->dokumen_hasil_pengujian), $dokumen_hasil_pengujian->id_permohonan, ['Content-Type' => 'application/pdf']);
            else {
                $this->response['status'] = 'success';
                $this->response['message'] = 'Kenapa anda coba-coba mendownload hasil pengujian orang lain :(';
            }
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function update_status_permohonan(Request $request, $id)
    {
        try {
            $form = [
                'status' => 'in:0,1,2,3,4,5',
            ];
            $validator = Validator::make($request->all(), $form);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $permohonan = $request->all();
            $permohonan['updated_at'] = Carbon::now();
            $permohonan['updated_by'] = auth('api')->user()->id;

            DB::table('labkon_trans_progress')->where('id_permohonan', $id)->update($permohonan);
            $historis['id_permohonan'] = $id;
            $historis['type_keterangan'] = 'Perubahan Status Permohonan';
            $historis['keterangan'] = $request->status;
            $historis['created_at'] = Carbon::now();
            $historis['created_by'] = auth('api')->user()->id;
            DB::table('labkon_utils_historis')->insert($historis);
            $this->response['status'] = 'success';
            $this->response['message'] = 'Berhasil memperbaharui status permohonan';
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function pengkajian_permohonan(Request $request, $id)
    {
        try {
            $form = [
                'lingkup' => 'boolean',
                'jenis_sampel' => 'boolean',
                'metode_pengujian' => 'boolean',
                'personil' => 'boolean',
                'kondisi_sampel' => 'in:Baik,Cacat,Kurang',
                'catatan' => 'string',
                'status' => 'in:0,1,2,3,4,5'
            ];
            $validator = Validator::make($request->all(), $form);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $pengkajian_permohonan = $request->except('status');
            $pengkajian_permohonan['updated_at'] = Carbon::now();
            $pengkajian_permohonan['updated_by'] = auth('api')->user()->id;

            $pengkajian = DB::table('labkon_pengkajian_permohonan');
            $update = $pengkajian->where('id_permohonan', $id);
            $count = $update->count();
            $message = '';
            if ($count > 0) {
                $update->update($pengkajian_permohonan);
                $message = 'Berhasil memperbaharui data pengkajian permohonan';
            } else {
                $pengkajian_permohonan['id_permohonan'] = $id;
                $pengkajian_permohonan['created_at'] = Carbon::now();
                $pengkajian_permohonan['created_by'] = auth('api')->user()->id;
                $pengkajian->insert($pengkajian_permohonan);
                $message = 'Berhasil menambah data pengkajian permohonan';
            }

            $permohonan['status'] = $request->status;
            $permohonan['updated_at'] = Carbon::now();
            $permohonan['updated_by'] = auth('api')->user()->id;
            DB::table('labkon_trans_progress')->where('id_permohonan', $id)->update($permohonan);

            $historis['id_permohonan'] = $id;
            $historis['type_keterangan'] = 'Pengkajian Permohonan';
            $historis['created_at'] = Carbon::now();
            $historis['created_by'] = auth('api')->user()->id;
            $historis['json'] = json_encode($pengkajian_permohonan);

            $permohonan = DB::table('labkon_trans_progress')->where('id_permohonan', $id)->first();
            $pemohon = DB::table('labkon_master_pemohon')->where('id_pemohon', $permohonan->id_pemohon)->first();
            $created_by = DB::table('users')->where('id', $permohonan->created_by)->first();
            if ($request->status == 0) {
                $historis['keterangan'] = 'Menunda permohonan dengan catatan : ' . $request->catatan;
                $subject = 'Status Permohonan Pengujian Laboratorium Bahan Kontruksi ' . str_replace('-', '/', $id);
                $view = 'mail.reject_pengkajian_permohonan_labkon';
                $data = [
                    'penguji_name' => auth('api')->user()->name,
                    'pemohon_name' => $pemohon->nama_penanggung_jawab,
                    'created_by' => $created_by->name,
                    'no_permohonan' => str_replace('-', '/', $id),
                    'catatan' => $request->catatan
                ];
                $this->send_email($created_by->email, $created_by->name, $subject, auth('api')->user()->email, auth('api')->user()->name, $view, $data);
            } else {
                $historis['keterangan'] = 'Melanjutkan permohonan dengan catatan : ' . $request->catatan;
                $subject = 'Status Permohonan Pengujian Laboratorium Bahan Kontruksi ' . str_replace('-', '/', $id);
                $view = 'mail.acc_pengkajian_permohonan_labkon';
                $data = [
                    'penguji_name' => auth('api')->user()->name,
                    'pemohon_name' => $pemohon->nama_penanggung_jawab,
                    'created_by' => $created_by->name,
                    'no_permohonan' => str_replace('-', '/', $id),
                    'catatan' => $request->catatan
                ];
                $this->send_email($created_by->email, $created_by->name, $subject, auth('api')->user()->email, auth('api')->user()->name, $view, $data);
            }
            DB::table('labkon_utils_historis')->insert($historis);

            $this->response['status'] = 'success';
            $this->response['message'] = $message;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }


    public function riwayat_permohonan($id)
    {
        try {
            $riwayat_permohonan = DB::table('labkon_utils_historis')
                ->where('id_permohonan', $id)
                ->orderBy('created_at')->get();
            $this->response['status'] = 'success';
            $this->response['data']['riwayat_permohonan'] = $riwayat_permohonan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function catatan_status_progress(Request $request, $id)
    {
        try {
            $form = [
                'status' => 'required|in:0,1,2,3,4,5,6',
                'type_keterangan' => 'required|string'
            ];
            $validator = Validator::make($request->all(), $form);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $permohonan['status'] = $request->status;
            $permohonan['updated_at'] = Carbon::now();
            $permohonan['updated_by'] = auth('api')->user()->id;
            DB::table('labkon_trans_progress')->where('id_permohonan', $id)->update($permohonan);

            $historis = $request->except('status');
            $historis['id_permohonan'] = $id;
            $historis['created_at'] = Carbon::now();
            $historis['created_by'] = auth('api')->user()->id;
            DB::table('labkon_utils_historis')->insert($historis);

            $permohonan = DB::table('labkon_trans_progress')->where('id_permohonan', $id)->first();
            $pemohon = DB::table('labkon_master_pemohon')->where('id_pemohon', $permohonan->id_pemohon)->first();
            $created_by = DB::table('users')->where('id', $permohonan->created_by)->first();
            if ($request->status == 3) {
                $subject = 'Status Permohonan Pengujian Laboratorium Bahan Kontruksi ' . str_replace('-', '/', $id);
                $view = 'mail.status_progress_on_progres';
                $data = [
                    'penguji_name' => auth('api')->user()->name,
                    'pemohon_name' => $pemohon->nama_penanggung_jawab,
                    'created_by' => $created_by->name,
                    'no_permohonan' => str_replace('-', '/', $id),
                    'catatan' => $request->keterangan
                ];
                $this->send_email($created_by->email, $created_by->name, $subject, auth('api')->user()->email, auth('api')->user()->name, $view, $data);
            } else {
                $subject = 'Status Permohonan Pengujian Laboratorium Bahan Kontruksi ' . str_replace('-', '/', $id);
                $view = 'mail.status_progress_selesai';
                $data = [
                    'penguji_name' => auth('api')->user()->name,
                    'pemohon_name' => $pemohon->nama_penanggung_jawab,
                    'created_by' => $created_by->name,
                    'no_permohonan' => str_replace('-', '/', $id),
                    'catatan' => $request->keterangan
                ];
                $this->send_email($created_by->email, $created_by->name, $subject, auth('api')->user()->email, auth('api')->user()->name, $view, $data);
            }

            $this->response['status'] = 'success';
            $this->response['message'] = 'berhasil menambahkan catatan status progress';
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function catatan_status_progress_last($id)
    {
        try {
            $last_status = DB::table('labkon_utils_historis')->where('id_permohonan', $id)->orderByDesc('created_at')->first();
            $this->response['status'] = 'success';
            $this->response['data']['last_status'] = $last_status;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function create_questioner(Request $request, $id)
    {
        try {
            $form = [
                'saran' => 'string',
                'keluhan' => 'string',
                'questions' => 'required|string'
            ];
            $validator = Validator::make($request->all(), $form);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $questioner = $request->all();
            $questioner['id_permohonan'] = $id;
            $questioner['created_at'] = Carbon::now();
            $questioner['created_by'] = auth('api')->user()->id;

            $tb_questioner = DB::table('labkon_utils_questioner');
            $tb_where = $tb_questioner->where('id_permohonan', $id);
            $count = $tb_where->count();
            if ($count > 0) $tb_where->update($questioner);
            else $tb_questioner->insert($questioner);

            $permohonan['status'] = 5;
            $permohonan['updated_at'] = Carbon::now();
            $permohonan['updated_by'] = auth('api')->user()->id;
            DB::table('labkon_trans_progress')->where('id_permohonan', $id)->update($permohonan);

            $historis['type_keterangan'] = 'Kuesioner';
            $historis['keterangan'] = 'Mengisi kuesioner.';
            $historis['id_permohonan'] = $id;
            $historis['json'] = json_encode($questioner);
            $historis['created_at'] = Carbon::now();
            $historis['created_by'] = auth('api')->user()->id;
            DB::table('labkon_utils_historis')->insert($historis);

            $this->response['status'] = 'success';
            $this->response['message'] = 'berhasil menambahkan catatan status progress';
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function formulir_pengaduan_data_exits($id)
    {
        try {
            $pengaduan = DB::table('labkon_utils_historis')
                ->where('id_permohonan', $id)
                ->where('type_keterangan', 'Cetak Formulir Pengaduan')
                ->orderByDesc('created_at')
                ->first();
            $this->response['status'] = 'success';
            $this->response['data']['pengaduan'] = $pengaduan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function tambah_nama_pengujian(Request $request)
    {
        try {
            $form = [
                'nama_pengujian' => 'required|string',
                'id_bahan_uji' => 'required|integer'
            ];
            $validator = Validator::make($request->all(), $form);

            if ($validator->fails()) {
                $this->response['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $exits = DB::table('bahan_uji_detail')->where('id_bahan_uji', $request->id_bahan_uji)
                ->where('nama_pengujian', $request->nama_pengujian);
            if ($exits->count() === 0) {
                $nama_uji_labkon['nama_pengujian'] = $request->nama_pengujian;
                $nama_uji_labkon['status'] = 'nonaktif';
                $nama_uji_labkon['id_bahan_uji'] = $request->id_bahan_uji;
                $nama_uji_labkon['created_by'] = auth('api')->user()->id;
                $nama_uji_labkon['created_at'] = Carbon::now();
                $nama_uji_labkon['show_at'] = auth('api')->user()->id;
                $id = DB::table('bahan_uji_detail')->insertGetId($nama_uji_labkon);
                $nama_pengujian = DB::table('bahan_uji_detail')->where('id', $id)->first();
            } else {
                $nama_pengujian = $exits->first();
                $id = [auth('api')->user()->id];
                $show_at = explode(',', $nama_pengujian->show_at);
                $show_at_update = array_merge($id, $show_at);
                $show_at_update = implode(',', array_unique($show_at_update));
                $data['show_at'] = $show_at_update;
                $exits->update($data);
            }
            $this->response['status'] = 'success';
            $this->response['message'] = "Berhasil menambahkan data";
            $this->response['data']['nama_pengujian'] = $nama_pengujian;
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $e->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function kuesioner($id)
    {
        $resolveAnswer = function ($question) {
            return [
                'pertanyaan' => $question->pertanyaan,
                'value' => (int)$question->value ?
                    $question->value . ' dari 5' :
                    '0 dari 5'
            ];
        };

        try {
            $kuesioner = DB::table('labkon_utils_questioner')
                ->where('id_permohonan', $id)
                ->first();
            $this->response['status'] = 'success';
            $kuesionerFormated['id_permohonan'] = $kuesioner->id_permohonan;
            $kuesionerFormated['saran'] = $kuesioner->saran;
            $kuesionerFormated['keluhan'] = $kuesioner->keluhan;
            $kuesionerFormated['questions'] = json_decode($kuesioner->questions);
            $data = [
                ['pertanyaan' => 'Waktu Pengisian', 'value' => $kuesioner->created_at],
                ['pertanyaan' => 'Saran', 'value' => $kuesioner->saran],
                ['pertanyaan' => 'Keluhan', 'value' => $kuesioner->keluhan]
            ];
            $questions = array_map($resolveAnswer, json_decode($kuesioner->questions));
            $data = array_merge($data, $questions);
            return Excel::download(new KuesionerLabkonExport($data, $id), 'kuesioner_' . $id . '.xlsx');

            $this->response['data']['quesioner'] = $data;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
    }
}
