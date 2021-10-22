<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;



class DetailUserController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Profil', [], ['show'], ['edit','update'], []);
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
        if($id != Auth::user()->id){
            $color = "danger";
            $msg = "Somethink when wrong!";
            return back()->with(compact('color', 'msg'));
            // return redirect('admin/user/profile/'. auth()->user()->id)->with(['error' => 'Somethink when wrong!']);
        }else{

            $profile=User::find($id);
            // $profile = DB::table('user_pegawai')
            // ->leftJoin('users', 'users.id', '=', 'user_pegawai.user_id')
            // ->leftJoin('user_role', 'user_role.id', '=', 'users.internal_role_id')->where('user_pegawai.user_id',$id)->first();

            // dd($profile);
            $kota = "";
            $provinsi = "";
            if($profile->pegawai){
                $kota = $profile->pegawai->city_id ? DB::table('indonesia_cities')->where('id', $profile->pegawai->city_id)->pluck('name')->first() :'';
                $provinsi = $profile->pegawai->province_id ? DB::table('indonesia_provinces')->where('id', $profile->pegawai->province_id)->pluck('name')->first()  :'';
            }
            $profile->provinsi=$provinsi;
            $profile->kota=$kota;
            // dd($profile);
            return view('admin.master.user.show',compact('profile'));
        }
    }
    public function showall($id)
    {
        //

            $profile = DB::table('user_pegawai')
            ->leftJoin('users', 'users.id', '=', 'user_pegawai.user_id')
            ->leftJoin('user_role', 'user_role.id', '=', 'users.internal_role_id')
            ->where('user_pegawai.user_id',$id)->first();

            if($profile){
                $kota = $profile->city_id ? DB::table('indonesia_cities')->where('id', $profile->city_id)->pluck('name')->first() :'';
                $provinsi = $profile->province_id? DB::table('indonesia_provinces')->where('id', $profile->province_id)->pluck('name')->first()  :'';
                $profile->provinsi=$provinsi;
                $profile->kota=$kota;
            }

            $profile->ruas = DB::table('user_master_ruas_jalan')
            ->where('user_id',$profile->user_id)->leftJoin('master_ruas_jalan','master_ruas_jalan.id','=','user_master_ruas_jalan.master_ruas_jalan_id')->get();

            return view('admin.master.user.show',compact('profile'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($id != Auth::user()->id){
            $color = "danger";
            $msg = "Somethink when wrong!";
            return back()->with(compact('color', 'msg'));
            // return redirect('admin/user/profile/'. auth()->user()->id)->with(['error' => 'Somethink when wrong!']);
        }else{
            $profile = DB::table('user_pegawai')->where('user_id',$id)->first();
            $cities ="";
            $provinces ="";
            $provinces =  DB::table('indonesia_provinces')->pluck('name', 'id');
            if($profile){
                // dd($profile);
                if($profile->city_id != null){
                    $cities =  DB::table('indonesia_cities')->where('province_id', $profile->province_id)->pluck('name', 'id');
                }
            }
            // dd($cities);
            $user = User::find($id);

            $sup = DB::table('utils_sup');
            if(Auth::user()->internalRole->uptd){
                $uptd_id = str_replace('uptd','',Auth::user()->internalRole->uptd);
                $sup = $sup->where('uptd_id',$uptd_id);
            }
            $sup = $sup->get();


            $role = DB::table('user_role');
            $role = $role->where('is_active', '1');
            if(Auth::user()->internalRole->uptd){
                // $uptd_id = str_replace('uptd','',Auth::user()->internalRole->uptd);
                $role = $role->where('uptd',Auth::user()->internalRole->uptd);
            }
            $role = $role->get();
            // dd($role);

            return view('admin.master.user.edit_detail_user',compact('profile','user','sup','role','provinces','cities'));
        }
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
        if($id != Auth::user()->id){
            $color = "danger";
            $msg = "Somethink when wrong!";
            return back()->with(compact('color', 'msg'));
            // return redirect('admin/user/profile/'. auth()->user()->id)->with(['error' => 'Somethink when wrong!']);
        }else{

            // dd($request);
            $this->validate($request,[
                'nama'=> 'required',
                'tgl_lahir'=> 'required',
                'tmp_lahir'=> 'required',
                'jenis_kelamin'=> 'required',
                'no_tlp'=> 'numeric|digits_between:8,13',
                'no_tlp_rumah'=> '',
                'sup_id'=> '',
                'tgl_mulai_kerja'=> '',
                'sekolah'=> '',
                'jejang'=> '',
                'jurusan_pendidikan'=> '',
                'provinsi'=> '',
                'kota'=> '',
                'kode_pos'=> '',
                'alamat'=> '',
                'agama'=> 'required',
                'ruas_jalan'=> '',
            ]);
                // dd($id);
                $updateprofile = DB::table('user_pegawai')
                ->where('user_id', $id)->first();
                if(isset($updateprofile)){
                    $validator = Validator::make($request->all(), [
                        'no_pegawai' => Rule::unique('user_pegawai', 'no_pegawai')->ignore($updateprofile->id)
                    ]);

                }else{
                    $validator = Validator::make($request->all(), [
                        'no_pegawai' => Rule::unique('user_pegawai', 'no_pegawai')
                    ]);
                }
               
                if ($validator->fails()) {
                    $color = "danger";
                    $msg = $validator->messages()->first();
                    return redirect(route('editProfile', $id))->with(compact('color', 'msg'));
                }
                // $temp = explode(",",$request->input('sup_id'));

            $userprofile['nama'] = $request->input('nama');
            $userprofile['no_pegawai']     = $request->input('no_pegawai');
            $userprofile['tgl_lahir']   = $request->input('tgl_lahir');
            $userprofile['tmp_lahir']   = $request->input('tmp_lahir');
            $userprofile['agama']  = $request->input('agama');
            $userprofile['jenis_kelamin'] = $request->input('jenis_kelamin');
            $userprofile['no_tlp']  = $request->input('no_tlp');
            $userprofile['no_tlp_rumah']  = $request->input('no_tlp_rumah');
            $userprofile['tgl_mulai_kerja']  = $request->input('tgl_mulai_kerja');
            $userprofile['sekolah']  = $request->input('sekolah');
            $userprofile['jejang']  = $request->input('jejang');
            $userprofile['jurusan_pendidikan']  = $request->input('jurusan_pendidikan');
            $userprofile['province_id']  = $request->input('provinsi');
            $userprofile['city_id']  = $request->input('kota');
            $userprofile['kode_pos']  = $request->input('kode_pos');
            $userprofile['alamat']  = $request->input('alamat');
            $updatetouser = null;
            // dd($request->input('sup_id'));

            if($request->input('sup_id') != null){
                $getsup = DB::table('utils_sup')->where('kd_sup',$request->input('sup_id'))->select('id','name')->first();
                $userupdat['sup_id']= $getsup->id;
                $userupdat['sup']= $getsup->name;
                $updatetouser = DB::table('users')->where('id', $id)->update($userupdat);
            }else{
                $userupdat['sup_id']= null;
                $userupdat['sup']= null;
                $updatetouser = DB::table('users')->where('id', $id)->update($userupdat);
            }
             //beneriiiiiiiiin
            if($updateprofile){
                $updateprofile = DB::table('user_pegawai')->where('id',$updateprofile->id)->update($userprofile);
            }else{
                $userprofile['user_id']  = $id;
                $updateprofile = DB::table('user_pegawai')->insert($userprofile);
            }
            // echo $request->input('sup_id');
            // dd($temp);
            $updateruas = DB::table('user_master_ruas_jalan')->where('user_id',$id)->delete();
            if($request->ruas_jalan){
                foreach($request->ruas_jalan as $data){
                    $userRuas['user_id'] =$id;
                    $userRuas['master_ruas_jalan_id'] =$data;
                    $updateruas =  DB::table('user_master_ruas_jalan')->insert($userRuas);
                }
            }
            if($updateprofile || $updatetouser || $updateruas){
                $updatenama['name'] = $request->input('nama');
                $updatetouser = DB::table('users')->where('id', $id)->update($updatenama);
                //redirect dengan pesan sukses

                $color = "success";
                $msg = "Data Berhasil Diupdate!";
                return redirect(route('editProfile', $id))->with(compact('color','msg'));
            }else{
                //redirect dengan pesan error
                $color = "danger";
                $msg = "Data Gagal Diupdate!";
                return redirect(route('editProfile', $id))->with(compact('color', 'msg'));

            }
        }
    }

    public function updateaccount(Request $request, $id)
    {
        //
        if($id != Auth::user()->id){
            $color = "danger";
            $msg = "Somethink when wrong!";
            return back()->with(compact('color', 'msg'));
            // return redirect('admin/user/profile/'. auth()->user()->id)->with(['error' => 'Somethink when wrong!']);
        }else{
            $datai=["email" => Auth::user()->email,
                    "password" => $request->input('password_lama')];
            $exist = Auth::attempt($datai);
            // dd(bcrypt($request->input('password_lama')));

            // echo Auth::user()->password;
            $validator = Validator::make($request->all(), [
                'email' => Rule::unique('users', 'email')->ignore($id),
                'password'   => 'confirmed'
            ]);
            if ($validator->fails()) {
                $color = "danger";
                $msg = $validator->messages()->first();
                return redirect(route('editProfile', $id))->with(compact('color', 'msg'));
            }

            // $this->validate($request,[
            //     'email' => 'required|email',
            //     'password'   => 'confirmed'
            // ]);
            if($request->input('password') != ""){
                if($exist){
                    $useraccount['password']     = bcrypt($request->input('password'));
                }else{
                    $color = "danger";
                    $msg ="Password Lama Salah ";
                    return redirect(route('editProfile', $id))->with(compact('color', 'msg'));
                }
            }
            $useraccount['email'] = $request->input('email');
            // dd($useraccount['password']);
            $updateaccount = DB::table('users')
            ->where('id', $id)->update($useraccount);
            if($updateaccount){
                //redirect dengan pesan sukses
                $color = "success";
                $msg = "Akun Berhasil Diupdate!";
                return redirect(route('editProfile', $id))->with(compact('color','msg'));
            }else{
                //redirect dengan pesan error
                $color = "danger";
                $msg = "Akun Gagal Diupdate!";
                return redirect(route('editProfile', $id))->with(compact('color', 'msg'));
            }
        }
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
