<?php

namespace App\Http\Controllers;

use App\Model\Push\UserPushNotification;
use App\Model\Transactional\Log;
use Illuminate\Support\Facades\Log as Logs;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Web Service
    public function login(Request $req)
    {
        $credentials = $req->only('email', 'password');
        $internal = DB::table('user_pegawai')->where('no_pegawai', $req->email);
        // dd($internal->first());
        if ($internal->count() > 0) {
            $user = DB::table('users')->where('id', $internal->first()->user_id)->first();
            $credentials['email'] = $user->email;
        }
        $auth = Auth::attempt($credentials);
        // dd($auth);
        if (!$auth) {
            Logs::notice("Email '" . $req->email . "' gagal login dengan ip " . request()->ip());
            return back()->with(['msg' => 'Email/NIP atau Password Salah', 'color' => 'danger']);
        }
        if (Auth::user()->role == 'masyarakat') {
            Auth::logout();
            return back()->with(['msg' => 'Silahkan Login Di Smartphone Untuk Mengakses Fitur Masyarakat', 'color' => 'danger']);
        }
        Log::create(['activity' => 'Login RUMIJA', 'user_id' => Auth::user()->id, 'description' => 'User ' . Auth::user()->name . ' Logged In To Web RUMIJA', 'ip_address' => request()->ip()]);

        Logs::info(Auth::user()->name . ' login dengan ip ' . request()->ip());
        return redirect()->intended('admin');
    }
    public function logout()
    {
        if (Auth::check()) {
            Log::create(['activity' => 'Logout RUMIJA', 'user_id' => Auth::user()->id, 'description' => 'User ' . Auth::user()->name . ' Logged Out From Web RUMIJA', 'ip_address' => request()->ip()]);
        }
        Auth::logout();

        return redirect('/login');
    }
    public function verifyEmail($token)
    {
        try {
            $decrypted = Crypt::decrypt($token);
            $user = User::find($decrypted);
            $user->email_verified_at = now();
            $user->save();
            return redirect('login')->with(['msg' => 'Email Berhasil Diverifikasi', 'color' => 'success']);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $e->getMessage();
        }
    }

    public function loginUsingId($encrypted_id)
    {
        $id = decrypt($encrypted_id);
        $auth = Auth::loginUsingId($id);
        pushNotification([Auth::user()->id], "Logged In", "You have Logged IN");
        return redirect('admin');
    }
}
