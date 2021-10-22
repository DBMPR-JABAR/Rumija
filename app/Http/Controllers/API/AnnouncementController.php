<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Announcement;

class AnnouncementController extends Controller
{
    //
    public function getDataInternal()
    {
        $announcement_internal = Announcement::latest('created_at')->where('sent_to','internal')->get();
        return response()->json([
            "response" => [
                "status"    => 200,
                "message"   => "List Data Pengumuman"
            ],
            "data" => $announcement_internal
        ], 200);
    }
    public function getDataMasyarakat()
    {
        $announcement_masyarakat = Announcement::latest('created_at')->where('sent_to','masyarakat')->get();
        return response()->json([
            "response" => [
                "status"    => 200,
                "message"   => "List Data Pengumuman"
            ],
            "data" => $announcement_masyarakat
        ], 200);
    }
    public function show($slug)
    {
        $pengumuman = Announcement::where('announcements.slug',$slug)
        ->leftJoin('users','announcements.created_by','=','users.id')->select('announcements.*', 'users.name as nama_user')
        ->first();

        if($pengumuman) {
            
            return response()->json([
                "response" => [
                    "status"    => 200,
                    "message"   => "Detail Data Pengumuman"
                ],
                "data" => $pengumuman
            ], 200);

        } else {

            return response()->json([
                "response" => [
                    "status"    => 404,
                    "message"   => "Data Post Tidak Ditemukan!"
                ],
                "data" => null
            ], 404);

        }
    }
}
