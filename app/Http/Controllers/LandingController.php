<?php

namespace App\Http\Controllers;

use App\Model\Transactional\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Model\Transactional\LaporanMasyarakat;

class LandingController extends Controller
{
    public function __construct()
    {
        $roles = [];
        $uptd_role = setAccessBuilder('UPTD', ['createUPTD'], ['getUPTD'], ['editUPTD', 'updateUPTD'], ['deleteUPTD']);
        $laporan = setAccessBuilder('Input Laporan', ['addLaporanMasyarakat'], [], [], []);
        $daftar_laporan = setAccessBuilder('Daftar Laporan', ['createLaporanMasyarakat'], ['addLaporanMasyarakat'], ['editLaporanMasyarakat', 'updateLaporanMasyaraka'], ['deleteLaporanMasyarakat']);
        $slideshow = setAccessBuilder('Slideshow', ['createSlideshow'], ['getSlideshow'], ['editSlideshow', 'updateSlideshow'], ['deleteSlideshow']);
        $profil = setAccessBuilder('Profil WEB', [], ['getProfil'], ['updateProfil'], []);
        $fitur = setAccessBuilder('Fitur', ['createFitur'], ['getFitur'], ['editFitur', 'updateFitur'], ['deleteFitur']);
        $pesan = setAccessBuilder('Pesan', [], ['getPesan'], [], []);
        $roles = array_merge($roles, $uptd_role);
        $roles = array_merge($roles, $laporan);
        $roles = array_merge($roles, $daftar_laporan);
        $roles = array_merge($roles, $slideshow);
        $roles = array_merge($roles, $profil);
        $roles = array_merge($roles, $fitur);
        $roles = array_merge($roles, $pesan);
        foreach ($roles as $role => $permission) {
            $this->middleware($role)->only($permission);
        }
    }

    // Lokasi: Landing Page

    // GET
    public function index()
    {
        $profil = DB::table('landing_profil')->where('id', 1)->first();
        $fitur = DB::table('landing_fitur')->get();
        $uptd = DB::table('landing_uptd')->get();
        $slideshow = DB::table('landing_slideshow')->get();
        $lokasi = DB::table('utils_lokasi')->get();
        $jenis_laporan = DB::table('utils_jenis_laporan')->get();
        $ruas_jalan = DB::table('master_ruas_jalan')->get();
        $video = DB::table('landing_news_video')->get();

        // Compact mengubah variabel profil untuk dijadikan variabel yang dikirim
        return view('landing.index', compact('profil', 'fitur', 'uptd', 'slideshow', 'lokasi', 'jenis_laporan', 'ruas_jalan', 'video'));
    }
    public function login()
    {
        $profil = DB::table('landing_profil')->where('id', 1)->first();
        return view('landing.login', compact('profil'));
    }
    public function paketPekerjaan()
    {
        $profil = DB::table('landing_profil')->where('id', 1)->first();
        return view('landing.paket-pekerjaan', compact('profil'));
    }
    public function progressPekerjaan()
    {
        $profil = DB::table('landing_profil')->where('id', 1)->first();
        return view('landing.progress-pekerjaan', compact('profil'));
    }


    // POST
    public function createLaporan(Request $request)
    {
        $rand = rand(100000, 999999);

        $kode = "P-" . $rand;
        $laporanMasyarakat = new LaporanMasyarakat;
        $laporanMasyarakat->fill($request->except(['gambar']));
        if ($request->gambar != null) {
            $path = 'laporan_masyarakat/' . date("YmdHis") . '_' . $request->gambar->getClientOriginalName();
            $request->gambar->storeAs('public/', $path);
            $laporanMasyarakat['gambar'] = $path;
        }
        $laporanMasyarakat->nomorPengaduan = $kode;
        $laporanMasyarakat->status = 'Submitted';
        $laporanMasyarakat->save();
        $color = 'success';
        $msg = 'Berhasil menambahkan laporan, tanggapan akan dikirim melalui email anda';
        return redirect('/#laporan')->with(['color' => $color, 'laporan-msg' => $msg]);
    }

    public function createPesan(Request $req)
    {
        $color = 'success';
        $msg = 'Berhasil menambahkan pesan, tanggapan akan dikirim melalui email anda';

        $data = $req->except(['_token']);
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');

        DB::table('landing_pesan')->insert($data);

        return redirect('/#kontak')->with(['color' => $color, 'pesan-msg' => $msg]);
    }

    public function uptd($slug)
    {
        $profil = DB::table('landing_profil')->where('id', 1)->first();
        $uptd = DB::table('landing_uptd')->where('slug', $slug)->first();
        // dd($uptd);
        $uptd_mapdata_all = [
            "uptd1" => ["ctr_lat" => -6.743473, "ctr_long" => 106.995262, "ctr_ext" => [-7.505569, -5.918148, 106.401085, 107.484841]],
            "uptd2" => ["ctr_lat" => -7.074604, "ctr_long" => 106.709829, "ctr_ext" => [-7.437657, -6.715317, 106.370304, 107.065018]],
            "uptd3" => ["ctr_lat" => -6.647938, "ctr_long" => 107.532346, "ctr_ext" => [-7.316122, -5.939857, 107.084381, 107.931877]],
            "uptd4" => ["ctr_lat" => -7.180538, "ctr_long" => 107.853166, "ctr_ext" => [-7.74015, -6.578923, 107.42025, 108.220009]],
            "uptd5" => ["ctr_lat" => -7.381833, "ctr_long" => 108.351077, "ctr_ext" => [-7.820979, -6.78191, 107.904429, 108.801382]],
            "uptd6" => ["ctr_lat" => -6.629987, "ctr_long" => 108.288672, "ctr_ext" => [-7.074581, -6.221112, 107.850746, 108.846881]]
        ];
        $slug = substr($slug, 0, 5);
        // dd($slug);
        $uptd_mapdata = $uptd_mapdata_all[$slug];
        return view('landing.uptd.index', compact('profil', 'uptd', 'uptd_mapdata'));
    }

    // Lokasi: Admin Dashboard

    // TODO: Pesan
    public function getPesan()
    {
        $pesan = DB::table('landing_pesan')->get();
        return view('admin.landing.pesan', compact('pesan'));
    }

    public function getLog()
    {
        $logs = Log::latest('created_at')->paginate(1000);
        return view('admin.landing.log', compact('logs'));
    }
    public function getLogUser()
    {
        $logs = Log::where('user_id',Auth::user()->id)->latest('created_at')->paginate(1000);
        return view('admin.landing.log', compact('logs'));
    }

    // TODO: Profil
    public function getProfil()
    {
        $profil = DB::table('landing_profil')->where('id', 1)->first();
        return view('admin.landing.profil', compact('profil'));
    }
    public function updateProfil(Request $req)
    {
        $color = 'success';
        $msg = 'Berhasil mengubah data profil';

        $old = DB::table('landing_profil')->where('id', 1)->first();

        $data = $req->except('_token', 'gambar');
        if ($req->gambar != null) {
            $old->gambar ?? Storage::delete('public/' . $old->gambar);

            $path = 'landing/profil/' . Str::snake(date("YmdHis") . ' ' . $req->gambar->getClientOriginalName());
            $req->gambar->storeAs('public/', $path);
            $data['gambar'] = $path;
        }

        DB::table('landing_profil')->where('id', 1)->update($data);

        return back()->with(compact('color', 'msg'));
    }

    // TODO: Slideshow
    public function getSlideshow()
    {
        $slideshow = DB::table('landing_slideshow')->get();
        return view('admin.landing.slideshow.index', compact('slideshow'));
    }
    public function editSlideshow($id)
    {
        $slideshow = DB::table('landing_slideshow')->where('id', $id)->first();
        return view('admin.landing.slideshow.edit', compact('slideshow'));
    }
    public function createSlideshow(Request $req)
    {
        $slideshow = $req->except('_token', 'gambar');

        if ($req->gambar != null) {
            $path = 'landing/slideshow/' . Str::snake(date("YmdHis") . ' ' . $req->gambar->getClientOriginalName());
            $req->gambar->storeAs('public/', $path);
            $slideshow['gambar'] = $path;
        }


        DB::table('landing_slideshow')->insert($slideshow);

        $color = "success";
        $msg = "Berhasil Menambah Data Slideshow";
        return back()->with(compact('color', 'msg'));
    }
    public function updateSlideshow(Request $req)
    {
        $slideshow = $req->except('_token', 'gambar', 'id');

        $old = DB::table('landing_slideshow')->where('id', $req->id)->first();

        if ($req->gambar != null) {
            $old->gambar ?? Storage::delete('public/' . $old->gambar);

            $path = 'landing/slideshow/' . Str::snake(date("YmdHis") . ' ' . $req->gambar->getClientOriginalName());
            $req->gambar->storeAs('public/', $path);
            $slideshow['gambar'] = $path;
        }


        DB::table('landing_slideshow')->where('id', $req->id)->update($slideshow);

        $color = "success";
        $msg = "Berhasil Mengubah Data Slideshow";
        return redirect(route('getLandingSlideshow'))->with(compact('color', 'msg'));
    }
    public function deleteSlideshow($id)
    {
        $old = DB::table('landing_slideshow')->where('id', $id);
        $old->first()->gambar ?? Storage::delete('public/' . $old->first()->gambar);

        $old->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Slideshow";
        return redirect(route('getLandingSlideshow'))->with(compact('color', 'msg'));
    }

    // TODO: Fitur
    public function getFitur()
    {
        $fitur = DB::table('landing_fitur')->get();
        return view('admin.landing.fitur.index', compact('fitur'));
    }
    public function editFitur($id)
    {
        $fitur  = DB::table('landing_fitur')->where('id', $id)->first();
        return view('admin.landing.fitur.edit', compact('fitur'));
    }
    public function createFitur(Request $req)
    {
        $fitur = $req->except('_token');

        DB::table('landing_fitur')->insert($fitur);

        $color = "success";
        $msg = "Berhasil Menambah Data Fitur";
        return back()->with(compact('color', 'msg'));
    }
    public function updateFitur(Request $req)
    {
        $fitur = $req->except('_token', 'id');

        DB::table('landing_fitur')->where('id', $req->id)->update($fitur);

        $color = "success";
        $msg = "Berhasil Mengubah Data Fitur";
        return redirect(route('getLandingFitur'))->with(compact('color', 'msg'));
    }

    public function deleteFitur($id)
    {
        DB::table('landing_fitur')->where('id', $id)->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Fitur";
        return redirect(route('getLandingFitur'))->with(compact('color', 'msg'));
    }

    // TODO: UPTD
    public function getUPTD()
    {
        $uptd = DB::table('landing_uptd');
        if (Auth::user()->internalRole->uptd) {
            $uptd = $uptd->where('slug', Auth::user()->internalRole->uptd);
        }
        $uptd = $uptd->get();
        return view('admin.landing.uptd.index', compact('uptd'));
    }
    public function editUPTD($id)
    {
        $uptd = DB::table('landing_uptd')->where('id', $id)->first();
        return view('admin.landing.uptd.edit', compact('uptd'));
    }
    public function createUPTD(Request $req)
    {
        $uptd = $req->except('_token', 'gambar');
        $uptd['slug'] = Str::slug($req->nama, '');

        if ($req->gambar != null) {
            $path = 'landing/uptd/' . Str::snake(date("YmdHis") . ' ' . $req->gambar->getClientOriginalName());
            $req->gambar->storeAs('public/', $path);
            $uptd['gambar'] = $path;
        }

        DB::table('landing_uptd')->insert($uptd);

        $color = "success";
        $msg = "Berhasil Menambah Data UPTD";
        return back()->with(compact('color', 'msg'));
    }
    public function updateUPTD(Request $req)
    {
        $uptd = $req->except('_token', 'gambar', 'id');
        $uptd['slug'] = Str::slug($req->nama, '');

        $old = DB::table('landing_uptd')->where('id', $req->id)->first();

        if ($req->gambar != null) {
            $old->gambar ?? Storage::delete('public/' . $old->gambar);

            $path = 'landing/uptd/' . Str::snake(date("YmdHis") . ' ' . $req->gambar->getClientOriginalName());
            $req->gambar->storeAs('public/', $path);
            $uptd['gambar'] = $path;
        }

        DB::table('landing_uptd')->where('id', $req->id)->update($uptd);

        $color = "success";
        $msg = "Berhasil Mengubah Data UPTD";
        return redirect(route('getMasterUPTD'))->with(compact('color', 'msg'));
    }
    public function deleteUPTD($id)
    {
        $old = DB::table('landing_uptd')->where('id', $id);
        $old->first()->gambar ?? Storage::delete('public/' . $old->first()->gambar);


        $old->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data UPTD";
        return redirect(route('getMasterUPTD'))->with(compact('color', 'msg'));
    }

    // DEBUG
    public function howToInsert(Request $req)
    {
        // Cara 1
        // $data = [
        //     'nama' => $req->nama,
        //     'nik' => $req->nik,
        //     'telp' => $req->telp,
        //     'email' => $req->email,
        //     'jenis' => $req->jenis,
        //     'deskripsi' => $req->deskripsi,
        //     'lat' => $req->lat,
        //     'long' => $req->long,
        //     'uptd_id' => $req->uptd_id,
        //     'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        // ];

        // Cara 2 : Pastikan input name sama dengan kolom tabel
        $data = $req->except(['_token', 'input_name_lain']);
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
    }

    public function getLaporanMasyarakat()
    {
        $laporan = DB::table('monitoring_laporan_masyarakat')->get();
        // dd($laporan);
        return view('admin.landing.laporan-masyarakat.index', compact('laporan'));
    }

    public function addLaporanMasyarakat()
    {
        $uptd = DB::table('landing_uptd')->get();

        return view('admin.landing.laporan-masyarakat.add', compact('uptd'));
    }

    public function createLaporanMasyarakat(Request $request)
    {
        $rand = rand(100000, 999999);

        $kode = "P-" . $rand;
        $laporanMasyarakat = new LaporanMasyarakat;
        $laporanMasyarakat->fill($request->except(['gambar']));
        if ($request->gambar != null) {
            $path = 'laporan_masyarakat/' . date("YmdHis") . '_' . $request->gambar->getClientOriginalName();
            $request->gambar->storeAs('public/', $path);
            $laporanMasyarakat['gambar'] = $path;
        }
        $laporanMasyarakat->nomorPengaduan = $kode;
        $laporanMasyarakat->status = 'Submitted';
        $laporanMasyarakat->save();


        $color = "success";
        $msg = "Berhasil Menambah Data Laporan Masyarakat";
        return back()->with(compact('color', 'msg'));
    }

    public function detailLaporanMasyarakat($id)
    {
        $detail = DB::table('monitoring_laporan_masyarakat')->where('id', $id)->get();
        return view('admin.landing.laporan-masyarakat.detail', ['detail' => $detail]);
    }
    public function editLaporanMasyarakat($id)
    {
        $data = DB::table('monitoring_laporan_masyarakat')->where('id', $id)->first();
        return view('admin.landing.laporan-masyarakat.edit', ['data' => $data]);

        // return response()->json(['data' => $data], 200);
    }

    public function deleteLaporanMasyarakat($id)
    {
        DB::table('monitoring_laporan_masyarakat')->where('id', $id)->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Laporan Masyarakat";
        return back()->with(compact('color', 'msg'));
    }
    public function updateLaporanMasyarakat(Request $req)
    {
        $data['nama'] = $req->nama;
        $data['nik'] = $req->nik;
        $data['alamat'] = $req->alamat;
        $data['telp'] = $req->telp;
        $data['email'] = $req->email;
        $data['jenis'] = $req->jenis;
        if ($req->gambar != null) {
            $path = 'laporan_masyarakat/' . date("YmdHis") . '_' . $req->gambar->getClientOriginalName();
            $req->gambar->storeAs('public/', $path);
            $data['gambar'] = $path;
        }
        $data['lokasi'] = $req->lokasi;
        $data['lat'] = $req->lat;
        $data['long'] = $req->long;
        $data['deskripsi'] = $req->deskripsi;
        $data['uptd_id'] = $req->uptd_id;
        $data['status'] = $req->status;

        $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

        DB::table('monitoring_laporan_masyarakat')->where('id', $req->id)->update($data);
        $title = "Perubahan status laporan.";
        $body = "Status laporan kamu saat ini berubah menjadi : " . $req->status;
        $userPelapor = DB::table("users")->where('email', $req->email)->first();
        //dd($userPelapor);
        if ($userPelapor) {
            $users = [$userPelapor->id];
            //$usersToken = DB::table('user_push_notification')->whereIn('user_id', $users)->pluck('device_token')->get();
            //dd($usersToken);

            sendNotification($users, $title, $body);
        }
        $color = "success";
        $msg = "Berhasil Menambah Data Laporan Masyarakat";
        return redirect(url('admin/lapor'))->with(compact('color', 'msg'));

        // return back()->with(compact('color', 'msg'));
    }
    public function labkon()
    {
        $data = DB::table('labkon_posts')->get();

        return view('landing.uptd.labkon', compact('data'));
    }
    public function createpost()
    {
        return view('landing.uptd.lab.posts');
    }
    public function storepost(Request $request)
    {
        // dd($request->cover);
        $this->validate($request, [
            'title'         => 'required|unique:labkon_posts',
            'content'       => 'required',
            'image'       => '',
            'category'      => ''
        ]);
        $posting = [
            'title'       => $request->input('title'),
            'slug'        => Str::slug($request->input('title'), '-'),
            'content'     => $request->input('content'),
            'view'          => 0,
            'image'         => $request->input('image'),
            'category'         => $request->input('category'),

        ];
        DB::table('labkon_posts')->insert($posting);
        return back();
    }
}
