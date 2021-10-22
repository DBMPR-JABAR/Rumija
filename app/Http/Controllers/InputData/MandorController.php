<?php

namespace App\Http\Controllers\InputData;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MandorController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Mandor', [], ['index'], [], []);
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
        //
        $temp=[];
        $users = User::get();
        $userssup = "";
        $usersuptd = "";
        // dd($users->internalRole->keterangan);
        foreach($users as $no => $data){
            $cek =$data->internalRole->role ?? '';
            if($cek != null && str_contains($cek,'Mandor')){
                $temp[]=$data;
            }

        }
        $users = $temp;
        if(Auth::user()->internalRole->uptd){
            $temp=[];
            $tempsup=[];

            // $uptd_id = str_replace('uptd','',Auth::user()->internalRole->uptd);
            foreach($users as $no => $data){
                if($data->internalRole->uptd ==Auth::user()->internalRole->uptd){
                    if(Auth::user()->sup_id != null && $data->sup_id == Auth::user()->sup_id){
                        $tempsup[]=$data;
                    }else{
                        $temp[]=$data;
                    }

                }
                // else if(Auth::user()->sup_id == null){
                //         $temp[]=$data;
                // }
            }
            $usersuptd = $temp;
            $userssup = $tempsup;

        }
        $roles = DB::table('user_role')->get();
        return view('admin.input.mandor.index',compact('users','userssup','usersuptd','roles'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
