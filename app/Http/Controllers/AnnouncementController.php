<?php

namespace App\Http\Controllers;

use App\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\Datatables\DataTables;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pengumuman = Announcement::latest('created_at')
        ->leftJoin('users','announcements.created_by','=','users.id')->select('announcements.*', 'users.name as nama_user')
        ->get();
        // dd($pengumuman);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $action = 'store';
        return view('admin.pengumuman.insert', compact('action'));

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
        $this->validate($request,[
            'cover'         => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'title'         => 'required|unique:announcements',
            'content'       => '',
            'sent_to'       => 'required'
        ]);
        $pengumuman = [
            "title"=>$request->title, 
            "slug" =>Str::slug($request->title, '-'),
            "content"=>$request->content, 
            "sent_to"=>$request->sent_to,
            "created_by"=>Auth::user()->id,
            "updated_by"=>Auth::user()->id

        ];
        // dd($pengumuman['slug']);
       
        // Notifikasi HP
        $title = "Pengumuman ";
        $body = $request->title;
        $userNotifHp = DB::table("users")->where('role',$request->sent_to)
        ->rightJoin('user_push_notification','users.id','=','user_push_notification.user_id')->pluck('users.id');
        // dd($userNotifHp);
        sendNotificationPengumuman($userNotifHp,$title,$body);

        // Notifikasi Email
        // $userNotifemail = DB::table("users")->where('role',$request->sent_to)->where('email', '!=', null)->get();
        // // dd($userNotifemail);
        // $subject = "Pengumuman";
        // foreach($userNotifemail as $dataaaa){
        //     $name = $dataaaa->name;
        //     $to_email = $dataaaa->email;
        //     setSendEmailHelpers($name, $request->title, $request->content, $to_email, $name ,$subject);
        // }
        // dd($name);
        if ($request->cover != null) {
            $path = Str::snake(date("YmdHis") . ' ' . $request->cover->getClientOriginalName());
            $request->cover->storeAs('public/pengumuman/', $path);
            $pengumuman['image'] = $path;
        }
        $announcement = Announcement::create($pengumuman)->save();


        if($announcement){
            //redirect dengan pesan sukses
            $announc = Announcement::where('slug',$pengumuman['slug'])->first();
            $utils_not = [
                "title"=>"pengumuman", 
                "role"=>$request->sent_to,
                "user_id"=>Auth::user()->id,
                "pointer_id" => $announc->id,
                "created_at" => $announc->created_at

            ];
            $utils_notif = DB::table("utils_notifikasi")->insert($utils_not);
            $color = "success";
            $msg = "Data Berhasil Disimpan!";
        }else{
            //redirect dengan pesan error
            $color = "danger";
            $msg = "Data Gagal Disimpan!";
        }
        return redirect()->route('announcement.index')->with(compact('color', 'msg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
        $pengumuman = Announcement::where('announcements.slug',$slug)
        ->leftJoin('users','announcements.created_by','=','users.id')->select('announcements.*', 'users.name as nama_user')
        ->first();
        $utils_notif = DB::table('utils_notifikasi')->where('utils_notifikasi.title','pengumuman')
        ->leftJoin('announcements','announcements.id','=','utils_notifikasi.pointer_id')->where('utils_notifikasi.pointer_id',$pengumuman->id)->select('announcements.*','utils_notifikasi.title as nama_notif','utils_notifikasi.id as utils_notifikasi_id')
        ->first();
        // dd($utils_notif);
        if(Auth::user()){
            $utils_not = [
                "title"=>"pengumuman", 
                "user_id"=>Auth::user()->id,
                "pointer_id" => $utils_notif->id,
                "utils_notifikasi_id" => $utils_notif->utils_notifikasi_id
            ];
            $read_notif = DB::table("read_notifikasi")->updateOrInsert($utils_not);

        }
        // dd($pengumuman);
        return view('admin.pengumuman.show', compact('pengumuman'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        //
        // dd($announcement);
        $action = 'edit';
        return view('admin.pengumuman.insert', compact('action','announcement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
         //
         $this->validate($request,[
            'cover'         => 'image|mimes:jpeg,jpg,png|max:2000',
            'title'         => 'required|unique:announcements,title,'.$announcement->id,
            'content'       => '',
            'sent_to'       => 'required'
        ]);
        $pengumuman = [
            "title"=>$request->title, 
            "slug" =>Str::slug($request->title, '-'),
            "content"=>$request->content, 
            "sent_to"=>$request->sent_to,
            "updated_by"=>Auth::user()->id

        ];
        // dd($announcement);
       
        if ($request->cover != null) {
            //remove old image
            Storage::disk('local')->delete('public/pengumuman/'.$announcement->image);
            
            $path = Str::snake(date("YmdHis") . ' ' . $request->cover->getClientOriginalName());
            $request->cover->storeAs('public/pengumuman/', $path);
            $pengumuman['image'] = $path;
        }
        
        $announcement = Announcement::findOrFail($announcement->id)
        ->update($pengumuman);
        // dd($pengumuman);
        if($announcement){
            //redirect dengan pesan sukses
            $color = "success";
            $msg = "Data Berhasil Diperbaharui!";
        }else{
            //redirect dengan pesan error
            $color = "danger";
            $msg = "Data Gagal Diperbaharui!";
        }
        return redirect()->route('announcement.index')->with(compact('color', 'msg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $announcement = Announcement::findOrFail($id);
        // dd($announcement->id);
        Storage::disk('local')->delete('public/pengumuman/'.$announcement->image);
        $announcement = $announcement->delete();

        $utils_notif = DB::table("utils_notifikasi")
        ->where('title','pengumuman')
        ->where('pointer_id',$id)->delete();


        if($announcement){
            //redirect dengan pesan sukses
            $color = "success";
            $msg = "Data Berhasil Dihapus!";
        }else{
            //redirect dengan pesan error
            $color = "danger";
            $msg = "Data Gagal Dihapus!";
        }
        return redirect()->route('announcement.index')->with(compact('color', 'msg'));

    }
}
