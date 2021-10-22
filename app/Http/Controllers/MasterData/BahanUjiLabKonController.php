<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BahanUjiLabKonController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Bahan Uji Labkon', ['create', 'createDetail'], ['index'], ['edit', 'update', 'editDetail', 'updateDetail'], ['destroy', 'destroyDetail']);
        foreach ($roles as $role => $permission) {
            $this->middleware($role)->only($permission);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bahan_uji_labkon = DB::table('bahan_uji')->get();
        $nama_uji_labkon = DB::table('bahan_uji_detail')
            ->leftJoin(
                'bahan_uji',
                'bahan_uji.id',
                '=',
                'bahan_uji_detail.id_bahan_uji'
            )
            ->select(
                'bahan_uji.nama as nama_bahan',
                'bahan_uji_detail.*'
            )
            ->where('bahan_uji_detail.status', '!=', 'deleted')
            ->get();
        // dd($nama_uji_labkon);
        return view('admin.master.bahan_uji_labkon.index', compact('bahan_uji_labkon', 'nama_uji_labkon'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = 'store';
        return view('admin.master.bahan_uji_labkon.insert', compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bahan_uji_labkon['nama'] = $request->nama;
        $bahan_uji_labkon['status'] = $request->status == 'on' ? 'aktif' : 'nonaktif';
        $bahan_uji_labkon['created_by'] = Auth::user()->id;
        $bahan_uji_labkon['created_at'] = Carbon::now();
        DB::table('bahan_uji')->insert($bahan_uji_labkon);
        $color = "success";
        $msg = "Berhasil Menambah Data Bahan Uji";
        return redirect(route('bahan_uji_labkon.index'))->with(compact('color', 'msg'));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bahan_uji_labkon = DB::table('bahan_uji')->where('id', $id)->first();
        $action = 'update';
        return view('admin.master.bahan_uji_labkon.insert', compact('action', 'bahan_uji_labkon'));
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
        $bahan_uji_labkon['nama'] = $request->nama;
        $bahan_uji_labkon['status'] = $request->status == 'on' ? 'aktif' : 'nonaktif';
        $bahan_uji_labkon['updated_at'] = Carbon::now();
        $bahan_uji_labkon['updated_by'] = Auth::user()->id;
        DB::table('bahan_uji')->where('id', $id)->update($bahan_uji_labkon);
        $color = "success";
        $msg = "Berhasil Memperbaharui Data Bahan Uji";
        return redirect(route('bahan_uji_labkon.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bahan_uji_labkon = DB::table('bahan_uji')->where('id', $id)->delete();
        $color = "success";
        $msg = "Berhasil Menghapus Data Bahan Uji";
        return redirect(route('bahan_uji_labkon.index'))->with(compact('color', 'msg'));
    }

    public function createDetail()
    {
        // dd('dsa00');
        $action = 'store';
        $bahan_uji_labkon = DB::table('bahan_uji')->get();
        return view('admin.master.bahan_uji_labkon.detail', compact('action', 'bahan_uji_labkon'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDetail(Request $request)
    {
        $nama_uji_labkon['nama_pengujian'] = $request->nama_pengujian;
        $nama_uji_labkon['status'] = $request->status == 'on' ? 'aktif' : 'nonaktif';
        $nama_uji_labkon['id_bahan_uji'] = $request->id_bahan_uji;
        $nama_uji_labkon['created_by'] = Auth::user()->id;
        $nama_uji_labkon['created_at'] = Carbon::now();
        DB::table('bahan_uji_detail')->insert($nama_uji_labkon);
        $color = "success";
        $msg = "Berhasil Menambah Data Nama Pengujian";
        return redirect(route('bahan_uji_labkon.index'))->with(compact('color', 'msg'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDetail($id)
    {
        // dd('dawsd');
        $nama_uji_labkon = DB::table('bahan_uji_detail')->where('id', $id)->first();
        $bahan_uji_labkon = DB::table('bahan_uji')->get();
        $action = 'update';
        return view('admin.master.bahan_uji_labkon.detail', compact('action', 'bahan_uji_labkon', 'nama_uji_labkon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDetail(Request $request, $id)
    {
        $nama_uji_labkon['updated_by'] = Auth::user()->id;
        $nama_uji_labkon['nama_pengujian'] = $request->nama_pengujian;
        $nama_uji_labkon['status'] = $request->status == 'on' ? 'aktif' : 'nonaktif';
        $nama_uji_labkon['id_bahan_uji'] = $request->id_bahan_uji;
        $nama_uji_labkon['updated_at'] = Carbon::now();
        DB::table('bahan_uji_detail')->where('id', $id)->update($nama_uji_labkon);
        $color = "success";
        $msg = "Berhasil Memperbaharui Data Nama Pengujian";
        return redirect(route('bahan_uji_labkon.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyDetail($id)
    {
        $bahan_uji['status'] = 'deleted';
        $bahan_uji_labkon = DB::table('bahan_uji_detail')->where('id', $id)->update($bahan_uji);
        $color = "success";
        $msg = "Berhasil Menghapus Data Nama Pengujian";
        return redirect(route('bahan_uji_labkon.index'))->with(compact('color', 'msg'));
    }
}
