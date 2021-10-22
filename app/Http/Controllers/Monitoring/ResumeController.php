<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResumeController extends Controller
{
    public function pekerjaan(Request $request)
    {
        if (Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role, 'Mandor') || str_contains(Auth::user()->internalRole->role, 'Pengamat') || str_contains(Auth::user()->internalRole->role, 'Kepala Satuan Unit Pemeliharaan')) {
            if (!Auth::user()->sup_id || !Auth::user()->internalRole->uptd) {
                $color = "danger";
                $msg = "Lengkapi Data Terlebih dahulu";
                if (Auth::user()->internalRole->uptd == null)
                    $msg = "Hubungi admin untuk melengkapi data jabatan";

                return redirect(url('admin/profile', Auth::user()->id))->with(compact('color', 'msg'));
            }
        }
        $nama_kegiatan_pekerjaan = DB::table('utils_nama_kegiatan_pekerjaan')->get();
        $pekerjaan = DB::table('kemandoran');

        $pekerjaan = $pekerjaan->leftJoin('master_ruas_jalan', 'master_ruas_jalan.id', '=', 'kemandoran.ruas_jalan')->select('kemandoran.*', 'master_ruas_jalan.nama_ruas_jalan');

        if (Auth::user() && Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $pekerjaan = $pekerjaan->where('kemandoran.uptd_id', $uptd_id);
            if (str_contains(Auth::user()->internalRole->role, 'Mandor')) {
                $pekerjaan = $pekerjaan->where('kemandoran.user_id', Auth::user()->id);
            } else if (Auth::user()->sup_id)
                $pekerjaan = $pekerjaan->where('kemandoran.sup_id', Auth::user()->sup_id);
        }

        $now = Carbon::now()->toDateString();
        // dd($now);

        $filter = null;
        if ($request->filter) {
            $filter = (object)[
                "tanggal_awal" => $request->tanggal_awal,
                "tanggal_akhir" => $request->tanggal_akhir,
                "uptd" => $request->uptd,
                "sup" => $request->sup
            ];
            $pekerjaan = $pekerjaan->whereRaw("tanggal BETWEEN '" . $request->tanggal_awal . "' AND '" . $request->tanggal_akhir . "'");
            $pekerjaan = $pekerjaan->where('kemandoran.uptd_id', $request->uptd);
            if ($request->sup != 'ALL') $pekerjaan = $pekerjaan->where('kemandoran.sup_id', $request->sup);
        } else {
            $pekerjaan = $pekerjaan->whereRaw("tanggal BETWEEN '" . $now . "' AND '" . $now . "'");
        }
        $pekerjaan = $pekerjaan->where('is_deleted', 0)->latest('tglreal')->get();

        foreach ($pekerjaan as $no => $data) {

            $data->status = "";
            $data->jenis_pekerjaan = DB::table('utils_jenis_laporan')->where('id', $data->jenis_pekerjaan)->pluck('name')->first();
            $detail_adjustment = DB::table('kemandoran_detail_status')->where('id_pek', $data->id_pek);
            $input_material = $this->cekMaterial($data->id_pek);
            if ($input_material) {
                $tempuser = DB::table('users')
                    ->leftJoin('user_role', 'users.internal_role_id', '=', 'user_role.id')->where('users.id', $data->user_id);
                if ($tempuser->exists()) {
                    $tempuser = $tempuser->first();
                    $data->status = $tempuser;
                    $data->status->status = "";
                }
            }
            $data->input_material = $input_material;
            $data->keterangan_status_lap = $detail_adjustment->exists();
            if ($detail_adjustment->exists()) {
                $detail_adjustment = $detail_adjustment
                    ->leftJoin('users', 'users.id', '=', 'kemandoran_detail_status.adjustment_user_id')
                    ->leftJoin('user_role', 'users.internal_role_id', '=', 'user_role.id');

                if ($detail_adjustment->count() > 1) {
                    $detail_adjustment = $detail_adjustment->get();
                    foreach ($detail_adjustment as $num => $data1) {
                        $temp = $data1;
                    }
                    $data->status = $temp;
                } else {
                    $detail_adjustment = $detail_adjustment->first();
                    $data->status = $detail_adjustment;
                }
                $temp = explode(" - ", $data->status->role);
                $data->status->jabatan = $temp[0];
            }
        }
        $ruas_jalan = DB::table('master_ruas_jalan');
        if (Auth::user()->internalRole->uptd) {
            $ruas_jalan = $ruas_jalan->where('uptd_id', $uptd_id);
        }
        $ruas_jalan = $ruas_jalan->get();

        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $sup = $sup->where('uptd_id', $uptd_id);
        }
        $sup = $sup->get();


        $mandor = User::where('user_role.role', 'like', '%mandor%');
        $mandor = $mandor->leftJoin('user_role', 'user_role.id', '=', 'users.internal_role_id')->select('users.*', 'user_role.id as id_role');
        if (Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role, 'Mandor') || str_contains(Auth::user()->internalRole->role, 'Pengamat') || str_contains(Auth::user()->internalRole->role, 'Kepala Satuan Unit Pemeliharaan')) {

            $mandor = $mandor->where('sup_id', Auth::user()->sup_id);
        }
        $mandor = $mandor->get();

        $kemandoran = DB::table('kemandoran');

        // foreach ($pekerjaan as $item) {
        //     if ($item->mail == 1) {
        //         $next_user = DB::table('users')->where('internal_role_id', $item->status->parent)->where('sup_id', $item->status->sup_id)->get();
        //         $item->status->next_user = $next_user;

        //         $name = Str::title($item->status->name);
        //         $id_pek = $item->id_pek;
        //         $nama_mandor = Str::title($item->nama_mandor);
        //         $jenis_pekerjaan = Str::title($item->paket);
        //         $uptd = Str::upper($item->status->uptd);
        //         $sup_mail = $item->sup;
        //         $status_mail = "Submitted";
        //         $keterangan = "Silahkan menunggu sampai semua menyetujui / Approved";
        //         $subject = "Status Laporan $item->id_pek-Submitted";
        //         if (str_contains($item->status->status, 'Edited')) {
        //             $status_mail = $item->status->status;
        //             $subject = "Status Laporan $item->id_pek-" . $item->status->status;
        //         }

        //         $to_email = $item->status->email;
        //         $to_name = $item->nama_mandor;
        //         $mail = $this->setSendEmail($name, $id_pek, $nama_mandor, $jenis_pekerjaan, $uptd, $sup_mail, $status_mail, $keterangan, $to_email, $to_name, $subject);
        //         if ($item->status->next_user != "") {
        //             foreach ($item->status->next_user as $no => $item1) {
        //                 $to_email = $item1->email;
        //                 $to_name = $item1->name;

        //                 $name = Str::title($item1->name);
        //                 $id_pek = $item->id_pek;
        //                 $nama_mandor = Str::title($item->nama_mandor);
        //                 $jenis_pekerjaan = Str::title($item->paket);
        //                 $uptd = Str::upper($item->status->uptd);
        //                 $sup_mail = $item->sup;

        //                 $keterangan = "Silahkan ditindak lanjuti";

        //                 $mail = $this->setSendEmail($name, $id_pek, $nama_mandor, $jenis_pekerjaan, $uptd, $sup_mail, $status_mail, $keterangan, $to_email, $to_name, $subject);
        //             }
        //         }
        //         if ($kemandoran->where('id_pek', $item->id_pek)->where('mail', $item->mail)->exists()) {
        //             $mail['mail'] = 2;
        //             $kemandoran->update($mail);
        //         }
        //     }
        // }
        $approve = 0;
        $reject = 0;
        $submit = 0;
        $not_complete = 0;

        $rekaps = DB::table('kemandoran')
            ->leftJoin('kemandoran_detail_status', 'kemandoran_detail_status.id_pek', '=', 'kemandoran.id_pek')
            ->select('kemandoran.*', 'kemandoran_detail_status.status', DB::raw('max(kemandoran_detail_status.id ) as status_s'), DB::raw('max(kemandoran_detail_status.id ) as status_s'))
            ->groupBy('kemandoran.id_pek');

        if (Auth::user() && Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $rekaps = $rekaps->where('kemandoran.uptd_id', $uptd_id);
            if (str_contains(Auth::user()->internalRole->role, 'Mandor')) {
                $rekaps = $rekaps->where('kemandoran.user_id', Auth::user()->id);
            } else if (Auth::user()->sup_id)
                $rekaps = $rekaps->where('kemandoran.sup_id', Auth::user()->sup_id);
        }

        $rekaps = $rekaps->get();
        foreach ($rekaps as $it) {

            $it->status_material = DB::table('bahan_material')->where('id_pek', $it->id_pek)->exists();

            $rekaplap = DB::table('kemandoran_detail_status')->where('id', $it->status_s)->pluck('status')->first();
            $it->status = $rekaplap;
            if (($it->status == "Approved" || $it->status == "Rejected" || $it->status == "Edited") || $it->status_material) {
                if ($it->status == "Approved") {
                    $approve += 1;
                } else if ($it->status == "Rejected" || $it->status == "Edited") {
                    $reject += 1;
                } else
                    $submit += 1;
            } else
                $not_complete += 1;
        }
        $sum_report = [
            "approve" => $approve,
            "reject" => $reject,
            "submit" => $submit,
            "not_complete" => $not_complete

        ];
        $jenis_laporan_pekerjaan = DB::table('utils_jenis_laporan')->get();

        $uptd = DB::table('landing_uptd')->get();
        $sup = DB::table('utils_sup')->get();
        // dd($uptd);

        return view('admin.monitoring.pekerjaan.resume', compact('filter', 'sup', 'uptd', 'pekerjaan', 'ruas_jalan', 'sup', 'mandor',  'sum_report', 'nama_kegiatan_pekerjaan', 'jenis_laporan_pekerjaan'));
    }

    public static function cekMaterial($id)
    {
        $cek = DB::table('bahan_material')->where('id_pek', $id)->exists();
        return $cek;
    }
}
