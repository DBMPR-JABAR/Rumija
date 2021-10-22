<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\GeneralResource;
use Illuminate\Http\Request;
use App\Model\DWH\ProgressMingguan;
use Illuminate\Support\Str;

class ProgressController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return (new GeneralResource(ProgressMingguan::all()));
        return (new GeneralResource(ProgressMingguan::where('LAT','<>','0')->get()));
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
        return new GeneralResource(ProgressMingguan::findOrFail($id));
    }

    public function showStatusCount($status)
    {
        $trimStatus = Str::replaceFirst('-', ' ', $status);
        $progress = ProgressMingguan::count()->filter(function($item) use ($trimStatus) {
            return $item->STATUS_PROYEK === $trimStatus;
        });
        return (new GeneralResource($progress));
    }

    public function showStatus($status)
    {
        $trimStatus = Str::replaceFirst('-', ' ', $status);
        $progress = ProgressMingguan::get()->filter(function($item) use ($trimStatus) {
            return $item->STATUS_PROYEK === $trimStatus;
        });
        return (new GeneralResource($progress));
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
