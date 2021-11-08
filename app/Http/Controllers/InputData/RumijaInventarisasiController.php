<?php

namespace App\Http\Controllers\InputData;
use App\Transactional\RumijaInventarisasiKategori as InventarisasiKategori;
use App\Transactional\RumijaInventarisasi as Inventarisasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class RumijaInventarisasiController extends Controller
{
    //
    public function index()
    {
        $data = Inventarisasi::get();
        return view('admin.input_data.rumija_inventarisasi.index', compact('data'));
    }
    public function create()
    {
        $uptd = DB::table('landing_uptd')->get();
        $category = InventarisasiKategori::all();
        $action = 'store';
        $ruas_jalan = DB::table('master_ruas_jalan');
        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $ruas_jalan = $ruas_jalan->where('uptd_id', $uptd_id);
            $sup = $sup->where('uptd_id', $uptd_id);
        }
        $ruas_jalan = $ruas_jalan->get();
        $sup = $sup->get();
        return view('admin.input_data.rumija_inventarisasi.insert', compact('uptd', 'ruas_jalan', 'action','category','sup'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'rumija_inventarisasi_kategori_id' => 'required',
            'uptd_id' => 'required',
            'kd_sup' => 'required',
            'id_ruas_jalan' => 'required',
            'kode_lokasi' => 'required',
            'lokasi' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'lat_akhir' => '',
            'lng_akhir' => '',
            'name' => '',
            'jumlah' => '',
            'panjang' => '',
            'lebar' => '',
            'tinggi' => '',
            'diameter' => '',
            'kontruksi' => '',
            'posisi' => '',
            'desa' => '',
            'keterangan' => '',

        ]);
        if ($validator->fails()) {
            $color = "danger";
            $msg =  $validator->errors();
            // Input Log Activity User
            storeLogActivity(declarLog(1, 'Inventarisasi', $request->name ));
            return back()->with(compact('color', 'msg'));
        }
        // dd(Str::slug($request->name, '-'));
        $temp = [
            'rumija_inventarisasi_kategori_id' => $request->rumija_inventarisasi_kategori_id,
            'uptd_id' => $request->uptd_id,
            'kd_sup' => $request->kd_sup,
            'id_ruas_jalan' => $request->id_ruas_jalan,
            'kode_lokasi' => $request->kode_lokasi,
            'lokasi' => $request->lokasi,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'lat_akhir' => $request->lat_akhir,
            'lng_akhir' => $request->lng_akhir,
            
        ];
        $detail =[
            'name' => $request->name,
            'jumlah' => $request->jumlah,
            'panjang' => $request->panjang,
            'lebar' => $request->lebar,
            'tinggi' => $request->tinggi,
            'diameter' => $request->diameter,
            'kontruksi' => $request->kontruksi,
            'posisi' => $request->posisi,
            'desa' => $request->desa,
            'keterangan' => $request->keterangan,
        ];
        
        $inventarisasi = Inventarisasi::create($temp);
        $inventarisasi->detail()->create($detail);
        if($inventarisasi){
            //redirect dengan pesan sukses
            storeLogActivity(declarLog(1, 'Inventarisasi', $request->name, 1 ));
            return redirect()->route('rumija.inventarisasi.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            storeLogActivity(declarLog(1, 'Inventarisasi', $request->name ));
            return redirect()->route('rumija.inventarisasi.create')->with(['danger' => 'Data Gagal Disimpan!']);
        }
    }
    public function edit($id)
    {
        
        $inventaris = Inventarisasi::find($id);
        $uptd = DB::table('landing_uptd')->get();
        // $ruas_jalan = DB::table('master_ruas_jalan')->get();
        
        $category = InventarisasiKategori::all();
        $action = 'update';
        $ruas_jalan = DB::table('master_ruas_jalan');
        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $ruas_jalan = $ruas_jalan->where('uptd_id', $uptd_id);
            $sup = $sup->where('uptd_id', $uptd_id);
        }
        $ruas_jalan = $ruas_jalan->get();
        $sup = $sup->get();


        return view('admin.input_data.rumija_inventarisasi.insert', compact('action', 'inventaris', 'uptd', 'ruas_jalan', 'category', 'sup'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            
            'rumija_inventarisasi_kategori_id' => 'required',
            'uptd_id' => 'required',
            'kd_sup' => 'required',
            'id_ruas_jalan' => 'required',
            'kode_lokasi' => 'required',
            'lokasi' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'lat_akhir' => '',
            'lng_akhir' => '',
            'name' => '',
            'jumlah' => '',
            'panjang' => '',
            'lebar' => '',
            'tinggi' => '',
            'diameter' => '',
            'kontruksi' => '',
            'posisi' => '',
            'desa' => '',
            'keterangan' => '',

        ]);
        if ($validator->fails()) {
            $color = "danger";
            $msg =  $validator->errors();
            // Input Log Activity User
            storeLogActivity(declarLog(2, 'Inventarisasi', $request->name ));
            return back()->with(compact('color', 'msg'));
        }
        // dd(Str::slug($request->name, '-'));
        $temp = [
            'rumija_inventarisasi_kategori_id' => $request->rumija_inventarisasi_kategori_id,
            'uptd_id' => $request->uptd_id,
            'kd_sup' => $request->kd_sup,
            'id_ruas_jalan' => $request->id_ruas_jalan,
            'kode_lokasi' => $request->kode_lokasi,
            'lokasi' => $request->lokasi,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'lat_akhir' => $request->lat_akhir,
            'lng_akhir' => $request->lng_akhir,
            
        ];
        $detail =[
            'name' => $request->name,
            'jumlah' => $request->jumlah,
            'panjang' => $request->panjang,
            'lebar' => $request->lebar,
            'tinggi' => $request->tinggi,
            'diameter' => $request->diameter,
            'kontruksi' => $request->kontruksi,
            'posisi' => $request->posisi,
            'desa' => $request->desa,
            'keterangan' => $request->keterangan,
        ];
        
        $inventarisasi = Inventarisasi::findOrFail($id);
        
        $inventarisasi->update($temp);
        // dd($inventarisasi);
        $inventarisasi->detail()->updateOrCreate($detail);
        if($inventarisasi){
            //redirect dengan pesan sukses
            storeLogActivity(declarLog(2, 'Inventarisasi', $request->name, 1 ));
            return redirect()->route('rumija.inventarisasi.index')->with(['success' => 'Data Berhasil Diperbaharui!']);
        }else{
            //redirect dengan pesan error
            storeLogActivity(declarLog(2, 'Inventarisasi', $request->name ));
            return redirect()->route('rumija.inventarisasi.edit',$id)->with(['danger' => 'Data Gagal Diperbaharui!']);
        }
    }
    public function destroy($id)
    {
        $inventarisasi = Inventarisasi::findOrFail($id);
        // dd($inventarisasi);
        storeLogActivity(declarLog(3, 'Inventarisasi', $inventarisasi->kategori_inventarisasi->name,1 ));
        $inventarisasi->detail()->delete();
        $inventarisasi->delete();
        $color = "success";
        $msg = "Berhasil Memnghapus Data Inventarisasi";

        return redirect(route('rumija.inventarisasi.index'))->with(compact('color', 'msg'));
    }
    public function get_category()
    {
        $data = DB::table('rumija_inventarisasi_kategori')->get();
        return view('admin.input_data.rumija_inventarisasi.kategori.index', compact('data'));
    }
    public function create_category()
    {
        $action = 'store';
        return view('admin.input_data.rumija_inventarisasi.kategori.insert', compact('action'));
    }
    public function store_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:rumija_inventarisasi_kategori',
        ]);
        if ($validator->fails()) {
            $color = "danger";
            $msg =  $validator->errors();
            // Input Log Activity User
            storeLogActivity(declarLog(1, 'Kategori Inventarisasi', $request->name ));
            return back()->with(compact('color', 'msg'));
        }
        // dd(Str::slug($request->name, '-'));
        $category = DB::table('rumija_inventarisasi_kategori')->insert([
            'name'   => $request->name,
            'slug'   => Str::slug($request->name, '-')
        ]);
        if($category){
            //redirect dengan pesan sukses
            storeLogActivity(declarLog(1, 'Kategori Inventarisasi', $request->name, 1 ));
            return redirect()->route('rumija.inventarisasi.kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            storeLogActivity(declarLog(1, 'Kategori Inventarisasi', $request->name ));
            return redirect()->route('rumija.inventarisasi.kategori.create')->with(['danger' => 'Data Gagal Disimpan!']);
        }
    }
    public function edit_category($id)
    {
        
        $data = DB::table('rumija_inventarisasi_kategori')->find($id);
        $action = 'update';
        return view('admin.input_data.rumija_inventarisasi.kategori.insert', compact('action', 'data'));
    }
    public function update_category(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => Rule::unique('rumija_inventarisasi_kategori', 'name')->ignore($request->id)
        ]);
        if ($validator->fails()) {
            $color = "danger";
            $msg =  $validator->errors();
            // Input Log Activity User
            
            storeLogActivity(declarLog(2, 'Kategori Inventarisasi', $request->name ));
            return back()->with(compact('color', 'msg'));
        }
        // dd(Str::slug($request->name, '-'));
       
        $category = DB::table('rumija_inventarisasi_kategori')->where('id',$id);
       
        $category->update([
            'name'   => $request->name,
            'slug'   => Str::slug($request->name, '-')
        ]);
        if($category){
            //redirect dengan pesan sukses
            storeLogActivity(declarLog(2, 'Kategori Inventarisasi', $request->name, 1 ));
            return redirect()->route('rumija.inventarisasi.kategori.index')->with(['success' => 'Data Berhasil Diperbaharui!']);
        }else{
            //redirect dengan pesan error
            storeLogActivity(declarLog(2, 'Kategori Inventarisasi', $request->name ));
            return redirect()->route('rumija.inventarisasi.kategori.edit')->with(['danger' => 'Data Gagal Diperbaharui!']);
        }
    }
    public function destroy_category($id)
    {
        $category = DB::table('rumija_inventarisasi_kategori')->where('id', $id);
        storeLogActivity(declarLog(3, 'Kategori Inventarisasi', $category->first()->name,1 ));
        $category->delete();
        $color = "success";
        $msg = "Berhasil Memnghapus Data Kategori Inventarisasi";

        return redirect(route('rumija.inventarisasi.kategori.index'))->with(compact('color', 'msg'));
    }
    
}
