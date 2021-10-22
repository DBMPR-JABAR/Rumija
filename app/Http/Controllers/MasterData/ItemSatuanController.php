<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemSatuanController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Item Satuan', ['create', 'store'], ['index'], ['edit', 'update'], ['destroy']);
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
        $item_satuan = DB::table('item_satuan')->get();
        //dd($item_satuan[0]->id);
        return view('admin.master.item_satuan.index', compact('item_satuan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.master.item_satuan.insert', ['action' => 'store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item_satuan['satuan'] = $request->satuan;
        DB::table('item_satuan')->insert($item_satuan);
        $color = "success";
        $msg = "Berhasil Menambah Data Item Satuan";
        return redirect(route('item_satuan.index'))->with(compact('color', 'msg'));
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
        $item_satuan = DB::table('item_satuan')->where('no', $id)->first();
        return view('admin.master.item_satuan.insert', ['action' => 'upadate', 'item_satuan' => $item_satuan]);
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
        $item_satuan['satuan'] = $request->satuan;
        DB::table('item_satuan')->where('no', $id)->update($item_satuan);

        $color = "success";
        $msg = "Berhasil Memperbaharui Data Item Satuan";
        return redirect(route('item_satuan.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item_satuan = DB::table('item_satuan')->where('no', $id)->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Item Satuan";
        return redirect(route('item_satuan.index'))->with(compact('color', 'msg'));
    }
}
