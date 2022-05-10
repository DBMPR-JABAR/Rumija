<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transactional\RumijaTipe;

class RumijaTipeController extends Controller
{
    //
    public function index(Request $request)
    {
    	$pelaporan = RumijaTipe::all();
       	return view('admin.input_data.pelaporan.index',['pelaporan' => $pelaporan]);
    }
}
