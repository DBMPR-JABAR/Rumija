<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Model\Transactional\Landing\NewsVideo;
use Illuminate\Http\Request;

class NewsVideoController extends Controller
{
    public function __construct()
    {
        $roles = setAccessBuilder('Video News', ['store'], ['detail','index'], [ 'update','show'], ['edit']);
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
        $data = NewsVideo::all();
        return view('admin.landing.video.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $count = NewsVideo::count();
        if($count < 3){
            $data = new NewsVideo;
            $data->fill($request->all());
            $data->save();

            $color = "success";
            $msg = "Berhasil Menambah Data Video Berita";
        }else{
            $color = "danger";
            $msg = "Tidak Dapat Menambah Video Berita Karena Penuh";
        }

        return redirect(route('video-news.index'))->with(compact('color','msg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = NewsVideo::find($id);
        return view('admin.landing.video.edit', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = NewsVideo::find($id);
        $data->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Video Berita";
        return redirect(route('video-news.index'))->with(compact('color', 'msg'));
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
        $data = NewsVideo::find($id);
        $data->fill($request->all());
        $data->save();

        $color = "success";
        $msg = "Berhasil Mengubah Data Video Berita";
        return redirect(route('video-news.index'))->with(compact('color','msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
