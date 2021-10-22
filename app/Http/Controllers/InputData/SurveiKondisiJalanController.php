<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
use App\Imports\SurveiKondisiJalanImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SurveiKondisiJalanController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Survey Kondisi Jalan', ['create', 'store'], ['index'], ['edit', 'update'], ['destroy']);
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
        $surveiKondisiJalan = DB::table('roadroid_trx_survey_kondisi_jalan')->get();
        $users = DB::table('users')->get();
        return view(
            'admin.input_data.survei_kondisi_jalan.index',
            [
                "surveiKondisiJalan" => $surveiKondisiJalan,
                "users" => $users,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ruasJalan = DB::table('master_ruas_jalan')->get();
        return view('admin.input_data.survei_kondisi_jalan.insert', [
            'action' => 'store',
            'ruasJalan' => $ruasJalan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $surveiKondisiJalan = $request->except(["_token"]);
        $surveiKondisiJalan["created_user"] = Auth::user()->id;
        $surveiKondisiJalan['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        DB::table('roadroid_trx_survey_kondisi_jalan')->insert($surveiKondisiJalan);

        $color = "success";
        $msg = "Berhasil Menambah Data Survei Kondisi Jalan";
        return redirect(route('survei_kondisi_jalan.index'))->with(compact('color', 'msg'));
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
        $surveiKondisiJalan = DB::table('roadroid_trx_survey_kondisi_jalan')->where('id', $id)->first();
        return view('admin.input_data.survei_kondisi_jalan.insert', [
            'action' => 'update',
            "surveiKondisiJalan" => $surveiKondisiJalan
        ]);
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
        $surveiKondisiJalan = $request->except(["_token", "_method"]);
        $surveiKondisiJalan["updated_user"] = Auth::user()->id;
        $surveiKondisiJalan['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
        DB::table('roadroid_trx_survey_kondisi_jalan')->where('id', $id)->update($surveiKondisiJalan);

        $color = "success";
        $msg = "Berhasil Mengubah Data Survei Kondisi Jalan";
        return redirect(route('survei_kondisi_jalan.index'))->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $temp = DB::table('roadroid_trx_survey_kondisi_jalan')->where('id', $id)->delete();
        $color = "success";
        $msg = "Berhasil Menghapus Data Survei Kondisi Jalan";
        return redirect(route('survei_kondisi_jalan.index'))->with(compact('color', 'msg'));
    }

    public function import(Request $request)
    {
        //dd($request->is_deleted);
        Excel::import(new SurveiKondisiJalanImport($request->id_ruas_jalan,$request->is_deleted), $request->file('survei_excel'));
        $surveiKondisiJalan = DB::table('roadroid_trx_survey_kondisi_jalan')->get();
        $users = DB::table('users')->get();
        $color = "success";
        $msg = "Berhasil Menimport Data Survei Kondisi Jalan";
        if($request->is_deleted == "Y")
        $msg = "Berhasil Menghapus Data Survei Kondisi Jalan";
        return redirect(route('survei_kondisi_jalan.index'))->with(compact('color', 'msg', 'surveiKondisiJalan', 'users'));
    }
}
