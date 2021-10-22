<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{

// Lokasi: Landing Page

    // GET
    public function index()
    {
        $profil = DB::table('landing_profil')->where('id',1)->first();
        $fitur = DB::table('landing_fitur')->get();
        $uptd = DB::table('landing_uptd')->get();
        $slideshow = DB::table('landing_slideshow')->get();
        $lokasi = DB::table('utils_lokasi')->get();
        $jenis_laporan = DB::table('utils_jenis_laporan')->get();

        // Compact mengubah variabel profil untuk dijadikan variabel yang dikirim
        return view('landing.index', compact('profil', 'fitur', 'uptd', 'slideshow','lokasi','jenis_laporan'));
    }
    public function login()
    {
        $profil = DB::table('landing_profil')->where('id',1)->first();

        return view('landing.login', compact('profil'));
    }
    public function paketPekerjaan()
    {
        $profil = DB::table('landing_profil')->where('id',1)->first();
        return view('landing.paket-pekerjaan',compact('profil'));
    }
    public function progressPekerjaan()
    {
        $profil = DB::table('landing_profil')->where('id',1)->first();
        return view('landing.progress-pekerjaan',compact('profil'));
    }

    // POST
    public function createLaporan(Request $req)
    {
        $color = 'success';
        $msg = 'Berhasil menambahkan laporan, tanggapan akan dikirim melalui email anda';

        $data = $req->except(['_token', 'agreed', 'gambar']);
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');

        DB::table('monitoring_laporan_masyarakat')->insert($data);

        return redirect('/#laporan')->with(['color' => $color,'laporan-msg' => $msg]);
    }

    public function createPesan(Request $req)
    {
        $color = 'success';
        $msg = 'Berhasil menambahkan pesan, tanggapan akan dikirim melalui email anda';

        $data = $req->except(['_token']);
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');

        DB::table('landing_pesan')->insert($data);

        return redirect('/#kontak')->with(['color' => $color,'pesan-msg' => $msg]);

    }

    public function uptd($slug)
    {
        $profil = DB::table('landing_profil')->where('id',1)->first();
        $uptd = DB::table('landing_uptd')->where('slug',$slug)->first();
        return view('landing.uptd.index',compact('profil', 'uptd'));
    }

// Lokasi: Admin Dashboard

    // TODO: Pesan
    public function getPesan()
    {
        $pesan = DB::table('landing_pesan')->get();
        return view('admin.landing.pesan',compact('pesan'));
    }

    // TODO: Profil
    public function getRuasJalan()
    {
      
        $ruas_jalan = DB::table('master_ruas_jalan')->get();
        return view('admin.master.ruas_jalan',compact('ruas_jalan'));
    }
    public function updateProfil(Request $req)
    {
        $color = 'success';
        $msg = 'Berhasil mengubah data profil';

        $old = DB::table('landing_profil')->where('id',1)->first();

        $data = $req->except('_token','gambar');
        if($req->gambar != null){
            $old->gambar ?? Storage::delete('public/'.$old->gambar);

            $path = 'landing/profil/'.Str::snake(date("YmdHis").' '.$req->gambar->getClientOriginalName());
            $req->gambar->storeAs('public/',$path);
            $data['gambar'] = $path;

        }

        DB::table('landing_profil')->where('id',1)->update($data);

        return back()->with(compact('color','msg'));
    }

    // TODO: Slideshow
    public function getSlideshow()
    {
        $slideshow = DB::table('landing_slideshow')->get();
        return view('admin.landing.slideshow.index',compact('slideshow'));
    }
    public function editSlideshow($id)
    {
        $slideshow = DB::table('landing_slideshow')->where('id',$id)->first();
        return view('admin.landing.slideshow.edit',compact('slideshow'));
    }
    public function createSlideshow(Request $req)
    {
        $slideshow = $req->except('_token','gambar');

        if($req->gambar != null){
            $path = 'landing/slideshow/'.Str::snake(date("YmdHis").' '.$req->gambar->getClientOriginalName());
            $req->gambar->storeAs('public/',$path);
            $slideshow['gambar'] = $path;
        }


        DB::table('landing_slideshow')->insert($slideshow);

        $color = "success";
        $msg = "Berhasil Menambah Data Slideshow";
        return back()->with(compact('color','msg'));
    }
    public function updateSlideshow(Request $req)
    {
        $slideshow = $req->except('_token','gambar','id');

        $old = DB::table('landing_slideshow')->where('id',$req->id)->first();

        if($req->gambar != null){
            $old->gambar ?? Storage::delete('public/'.$old->gambar);

            $path = 'landing/slideshow/'.Str::snake(date("YmdHis").' '.$req->gambar->getClientOriginalName());
            $req->gambar->storeAs('public/',$path);
            $slideshow['gambar'] = $path;

        }


        DB::table('landing_slideshow')->where('id',$req->id)->update($slideshow);

        $color = "success";
        $msg = "Berhasil Mengubah Data Slideshow";
        return redirect(route('getLandingSlideshow'))->with(compact('color','msg'));
    }
    public function deleteSlideshow($id)
    {
        $old = DB::table('landing_slideshow')->where('id',$id);
        $old->first()->gambar ?? Storage::delete('public/'.$old->first()->gambar);

        $old->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Slideshow";
        return redirect(route('getLandingSlideshow'))->with(compact('color','msg'));
    }

    // TODO: Fitur
    public function getFitur()
    {
        $fitur = DB::table('landing_fitur')->get();
        return view('admin.landing.fitur.index',compact('fitur'));
    }
    public function editFitur($id)
    {
        $fitur  = DB::table('landing_fitur')->where('id',$id)->first();
        return view('admin.landing.fitur.edit',compact('fitur'));
    }
    public function createFitur(Request $req)
    {
        $fitur = $req->except('_token');

        DB::table('landing_fitur')->insert($fitur);

        $color = "success";
        $msg = "Berhasil Menambah Data Fitur";
        return back()->with(compact('color','msg'));
    }
    public function updateFitur(Request $req)
    {
        $fitur = $req->except('_token','id');

        DB::table('landing_fitur')->where('id',$req->id)->update($fitur);

        $color = "success";
        $msg = "Berhasil Mengubah Data Fitur";
        return redirect(route('getLandingFitur'))->with(compact('color','msg'));
    }

    public function deleteFitur($id)
    {
        DB::table('landing_fitur')->where('id',$id)->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Fitur";
        return redirect(route('getLandingFitur'))->with(compact('color','msg'));
    }

    // TODO: UPTD
    public function getUPTD()
    {
        $uptd = DB::table('landing_uptd');
        if (Auth::user()->internalRole->uptd) {
            $uptd = $uptd->where('slug',Auth::user()->internalRole->uptd);
        }
        $uptd = $uptd->get();
        return view('admin.landing.uptd.index',compact('uptd'));
    }
    public function editUPTD($id)
    {
        $uptd = DB::table('landing_uptd')->where('id',$id)->first();
        return view('admin.landing.uptd.edit',compact('uptd'));
    }
    public function createUPTD(Request $req)
    {
        $uptd = $req->except('_token','gambar');
        $uptd['slug'] = Str::slug($req->nama, '');

        if($req->gambar != null){
            $path = 'landing/uptd/'.Str::snake(date("YmdHis").' '.$req->gambar->getClientOriginalName());
            $req->gambar->storeAs('public/',$path);
            $uptd['gambar'] = $path;
        }

        DB::table('landing_uptd')->insert($uptd);

        $color = "success";
        $msg = "Berhasil Menambah Data UPTD";
        return back()->with(compact('color','msg'));
    }
    public function updateUPTD(Request $req)
    {
        $uptd = $req->except('_token','gambar','id');
        $uptd['slug'] = Str::slug($req->nama, '');

        $old = DB::table('landing_uptd')->where('id',$req->id)->first();

        if($req->gambar != null){
            $old->gambar ?? Storage::delete('public/'.$old->gambar);

            $path = 'landing/uptd/'.Str::snake(date("YmdHis").' '.$req->gambar->getClientOriginalName());
            $req->gambar->storeAs('public/',$path);
            $uptd['gambar'] = $path;
        }

        DB::table('landing_uptd')->where('id',$req->id)->update($uptd);

        $color = "success";
        $msg = "Berhasil Mengubah Data UPTD";
        return redirect(route('getLandingUPTD'))->with(compact('color','msg'));
    }
    public function deleteUPTD($id)
    {
        $old = DB::table('landing_uptd')->where('id',$id);
        $old->first()->gambar ?? Storage::delete('public/'.$old->first()->gambar);


        $old->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data UPTD";
        return redirect(route('getLandingUPTD'))->with(compact('color','msg'));
    }

// DEBUG
    public function howToInsert(Request $req)
    {
        // Cara 1
        $data = [
            'nama' => $req->nama,
            'nik' => $req->nik,
            'telp' => $req->telp,
            'email' => $req->email,
            'jenis' => $req->jenis,
            'deskripsi' => $req->deskripsi,
            'lat' => $req->lat,
            'long' => $req->long,
            'uptd_id' => $req->uptd_id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        // Cara 2 : Pastikan input name sama dengan kolom tabel
        $data = $req->except(['_token', 'input_name_lain']);
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
    }
}
