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
    	$rumija_tipe = RumijaTipe::all();
        dd($rumija_tipe);
       	return view('admin.master.rumija_tipe.index',compact('rumija_tipe'));
    }
}
