<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembangunanTalikuatController extends Controller
{
    public function getPembangunanTalikuat(Request $request)
    {
        try { $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            $this->response['data']['error'] = $validator->errors();
            return response()->json($this->response, 200);
        }

        if ($request->username !== 'talikuat' || $request->password !== 'Harbang@79') {
            $this->response['data']['error'] = "Hak akses salah";
            return response()->json($this->response, 200);
        }

            $pembangunan = DB::table('pembangunan')->get();

            $this->response['status'] = 'success';
            $this->response['data']['pembangunan'] = $pembangunan;
            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error' . $th;
            return response()->json($this->response, 500);
        }
    }
}
