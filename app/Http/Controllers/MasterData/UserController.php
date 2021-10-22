<?php

namespace App\Http\Controllers\MasterData;

use App\User;
use App\Model\Transactional\RuasJalan;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $roles = [];
        $user = setAccessBuilder('User', ['storePermission', 'storeMenu'], ['getPermission'], ['editPermission', 'updatePermission', 'editMenu', 'updateMenu'], ['destroyMenu', 'destroyPermission']);
        $role_akses = setAccessBuilder('Role Akses', ['createRoleAccess', 'storeRoleAccess', 'createRoleAcces'], ['getDaftarRoleAkses', 'detailRoleAkses', 'getDataRoleAkses'], ['editRoleAccess', 'updateRoleAccess', 'updateDataRoleAkses'], ['deleteRoleAkses']);
        $manajemen_user = setAccessBuilder('Manajemen User', ['store'], ['index', 'getUser', 'getUserAPI', 'detailUser'], ['edit', 'update'], ['delete']);
        $user_role = setAccessBuilder('User Role', ['createUserRole'], ['getDataUserRole', 'detailUserRole', 'getUserRoleData'], ['updateUserRole'], ['deleteUserRole']);
        $roles = array_merge($roles, $user);
        $roles = array_merge($roles, $role_akses);
        $roles = array_merge($roles, $manajemen_user);
        $roles = array_merge($roles, $user_role);
        foreach ($roles as $role => $permission) {
            $this->middleware($role)->only($permission);
        }
    }

    public function index()
    {
        $users = User::all();
        // if(Auth::user()->internalRole->uptd){
        //     $uptd_id = str_replace('uptd','',Auth::user()->internalRole->uptd);
        //     $laporan = $users->where('uptd_id',$uptd_id);
        // }
        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $sup = $sup->where('uptd_id', $uptd_id);
        }
        $sup = $sup->get();

        $role = DB::table('user_role');
        $role = $role->where('is_active', '1');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $role = $role->where('uptd_id', $uptd_id);
        }
        $role = $role->get();

        return view('admin.master.user.index', compact('users', 'sup', 'role'));
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users',
        ]);

        if ($validator->fails()) {
            $color = "danger";
            $msg = "Email telah terdaftar";
            // Input Log Activity User
            
            storeLogActivity(declarLog(1, 'Manajemen User', $request->email ));
            return back()->with(compact('color', 'msg'));
        }

        $user['name'] = $request->name;
        $user['email'] = $request->email;
        $user['password'] = Hash::make($request->password);
        $user['role'] = "internal";
        $user['email_verified_at'] = date('Y-m-d H:i:s');
        $user['internal_role_id'] = $request->internal_role_id;
        $user['sup'] = $request->sup;

        $id = DB::table('users')->insertGetId($user);

        $userPegawai['no_pegawai'] = $request->no_pegawai;
        $userPegawai['nama'] = $request->name;
        $userPegawai['no_tlp'] = $request->no_tlp;
        $userPegawai['user_id'] = $id;
        $userPegawai['created_at'] = date('Y-m-d H:i:s');
        $userPegawai['created_by'] = Auth::user()->id;

        DB::table('user_pegawai')->insert($userPegawai);
        $color = "success";
        $msg = "Berhasil Menambah Data User";
        
        storeLogActivity(declarLog(1, 'Manajemen User', $request->email, 1 ));
        return back()->with(compact('color', 'msg'));
    }

    public function edit($id)
    {
        $user = User::where('users.id', $id)
            ->leftjoin('user_pegawai', 'user_pegawai.user_id', '=', 'users.id')->select('users.*', 'user_pegawai.no_pegawai', 'user_pegawai.no_tlp')->first();

        $sup = DB::table('utils_sup');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $sup = $sup->where('uptd_id', $uptd_id);
        }
        $sup = $sup->get();

        $role = DB::table('user_role');
        $role = $role->where('is_active', '1');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $role = $role->where('uptd', Auth::user()->internalRole->uptd);
        }
        $role = $role->get();
        $users = User::find($id);
        // dd(in_array(32,array_column( $users->ruas->toArray(), 'id')));
        // dd($users->ruas);
        return view('admin.master.user.edit', compact('user', 'sup', 'role', 'users'));
    }


    public function update(Request $request)
    {
        $tempAct = [
            'activity' => 'Add Item',
            'target' => 'User',
            'description' => 'Gagal Menambahkan Data User '.$request->email,
            'status' => 'error'
        ];
        
        $validator = Validator::make($request->all(), [
            'email' => Rule::unique('users', 'email')->ignore($request->id),
        ]);
        if ($validator->fails()) {
            $color = "danger";
            $msg = "Email telah terdaftar ";
            storeLogActivity(declarLog(2, 'Manajemen User', $request->email ));

            return back()->with(compact('color', 'msg'));
        }
        // dd($request->ruas_jalan);
        $user['email'] = $request->email;
        if ($request->role == 'internal') {
            $getsup = DB::table('utils_sup')->where('kd_sup', $request->input('sup_id'))->select('id', 'name')->first();
            if($getsup){
                $user['sup_id'] = $getsup->id;
                $user['sup'] = $getsup->name;

            }
        }
        $user['role'] = $request->role;

        $userId = $request->id;
        $user['name'] = $request->name;
        $user['internal_role_id'] = $request->internal_role_id;

        $user['blokir'] = $request->blokir;

        if ($request->password != "") {
            $user['password'] = Hash::make($request->password);
        }
        DB::table('users')->where('id', $userId)->update($user);
        $userPegawai['no_pegawai'] = $request->no_pegawai;
        $userPegawai['nama'] = $request->name;
        $userPegawai['no_tlp'] = $request->no_tlp;
        $userPegawai['updated_at'] = date('Y-m-d H:i:s');
        $userPegawai['created_by'] = Auth::user()->id;

        // dd($userPegawai);
        $user_peg = DB::table('user_pegawai')->where('user_id', $userId);
        if ($user_peg->exists()) {
            $user_peg = $user_peg->update($userPegawai);
        } else {
            $userPegawai['user_id'] = $userId;
            $user_peg = $user_peg->insert($userPegawai);
        }

        DB::table('user_master_ruas_jalan')->where('user_id', $request->id)->delete();
        if ($request->ruas_jalan) {
            foreach ($request->ruas_jalan as $data) {
                $userRuas['user_id'] = $request->id;
                $userRuas['master_ruas_jalan_id'] = $data;
                DB::table('user_master_ruas_jalan')->insert($userRuas);
            }
        }

        $color = "success";
        $msg = "Berhasil Memperbaharui Data User";
        storeLogActivity(declarLog(2, 'Manajemen User', $request->email,1 ));

        return back()->with(compact('color', 'msg'));
    }

    public function delete($id)
    {
        $user = DB::table('users');
        $user->where('id',$id)->update(array('is_delete' => 1));
        // dd($user->first()->email);
        DB::table('user_pegawai')->where('user_id', $id)->update(array('is_delete' => 1));

        $color = "success";
        $msg = "Berhasil Menghapus Data User";
        storeLogActivity(declarLog(3, 'Manajemen User', $user->first()->email,1 ));

        return redirect(route('getMasterUser'))->with(compact('color', 'msg'));
    }
    public function restore($id)
    {
        $user = DB::table('users');
        $user->where('id',$id)->update(array('is_delete' => 0));
        // dd($user->first()->email);
        DB::table('user_pegawai')->where('user_id', $id)->update(array('is_delete' => 0));

        $color = "success";
        $msg = "Berhasil Mengembalikan Data User";
        storeLogActivity(declarLog(4, 'Manajemen User', $user->first()->email,1 ));

        return redirect(route('getMasterUser'))->with(compact('color', 'msg'));
    }
    public function deletepermanent($id)
    {
        $user = DB::table('users')->where('id',$id);
        storeLogActivity(declarLog(3, 'Manajemen User', $user->first()->email,1 ));
        $user->delete();
        // dd($user->first()->email);
        DB::table('user_pegawai')->where('user_id', $id)->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data User Secara Permanen";

        return redirect(route('getMasterUser'))->with(compact('color', 'msg'));
    }

    public function getUser()
    {
        $users = DB::table('users')->where('is_delete',null)->orWhere('is_delete',0)->get();
        $roles = DB::table('user_role');
        if (Auth::user()->internalRole->uptd) {
            $roles = $roles->where('uptd', Auth::user()->internalRole->uptd);
        }
        $roles = $roles->get();
        // dd($roles);
        return view('admin.master.user.manajemen.index', compact('users', 'roles'));
    }
    public function getUserTrash()
    {
        $users = DB::table('users')->where('is_delete',1)->get();
        $roles = DB::table('user_role');
        if (Auth::user()->internalRole->uptd) {
            $roles = $roles->where('uptd', Auth::user()->internalRole->uptd);
        }
        $roles = $roles->get();
        // dd($roles);
        return view('admin.master.user.manajemen.index', compact('users', 'roles'));
    }

    public function getUserAPI()
    {
        $response = [
            'status' => 'false',
            'data' => []
        ];
        $users = DB::table('users');
        if (Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $laporan = $users->where('uptd_id', $uptd_id);
        }
        $laporan = $laporan->get();
        $response['data'] = $laporan;
        return response()->json($response, 200);
    }

    public function detailUser($id)
    {
        $users = DB::table('users')->where('id', $id)->get();
        return view('admin.master.user.manajemen.detail', [
            'users' => $users
        ]);
    }


    public function getDaftarRoleAkses()
    {
        $roleExist = DB::table('master_grant_role_aplikasi')->distinct()->pluck('internal_role_id');

        $user_role = DB::table('user_role as a')
            ->whereNotIn('id', $roleExist)
            ->get();


        $user_role_list = DB::table('user_role as a')
            ->distinct()
            ->join('master_grant_role_aplikasi as b', 'a.id', '=', 'b.internal_role_id')
            ->join('utils_role_access as c', 'b.id', '=', 'c.master_grant_role_aplikasi_id')
            ->select('a.uptd', 'a.role', 'a.id as role_id', DB::raw('GROUP_CONCAT( b.menu SEPARATOR ", ") as menu_user'), DB::raw('GROUP_CONCAT( b.id SEPARATOR ", ") as id_menu'), DB::raw('GROUP_CONCAT( c.role_access SEPARATOR ", ") as role_access'))
            ->where('b.menu', 'NOT LIKE', '%disposisi%')
            ->groupBy('a.role')
            ->orderBy('a.id')
            ->get();
        // dd($user_role_list);
        $alldata = array();
        $counter = 0;
        // dd($user_role_list);
        foreach ($user_role_list as $data) {
            $permiss = array();
            $men = explode(",", $data->menu_user);
            $aks = explode(",", $data->role_access);
            $counting = 0;

            foreach ($men as $no) {
                $temp = $no . '.' . $aks[$counting];
                array_push($permiss, $temp);
                $counting++;
            }
            $permission = implode(",", $permiss);
            $alldata[$counter]['uptd'] = $data->uptd;
            $alldata[$counter]['role_id'] = $data->role_id;
            $alldata[$counter]['id_menu'] = $data->id_menu;
            $alldata[$counter]['role'] = $data->role;
            $alldata[$counter]['permissions'] = $permission;
            $counter++;
        }

        $internal = DB::table('master_grant_role_aplikasi as a')
            ->select('a.id', 'internal_role_id')
            ->where('menu', 'NOT LIKE', '%Disposisi%')
            ->groupBy('internal_role_id')
            ->get();
        // dd($alldata);
        $temporaridata = $alldata;
        $tempdata = [];
        foreach ($alldata as $dataa) {

            foreach (explode(", ", $dataa['id_menu']) as $data) {
                $uptd_access = DB::table('utils_role_access_uptd as a')
                    ->distinct()
                    ->select(DB::raw('GROUP_CONCAT(a.uptd_name SEPARATOR ", ") as uptd_akses'))
                    ->where('a.master_grant_role_aplikasi_id', $data)
                    ->orderBy('a.master_grant_role_aplikasi_id')
                    ->get();
                $uptd_akses[] = $uptd_access[0]->uptd_akses;
                break;
            }
            if (Auth::user()->internalRole->uptd != null && $dataa['uptd'] == Auth::user()->internalRole->uptd) {
                $uptd_id = str_replace('uptd', '', $dataa['uptd']);
                $dataa['uptd_aks'] = $uptd_id;

                $tempdata[] = $dataa;
            }
        }
        // dd($tempdata);
        if ($tempdata != null) {
            $temporaridata = $tempdata;
        }
        $alldata = $temporaridata;
        $menu = DB::table('master_grant_role_aplikasi as a')
            ->distinct()
            ->where('menu', 'NOT LIKE', '%Disposisi%')
            ->groupBy('a.menu')
            ->get();
        // print_r($uptd_akses);
        // dd($uptd_akses);
        // dd($alldata);
        return view(
            'admin.master.user.role_akses',
            [
                'user_role' => $user_role,
                'menu_access' => $alldata,
                'uptd_access' => $uptd_akses,
                'user_role_list' => $user_role_list,
                'menu' => $menu
            ]
        );
    }

    public function createRoleAccess()
    {
        $temp = array();
        $roleExist = DB::table('master_grant_role_aplikasi')->distinct()->pluck('internal_role_id');
        foreach ($roleExist as $data) {
            // echo $data."<br>";
            if ($data)
                array_push($temp, $data);
        }
        $menu = DB::table('master_grant_role_aplikasi as a')
            ->distinct()
            ->where('menu', 'NOT LIKE', '%Disposisi%')
            ->groupBy('a.menu')
            ->get();
        $user_role = DB::table('user_role as a')
            ->whereNotIn('id', $temp)
            ->get();

        // dd($menu);
        return view('admin.master.user.role_akses_add', compact('menu', 'user_role'));
    }

    public function storeRoleAccess(Request $request)
    {
        $this->validate($request, [
            'uptd_access' => 'required',
            'user_role' => 'required',
            'menu' => 'required',

        ]);
        $roleExist = DB::table('master_grant_role_aplikasi')->distinct()->pluck('internal_role_id');
        $role_access = array();
        $data = ([
            'user_role' => $request->user_role,
            'menu' => $request->menu,
            'uptd_access' => $request->uptd_access
        ]);

        $master_grant['internal_role_id']  = $data['user_role'];
        //    echo $data['user_role'];
        for ($i = 0; $i < count($data['menu']); $i++) {
            $ex = explode(".", $data['menu'][$i]);
            array_push(
                $role_access,
                [
                    'menu' => $ex[0],
                    'access' => $ex[1],
                ]
            );
            $cek = DB::table('master_grant_role_aplikasi')
                ->where(['internal_role_id' => $data['user_role'], 'menu' => $ex[0]])->exists();
            // dd( $cek);

            // dd( $cek);
            if ($cek == false) {

                $master_grant['menu'] = $ex[0];
                $master_grant['created_date'] = date('Y-m-d H:i:s');
                $master_grant['pointer'] = 0;
                DB::table('master_grant_role_aplikasi')->insert($master_grant);
            } else {

                $masters = DB::table('master_grant_role_aplikasi as a')
                    ->select('a.id')
                    ->where(['a.internal_role_id' => $data['user_role'], 'a.menu' => $ex[0]])
                    ->where('a.pointer', 1)
                    ->get();
                $masers_id = $masters[0]->id;
                $pointer['pointer'] = 0;
                DB::table('master_grant_role_aplikasi')->where('id', $masers_id)->update($pointer);
            }
            $master = DB::table('master_grant_role_aplikasi as a')
                ->select('a.id')
                ->where('a.internal_role_id', $data['user_role'])
                ->where('a.pointer', 0)
                ->get();

            $maser_id = $master[0]->id;
            $role_access_list['master_grant_role_aplikasi_id'] = $maser_id;
            $role_access_list['role_access'] = $ex[1];
            DB::table('utils_role_access')->insert($role_access_list);
            $pointer['pointer'] = 1;
            DB::table('master_grant_role_aplikasi')->where('id', $maser_id)->update($pointer);
            if ($cek == false) {
                for ($j = 0; $j < count($data['uptd_access']); $j++) {
                    $uptd_access_list['uptd_name'] = $data['uptd_access'][$j];
                    $uptd_access_list['master_grant_role_aplikasi_id'] = $maser_id;
                    DB::table('utils_role_access_uptd')->insert($uptd_access_list);
                }
            }
        }
        $color = "success";
        $msg = "Berhasil Menambah Data Grant Access Role Aplikasi";
        return redirect()->route('getRoleAkses')->with(compact('color', 'msg'));
    }

    public function editRoleAccess($id)
    {
        $roleExist = DB::table('master_grant_role_aplikasi')->distinct()->pluck('internal_role_id');

        $user_role_list = DB::table('user_role as a')
            ->distinct()
            ->select('a.role', 'a.id as role_id', DB::raw('GROUP_CONCAT(b.menu SEPARATOR ", ") as menu_user'), DB::raw('GROUP_CONCAT(b.id SEPARATOR ", ") as id_menu'))
            ->join('master_grant_role_aplikasi as b', 'a.id', '=', 'b.internal_role_id')
            // ->join('utils_role_access as c','b.id','=','c.master_grant_role_aplikasi_id')
            ->where('a.id', $id)
            ->where('b.menu', 'NOT LIKE', '%disposisi%')
            ->groupBy('a.role')
            ->orderBy('b.menu')
            ->get();
        $alldata = array();
        // dd($user_role_list);
        $counter = 0;
        foreach ($user_role_list as $data) {
            $permiss = array();
            $permissuser = array();
            $roleaccessuser = [];

            $men = explode(", ", $data->menu_user);
            // $aks = explode(", ",$data->role_access);
            $id_men = explode(", ", $data->id_menu);
            // dd($id_men);
            // dd($aks);
            // dd($men);


            $counting = 0;
            foreach ($id_men as $now) {
                $id_menn = DB::table('master_grant_role_aplikasi')->where('id', $now)
                    ->select('menu', 'id')->first();
                array_push($permissuser, $id_menn);

                $role_access = DB::table('utils_role_access')->where('master_grant_role_aplikasi_id', $now)
                    ->pluck('role_access');
                foreach ($role_access as $items) {
                    $roleaccessuser[$now][] = [$items];
                }
                // dd($role_access);
            }
            // dd($permissuser);
            // dd($roleaccessuser);

            foreach ($permissuser as $da => $no) {
                for ($i = 0; $i < count($roleaccessuser[$no->id]); $i++) {
                    $temp = $no->menu . '.' . implode($roleaccessuser[$no->id][$i]);
                    array_push($permiss, $temp);
                }
            }
            // dd($permiss);
            $permission = implode(", ", $permiss);
            $alldata['role_id'] = $data->role_id;
            $alldata['role'] = $data->role;
            $alldata['id_menu'] = $data->id_menu;
            $alldata['permissions'] = $permission;
            $counter++;
        }
        // dd($alldata);
        // dd($id_men);
        // $menu = DB::table('master_grant_role_aplikasi as a')
        // ->distinct()
        // ->where('menu','NOT LIKE', '%Disposisi%')
        // ->groupBy('a.menu')
        // ->get();
        $menu = DB::table('permissions')
            ->leftjoin('menu', 'menu.id', '=', 'permissions.menu_id')->select('permissions.*', 'menu.nama as nama_menu')
            ->orderBy('permissions.menu_id')->get();
        $cekopoint = DB::table('permissions')
            ->leftjoin('menu', 'menu.id', '=', 'permissions.menu_id')->select('permissions.*', 'menu.nama as nama_menu')
            ->orderBy('permissions.menu_id')->groupBy('permissions.menu_id')->get();
        // dd($menu);


        $tempi1 = array();
        $tempi2 = [];

        foreach ($menu as $item => $as) {
            // if($as->view_user_id > 0 && $as->view_user_id != Auth::user()->id){
            //     break;
            // }
            if (!$as->view_user_id) {

                $tempi2[] = ['nama' => $as->nama . '.Create', 'nama_menu' => $as->nama_menu, 'view_user_id' => $as->view_user_id];
                $tempi2[] = ['nama' => $as->nama . '.View', 'nama_menu' => $as->nama_menu, 'view_user_id' => $as->view_user_id];
                $tempi2[] = ['nama' => $as->nama . '.Update', 'nama_menu' => $as->nama_menu, 'view_user_id' => $as->view_user_id];
                $tempi2[] = ['nama' => $as->nama . '.Delete', 'nama_menu' => $as->nama_menu, 'view_user_id' => $as->view_user_id];
            } else if ($as->view_user_id && $as->view_user_id == Auth::user()->id) {
                $tempi2[] = ['nama' => $as->nama . '.Create', 'nama_menu' => $as->nama_menu, 'view_user_id' => $as->view_user_id];
                $tempi2[] = ['nama' => $as->nama . '.View', 'nama_menu' => $as->nama_menu, 'view_user_id' => $as->view_user_id];
                $tempi2[] = ['nama' => $as->nama . '.Update', 'nama_menu' => $as->nama_menu, 'view_user_id' => $as->view_user_id];
                $tempi2[] = ['nama' => $as->nama . '.Delete', 'nama_menu' => $as->nama_menu, 'view_user_id' => $as->view_user_id];
            }
        }

        // dd($cekopoint);
        // dd($tempi2);
        $user_role = DB::table('user_role as a')
            ->where('id', $id)
            ->get();
        $temporari = explode(", ", $alldata['permissions']);
        $alldata['permissions'] = $temporari;
        $alldata['menu_test'] = $tempi2;
        // dd($alldata['menu_test']);

        foreach (explode(",", $alldata['id_menu']) as $data) {
            $uptd_access = DB::table('utils_role_access_uptd as a')
                ->distinct()
                ->select(DB::raw('GROUP_CONCAT(a.uptd_name SEPARATOR ", ") as uptd_akses'))
                ->where('a.master_grant_role_aplikasi_id', $data)
                ->orderBy('a.master_grant_role_aplikasi_id')
                ->get();
            $uptd_akses[] = $uptd_access[0]->uptd_akses;
        }
        $alldata['uptd_akses'] = explode(", ", $uptd_akses[0]);
        $int = array();
        foreach ($alldata['uptd_akses'] as $tem) {
            array_push($int, (int) $tem);
        }
        $alldata['uptd_akses'] = $int;
        //    dd($alldata);
        return view('admin.master.user.role_akses_edit', compact('menu', 'user_role', 'alldata', 'cekopoint'));
    }

    public function updateRoleAccess(Request $request, $id)
    {
        $this->validate($request, [
            'uptd_access' => 'required',
            'user_role' => 'required',
            'menu' => 'required',

        ]);
        // dd($request->menu);
        // dd($request);
        // dd($data);
        //Delete data
        $menu = DB::table('master_grant_role_aplikasi')
            ->where('internal_role_id', $id)
            ->get();
        // dd($menu);
        for ($i = 0; $i < count($menu); $i++) {
            $role_access = DB::table('utils_role_access')
                ->where('master_grant_role_aplikasi_id', $menu[$i]->id);
            $uptd_access = DB::table('utils_role_access_uptd')
                ->where('master_grant_role_aplikasi_id', $menu[$i]->id);
            $role_access->delete();
            $uptd_access->delete();
        }

        $role_akses = DB::table('master_grant_role_aplikasi')
            ->where('internal_role_id', $id);
        $role_akses->delete();
        // dd($request);
        //Store data

        // $roleExist = DB::table('master_grant_role_aplikasi')->distinct()->pluck('internal_role_id');
        $role_access = array();
        $data = ([
            'user_role' => $request->user_role,
            'menu' => $request->menu,
            'uptd_access' => $request->uptd_access
        ]);
        // dd($data);
        $master_grant['internal_role_id']  = $data['user_role'];
        // echo $data['user_role'];
        for ($i = 0; $i < count($data['menu']); $i++) {
            $ex = explode(".", $data['menu'][$i]);
            array_push(
                $role_access,
                [
                    'menu' => $ex[0],
                    'access' => $ex[1],
                ]
            );
            $cek = DB::table('master_grant_role_aplikasi')
                ->where(['internal_role_id' => $data['user_role'], 'menu' => $ex[0]])->exists();

            if ($cek == false) {

                $master_grant['menu'] = $ex[0];
                $master_grant['created_date'] = date('Y-m-d H:i:s');
                $master_grant['pointer'] = 0;
                // dd( $master_grant);
                DB::table('master_grant_role_aplikasi')->insert($master_grant);
            } else {

                $masters = DB::table('master_grant_role_aplikasi as a')
                    ->select('a.id')
                    ->where(['a.internal_role_id' => $data['user_role'], 'a.menu' => $ex[0]])
                    ->where('a.pointer', 1)
                    ->get();
                $masers_id = $masters[0]->id;
                $pointer['pointer'] = 0;
                DB::table('master_grant_role_aplikasi')->where('id', $masers_id)->update($pointer);
            }
            $master = DB::table('master_grant_role_aplikasi as a')
                ->select('a.id')
                ->where('a.internal_role_id', $data['user_role'])
                ->where('a.pointer', 0)
                ->get();

            $maser_id = $master[0]->id;
            $role_access_list['master_grant_role_aplikasi_id'] = $maser_id;
            $role_access_list['role_access'] = $ex[1];
            DB::table('utils_role_access')->insert($role_access_list);
            $pointer['pointer'] = 1;
            DB::table('master_grant_role_aplikasi')->where('id', $maser_id)->update($pointer);
            if ($cek == false) {
                for ($j = 0; $j < count($data['uptd_access']); $j++) {
                    $uptd_access_list['uptd_name'] = $data['uptd_access'][$j];
                    $uptd_access_list['master_grant_role_aplikasi_id'] = $maser_id;
                    DB::table('utils_role_access_uptd')->insert($uptd_access_list);
                }
            }
        }

        return redirect(route('editRoleAccess', $id))->with('status', 'Berhasil Edit Data Grant Access Role Aplikasi!');
        //    $color = "success";
        //     $msg = "Edit Data Grant Access Role Aplikasi";
        //     return redirect()->route('getRoleAkses')->with(compact('color', 'msg'));

    }

    public function createRoleAkses(Request $request)
    {

        $id_role_access = DB::table('user_role as a')
            ->select('a.id', 'a.role')
            ->where('a.id', $request->user_role)
            ->get();
        for ($i = 0; $i < count($request->menu); $i++) {
            $role_access['menu'] = $request->menu[$i];
            $role_access['internal_role_id']  = $id_role_access[0]->id;
            $role_access['created_date'] = date('Y-m-d H:i:s');
            DB::table('master_grant_role_aplikasi')->insert($role_access);
        }
        $jml_menu = count($request->menu);
        $id_role_access_list = DB::table('master_grant_role_aplikasi as a')
            ->select('a.id')
            ->where('menu', 'NOT LIKE', '%Disposisi%')
            ->orderBy('a.id', 'DESC')
            ->limit($jml_menu)
            ->get();

        for ($i = 0; $i < $jml_menu; $i++) {
            for ($j = 0; $j < count($request->role_access); $j++) {
                $role_access_list['role_access'] = $request->role_access[$j];
                $role_access_list['master_grant_role_aplikasi_id'] = $id_role_access_list[$i]->id;
                DB::table('utils_role_access')->insert($role_access_list);
            }
            for ($j = 0; $j < count($request->uptd_access); $j++) {
                $uptd_access_list['uptd_name'] = $request->uptd_access[$j];
                $uptd_access_list['master_grant_role_aplikasi_id'] = $id_role_access_list[$i]->id;
                DB::table('utils_role_access_uptd')->insert($uptd_access_list);
            }
        }
        $color = "success";
        $msg = "Berhasil Menambah Data Grant Access Role Aplikasi";
        return back()->with(compact('color', 'msg'));
    }

    public function detailRoleAkses($id)
    {

        $user_role_list = DB::table('user_role as a')
            ->distinct()
            ->select('a.role', 'a.id as role_id', DB::raw('GROUP_CONCAT(b.menu SEPARATOR ", ") as menu_user'), DB::raw('GROUP_CONCAT(b.id SEPARATOR ", ") as id_menu'), DB::raw('GROUP_CONCAT(c.role_access SEPARATOR ", ") as role_access'))
            ->join('master_grant_role_aplikasi as b', 'a.id', '=', 'b.internal_role_id')
            ->join('utils_role_access as c', 'b.id', '=', 'c.master_grant_role_aplikasi_id')
            ->where('a.id', $id)
            ->where('b.menu', 'NOT LIKE', '%disposisi%')
            ->groupBy('a.role')
            ->orderBy('a.id')
            ->get();
        $alldata = array();
        $counter = 0;
        foreach ($user_role_list as $data) {
            $permiss = array();
            $men = explode(", ", $data->menu_user);
            $aks = explode(", ", $data->role_access);
            $counting = 0;

            foreach ($men as $no) {
                $temp = $no . '.' . $aks[$counting];
                array_push($permiss, $temp);
                $counting++;
            }
            // dd($user_role_list);
            $permission = implode(", ", $permiss);
            $alldata['role_id'] = $data->role_id;
            $alldata['role'] = $data->role;
            $alldata['id_menu'] = $data->id_menu;
            $alldata['permissions'] = $permission;
            $counter++;
        }

        $menu = DB::table('master_grant_role_aplikasi as a')
            ->distinct()
            ->where('menu', 'NOT LIKE', '%Disposisi%')
            ->groupBy('a.menu')
            ->get();
        $tempi = array();
        foreach ($menu as $item => $as) {
            array_push($tempi, $as->menu . '.Create');
            array_push($tempi, $as->menu . '.View');
            array_push($tempi, $as->menu . '.Update');
            array_push($tempi, $as->menu . '.Delete');
        }
        // dd($menu);
        // dd($tempi);
        $user_role = DB::table('user_role as a')
            ->where('id', $id)
            ->get();

        $alldata['menu'] = $tempi;

        foreach (explode(",", $alldata['id_menu']) as $data) {
            $uptd_access = DB::table('utils_role_access_uptd as a')
                ->distinct()
                ->select(DB::raw('GROUP_CONCAT(a.uptd_name SEPARATOR ", ") as uptd_akses'))
                ->where('a.master_grant_role_aplikasi_id', $data)
                ->orderBy('a.master_grant_role_aplikasi_id')
                ->get();
            $uptd_akses[] = $uptd_access[0]->uptd_akses;
        }
        $alldata['uptd_akses'] = explode(", ", $uptd_akses[0]);
        $int = array();
        foreach ($alldata['uptd_akses'] as $tem) {
            array_push($int, (int) $tem);
        }
        $alldata['uptd_akses'] = $int;
        //    dd($alldata);
        return view('admin.master.user.detail_role_akses', compact('alldata'));
    }
    public function deleteRoleAkses($id)
    {

        $menu = DB::table('master_grant_role_aplikasi')
            ->where('internal_role_id', $id)
            ->get();



        for ($i = 0; $i < count($menu); $i++) {
            $role_access = DB::table('utils_role_access')
                ->where('master_grant_role_aplikasi_id', $menu[$i]->id);

            $uptd_access = DB::table('utils_role_access_uptd')
                ->where('master_grant_role_aplikasi_id', $menu[$i]->id);
            $role_access->delete();
            $uptd_access->delete();
        }

        $role_akses = DB::table('master_grant_role_aplikasi')
            ->where('internal_role_id', $id);
        $role_akses->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Grant Access Role Aplikasi";
        return back()->with(compact('color', 'msg'));
    }
    public function getDataRoleAkses($id)
    {
        $user_role = DB::table('master_grant_role_aplikasi as a')
            ->distinct()
            ->join('user_role as b', 'b.id', '=', 'a.internal_role_id')
            ->select('a.id', 'a.internal_role_id', 'a.menu', 'a.created_date', 'b.role')
            ->where('menu', 'NOT LIKE', '%Disposisi%')
            ->where('a.internal_role_id', $id)
            ->get();
        $role_access = DB::table('utils_role_access as a')
            ->distinct()
            ->select('a.id', 'a.role_access', 'a.master_grant_role_aplikasi_id')
            ->where('a.master_grant_role_aplikasi_id', $id)
            ->get();
        $uptd_access = DB::table('utils_role_access_uptd as a')
            ->distinct()
            ->select('a.id', 'a.uptd_name', 'a.master_grant_role_aplikasi_id')
            ->where('a.master_grant_role_aplikasi_id', $id)
            ->get();
        $user_role_list = DB::table('user_role as a')
            ->distinct()
            ->select('a.role', 'a.id as role_id')
            ->where('a.id', $id)
            ->get();
        return response()->json(["user_role" => $user_role, "role_access" => $role_access, "uptd_access" => $uptd_access, "user_role_list" => $user_role_list], 200);
    }
    public function updateDataRoleAkses(Request $request)
    {
        $menu = DB::table('master_grant_role_aplikasi')
            ->where('internal_role_id', $request->user_role)
            ->get();

        foreach ($menu as $data) {
            $role_access = DB::table('utils_role_access')
                ->where('master_grant_role_aplikasi_id', $data->id);

            $uptd_access = DB::table('utils_role_access_uptd')
                ->where('master_grant_role_aplikasi_id', $data->id);
            $role_access->delete();
            $uptd_access->delete();
        }


        $role_akses = DB::table('master_grant_role_aplikasi')
            ->where('internal_role_id', $request->user_role);
        $role_akses->delete();

        $id_role_access = DB::table('user_role as a')
            ->select('a.id', 'a.role')
            ->where('a.id', $request->user_role)
            ->first();

        foreach ($request->menu as $userMenu) {
            $newrole_access['menu'] = $userMenu;
            $newrole_access['internal_role_id']  = $id_role_access->id;
            $newrole_access['created_date'] = date('Y-m-d H:i:s');
            DB::table('master_grant_role_aplikasi')->insert($newrole_access);
        }
        $jml_menu = count($request->menu);
        $id_role_access_list = DB::table('master_grant_role_aplikasi as a')
            ->select('a.id')
            ->orderBy('a.id', 'DESC')
            ->limit($jml_menu)
            ->get();
        for ($i = 0; $i < $jml_menu; $i++) {
            for ($j = 0; $j < count($request->role_access); $j++) {
                $role_access_list['role_access'] = $request->role_access[$j];
                $role_access_list['master_grant_role_aplikasi_id'] = $id_role_access_list[$i]->id;
                DB::table('utils_role_access')->insert($role_access_list);
            }
            for ($j = 0; $j < count($request->uptd_access); $j++) {
                $uptd_access_list['uptd_name'] = $request->uptd_access[$j];
                $uptd_access_list['master_grant_role_aplikasi_id'] = $id_role_access_list[$i]->id;
                DB::table('utils_role_access_uptd')->insert($uptd_access_list);
            }
        }

        $color = "success";
        $msg = "Berhasil Mengupdate Data Grant Access Role Aplikasi";
        return back()->with(compact('color', 'msg'));
    }

    function getDataUserRole()
    {
        $user_role_list = DB::table('user_role');
        if (Auth::user()->internalRole->uptd) {
            $user_role_list = $user_role_list->where('uptd', Auth::user()->internalRole->uptd);
        }
        $user_role_list = $user_role_list->get();
        // dd($user_role_list);
        return view('admin.master.user.user_role.user_role', compact('user_role_list'));
    }

    public function createUserRole(Request $request)
    {
        // dd($request->user_role);
        $create['role'] = $request->user_role;
        $create['is_superadmin'] = $request->super_admin;
        $create['parent'] = $request->parent;
        $create['keterangan'] = $request->keterangan;
        $create['is_active'] = $request->is_active;
        $create['is_deleted'] = $request->is_deleted;
        $create['uptd'] = $request->uptd;
        $create['created_at'] = date('Y-m-d H:i:s');
        $create['created_by'] = Auth::user()->id;
        $create['parent_id'] = $request->parent;
        DB::table('user_role')->insert($create);
        $color = "success";
        $msg = "Berhasil Menambah Data User Role";

        storeLogActivity(declarLog(1, 'User Role', $request->user_role,1 ));

        return back()->with(compact('color', 'msg'));
    }

    public function detailUserRole($id)
    {
        $user_role = DB::table('user_role as a')
            ->where('id', $id)
            ->get();
        return view('admin.master.user.user_role.detail_user_role', [
            'user_role' => $user_role
        ]);
    }

    public function getUserRoleData($id)
    {
        $user_role = DB::table('user_role')
            ->where('id', $id)
            ->get();
        return response()->json(["user_role" => $user_role], 200);
    }
    public function updateUserRole(Request $request)
    {
        $update['role'] = $request->user_role;
        $update['is_superadmin'] = $request->super_admin;
        $update['parent'] = $request->parent;
        $update['keterangan'] = $request->keterangan;
        $update['is_active'] = $request->is_active;
        $update['is_deleted'] = $request->is_deleted;
        $update['uptd'] = $request->uptd;
        $update['updated_at'] = date('Y-m-d H:i:s');
        $update['updated_by'] = Auth::user()->id;
        $update['parent_id'] = 0;
        DB::table('user_role')->where('id', $request->id)->update($update);
        $color = "success";
        $msg = "Berhasil Mengupdate Data User Role";
        storeLogActivity(declarLog(2, 'User Role', $request->user_role,1 ));

        return back()->with(compact('color', 'msg'));
    }
    public function deleteUserRole($id)
    {
        
        $user_role = DB::table('user_role')->where('id',$id);
        storeLogActivity(declarLog(3, 'User Role', $user_role->first()->role,1 ));
        $user_role->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data User Role";

        return back()->with(compact('color', 'msg'));
    }

    public function getPermission()
    {
        $permission = DB::table('permissions')
            ->leftjoin('menu', 'menu.id', '=', 'permissions.menu_id')->select('permissions.*', 'menu.nama as nama_menu')
            ->orderBy('permissions.menu_id')->get();
        // dd($permission);
        $menu = DB::table('menu')->get();

        return view('admin.master.user.permission.index', compact('permission', 'menu'));
    }

    public function storePermission(Request $request)
    {
        $data['nama'] = $request->nama;
        $data['menu_id'] = $request->menu ?: "";
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] =  $data['created_by'];
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = $data['created_at'];
        if ($request->lihat_admin == "on")
            $data['view_user_id'] = Auth::user()->id;

        // dd($data);
        $menu = DB::table('permissions')->insert($data);
        if ($menu) {
            $color = "success";
            $msg = "Berhasil Menambahkan Data Menu";
        } else {
            $color = "danger";
            $msg = "Gagal Menambahkan Data Menu";
        }
        return back()->with(compact('color', 'msg'));
    }
    public function editPermission($id)
    {
        $permission = DB::table('permissions')
            ->where('id', $id)
            ->get();
        return response()->json(["permission" => $permission], 200);
    }
    public function updatePermission(Request $request)
    {

        $data['menu_id'] = $request->menu ?: "";
        $data['updated_by'] =  Auth::user()->id;
        $data['updated_at'] = Carbon::now();
        if ($request->lihat_admin == "on") {
            $data['view_user_id'] = Auth::user()->id;
        } else
            $data['view_user_id'] = null;


        $menu = DB::table('permissions')->where('id', $request->id)->update($data);
        if ($menu) {
            // dd($request->id);
            $color = "success";
            $msg = "Berhasil Mengedit Data Menu";
        } else {
            $color = "danger";
            $msg = "Gagal Mengedit Data Menu";
        }
        return back()->with(compact('color', 'msg'));
    }
    public function storeMenu(Request $request)
    {
        $data['nama'] = $request->nama;
        // dd($request->nama);
        $menu = DB::table('menu')->insert($data);
        if ($menu) {
            $color = "success";
            $msg = "Berhasil Menambahkan Data Menu";
        } else {
            $color = "danger";
            $msg = "Gagal Menambahkan Data Menu";
        }
        return back()->with(compact('color', 'msg'));
    }
    public function editMenu($id)
    {
        $menu = DB::table('menu')
            ->where('id', $id)
            ->get();
        return response()->json(["menu" => $menu], 200);
    }
    public function updateMenu(Request $request)
    {
        $data['nama'] = $request->nama;
        $menu = DB::table('menu')->where('id', $request->id)->update($data);
        if ($menu) {
            // dd($request->id);
            $color = "success";
            $msg = "Berhasil Mengedit Data Menu";
        } else {
            $color = "danger";
            $msg = "Gagal Mengedit Data Menu";
        }
        return back()->with(compact('color', 'msg'));
    }
    public function destroyMenu($id)
    {
        $menu = DB::table('menu')
            ->where('id', $id)
            ->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Menu";
        return back()->with(compact('color', 'msg'));
    }
    public function destroyPermission($id)
    {
        $menu = DB::table('permissions')
            ->where('id', $id)
            ->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Permission";
        return back()->with(compact('color', 'msg'));
    }
}
