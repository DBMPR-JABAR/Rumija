<?php

namespace App\Http\Controllers\InputData;
use App\Transactional\RumijaInventarisasiKategori as InventarisasiKategori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
class RumijaInventarisasiController extends Controller
{
    //
    public function index()
    {
        $data = DB::table('rumija_inventarisasi')->get();
        return view('admin.input_data.rumija_inventarisasi.index', compact('data'));
    }
    public function create()
    {
        $uptd = DB::table('landing_uptd')->get();
        $ruas_jalan = DB::table('master_ruas_jalan')->get();
        $category = InventarisasiKategori::all();
        $action = 'store';
        return view('admin.input_data.rumija_inventarisasi.insert', compact('uptd', 'ruas_jalan', 'action','category'));
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
