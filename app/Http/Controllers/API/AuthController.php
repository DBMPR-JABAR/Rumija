<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Model\Transactional\Log;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    private $response;

    public function __construct()
    {
        $this->middleware('jwt.auth')->only(['getToken', 'getUser', 'refresh', 'newPassword']);
        $this->response = [
            'status' => 'false',
            'data' => []
        ];
    }

    public function login(Request $req)
    {
        $credentials = $req->only('email', 'password');
        $internal = DB::table('user_pegawai')->where('no_pegawai', $req->email);

        if ($internal->count() > 0) {
            $user = DB::table('users')->where('id', $internal->first()->user_id)->first();
            $credentials['email'] = $user->email;
        }

        try {
            if (!$token = auth('api')->attempt($credentials)) {
                $this->response['data']['message'] = 'invalid_credentials';
                return response()->json($this->response, 200);
            }
        } catch (JWTException $e) {
            $this->response['data']['message'] = 'could_not_create_token';
            return response()->json($this->response, 500);
        }

        if (!auth('api')->user()->email_verified_at) {
            auth('api')->logout();
            $this->response['data']['message'] = 'Email Not Verified';
            return response()->json($this->response, 200);
        }
        if (auth('api')->user()->role == 'internal') {
            Log::create(['activity' => 'Login', 'description' => 'User ' . auth('api')->user()->name . ' Logged In To Android App']);
        }
        $userMasyarakat = DB::table('user_masyarakat')->where('user_id', auth('api')->user()->id)->first();
        $this->response['status'] = 'success';
        $this->response['data']['token'] = $this->getToken($token);
        $this->response['data']['user'] = auth('api')->user();

        if (auth('api')->user()->internalRole) {
            $role = auth('api')->user()->internalRole->role;
            if (strpos($role, 'Mandor') !== false) $this->response['data']['user']['role'] = "mandor";
        }

        $this->response['data']['user']['noTelp'] = $userMasyarakat ? $userMasyarakat->no_telp : 0;
        $this->response['data']['user']['alamat'] = $userMasyarakat ? $userMasyarakat->alamat : "-";
        $this->response['data']['user']['encrypted_id'] = encrypt(auth('api')->user()->id);
        return response()->json($this->response, 200);
    }

    public function logout()
    {
        try {
            if (auth('api')->user()->role == 'internal') {
                Log::create(['activity' => 'Logout', 'description' => 'User ' . auth('api')->user()->name . ' Logged Out From Android App']);
            }
            auth('api')->logout();
            $this->response['status'] = 'success';
            $this->response['data']['message'] = 'User Logged Out';
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function register(Request $req)
    {
        try {
            // Data Validation
            $validator = Validator::make($req->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            // Create User Data
            $user = User::create([
                'name' => $req->get('name'),
                'email' => $req->get('email'),
                'password' => Hash::make($req->get('password')),
                'role' => 'masyarakat'
            ]);

            // Send Email Verification
            $to_email = $user->email;
            $to_name = $user->name;
            $data = [
                'name' => $user->name,
                'link' => url('verify-email/' . Crypt::encrypt($user->id))
            ];
            Mail::send('mail.sendVerificationMail', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('Verifikasi Akun Temanjabar');

                $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
            });

            // Response
            $this->response['status'] = 'success';
            $this->response['data']['message'] = 'Email Verifikasi Dikirim';

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function newPassword(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'passwordOld' => 'required|string|min:6',
                'passwordNew' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $user = User::where('id', auth()->user()->id)->first();
            if (Hash::check($req->passwordOld, $user->password)) {
                $user->password = Hash::make($req->get('passwordNew'));
                $user->save();
                $this->response['data']['message'] = "Berhasil Mengubah Password";
            } else {
                $this->response['data']['message'] = "Password Lama Salah";
            }
            $this->response['status'] = 'success';
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function changeDetail(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'noTelp' => 'min:8',
                'alamat' => 'string|min:5',
            ]);
            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $userId = auth('api')->user()->id;
            DB::table('user_masyarakat')->where('user_id', $userId)->update([
                'no_telp' => $req->get('noTelp'),
                'alamat' => $req->get('alamat')
            ]);
            //$userMasyarakat->no_telp = $req->get('noTelp');
            //$userMasyarakat->alamat = $req->get('alamat');
            //$userMasyarakat->save();
            $this->response['data']['message'] = "Berhasil Mengubah Identitas";
            $this->response['status'] = 'success';
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error' . $e;
            return response()->json($this->response, 500);
        }
    }

    public function resetPasswordMail(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'email' => 'required|string|email|max:255|exists:users',
            ]);
            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $kode_otp = rand(100000, 999999);

            $user = User::where('email', $req->email)->first();
            $user->kode_otp = $kode_otp;
            $user->save();

            $data = [
                'name' => $user->name,
                'kode_otp' => $kode_otp
            ];
            $to_email = $user->email;
            $to_name = $user->name;

            Mail::send('mail.sendOTP', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('Verifikasi OTP Reset Password Akun Temanjabar');

                $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
            });

            $this->response['status'] = 'success';
            $this->response['data']['kode_otp'] = $kode_otp;
            $this->response['data']['message'] = "Kode Verifikasi Dikirim";

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }
    public function resetPassword(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'email' => 'required|string|email|max:255|exists:users',
                'password' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $user = User::where('email', $req->email)->first();
            $user->password = Hash::make($req->get('password'));
            $user->save();

            $this->response['status'] = 'success';
            $this->response['data']['message'] = "Berhasil Mengubah Password";

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function getUser()
    {
        try {
            $this->response['status'] = 'success';
            $this->response['data']['user'] = auth('api')->user();
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    protected function getToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }

    public function refresh()
    {
        try {
            return $this->getToken(auth('api')->refresh());
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function loginOTP(Request $req)
    {
        $user = User::where('email', $req->email)->first();
        try {
            if ($user && Hash::check($req->password, $user->password)) {
                if (!$user->email_verified_at) {
                    $this->response['data']['message'] = 'Email Not Verified';
                    return response()->json($this->response, 200);
                }
                $kode_otp = rand(100000, 999999);
                $user->kode_otp = $kode_otp;
                $user->save();

                $to_email = $user->email;
                $to_name = $user->name;
                $data = [
                    'name' => $to_name,
                    'kode_otp' => $kode_otp
                ];
                try {
                    Mail::send('mail.sendOTP', $data, function ($message) use ($to_name, $to_email) {
                        $message->to($to_email, $to_name)->subject('Kode OTP Temanjabar');
                        // $message->to('priyayidimas@upi.edu', 'Dimas Anom Priyayi')->subject('Kode OTP Temanjabar');
                        $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
                    });
                    $this->response['status'] = 'success';
                    $this->response['data']['otp'] = $kode_otp;
                    $this->response['data']['message'] = 'Kode OTP Terkirim';
                    return response()->json($this->response, 200);
                } catch (\Exception $e) {
                    $this->response['data']['message'] = 'Internal Error';
                    return response()->json($this->response, 500);
                }
            } else {
                throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("User Email or Password Mismatch");
            }
        } catch (\Exception $e) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $this->response['data']['message'] = 'User Email or Password Mismatch';
                return response()->json($this->response, 200);
            } else {
                $this->response['data']['message'] = 'Internal Error';
                return response()->json($this->response, 500);
            }
        }
    }

    public function verifyOTPLogin(Request $req)
    {
        try {
            $user = User::where('email', $req->email)->where('kode_otp', $req->kode_otp)->first();
            if (!$user || !$token = auth('api')->login($user)) {
                $this->response['data']['message'] = 'Invalid OTP';
                return response()->json($this->response, 200);
            }
        } catch (JWTException $e) {
            $this->response['data']['message'] = 'could_not_create_token';
            return response()->json($this->response, 500);
        }

        $this->response['status'] = 'success';
        $this->response['data']['token'] = $this->getToken($token);
        $this->response['data']['user'] = auth('api')->user();
        return response()->json($this->response, 200);
    }

    public function registerOTP(Request $req)
    {
        try {
            // Data Validation
            $validator = Validator::make($req->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }

            $kode_otp = rand(100000, 999999);


            $existing = User::where('email', $req->email)->first();
            if ($existing) {
                if (!$existing->email_verified_at) {
                    $user = $existing;
                } else {
                    $this->response['data']['message'] = 'Email sudah digunakan, mohon gunakan email yang lain';
                    if ($existing->role == 'internal')
                        $this->response['data']['message'] = 'Email telah digunakan sebagai internal user, mohon gunakan email yang lain';
                    return response()->json($this->response, 200);
                }
            } else {
                $user = new User;
            }
            $role_masyarakat = DB::table('user_role')->where('role', 'like', '%masyarakat/eksternal%')->first();

            // Create User Data
            $user->name = $req->get('name');
            $user->email = $req->get('email');
            $user->password = Hash::make($req->get('password'));
            $user->role = 'masyarakat';
            $user->internal_role_id = $role_masyarakat->id;
            $user->kode_otp = $kode_otp;
            $user->save();

            DB::table('user_masyarakat')->insert(['user_id' => $user->id]);

            // Send Email Verification
            $to_email = $user->email;
            $to_name = $user->name;
            $data = [
                'name' => $user->name,
                'kode_otp' => $kode_otp
            ];
            Mail::send('mail.sendOTP', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('Verifikasi OTP Akun Temanjabar');
                $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
            });

            // Response
            $this->response['status'] = 'success';
            $this->response['data']['kode_otp'] = $kode_otp;
            $this->response['data']['message'] = 'Email Verifikasi Dikirim';

            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function resendOTPMail(Request $req)
    {
        $kode_otp = rand(100000, 999999);

        $user = User::where('email', $req->email)->first();
        $user->kode_otp = $kode_otp;
        $user->save();

        $to_email = $user->email;
        $to_name = $user->name;

        $data = [
            'name' => $to_name,
            'kode_otp' => $kode_otp
        ];
        try {
            Mail::send('mail.sendOTP', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('Verifikasi OTP Akun Temanjabar');
                $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
            });

            $this->response['status'] = 'success';
            $this->response['data']['otp'] = $kode_otp;
            $this->response['data']['message'] = 'Kode OTP Terkirim';
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    public function verifyOTP(Request $req)
    {
        try {
            $user = User::where('email', $req->email)->where('kode_otp', $req->kode_otp)->first();
            if (!$user) {
                $this->response['data']['message'] = 'Invalid OTP';
                return response()->json($this->response, 200);
            }

            $user->email_verified_at = now();
            $user->save();

            $this->response['status'] = 'success';
            $this->response['data']['id'] = $user->id;
            $this->response['data']['message'] = 'Berhasil Verifikasi';
            return response()->json($this->response, 200);
        } catch (JWTException $e) {
            $this->response['data']['message'] = 'could_not_create_token';
            return response()->json($this->response, 500);
        }
    }
}
