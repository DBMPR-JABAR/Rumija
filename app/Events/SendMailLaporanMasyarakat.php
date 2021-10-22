<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendMailLaporanMasyarakat
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id,$status)
    {
        $this->status_laporan = $status;
        $this->no_laporan = $id;
        $this->email_list_notifications =
            DB::table('users')
            ->leftJoin('master_grant_role_aplikasi', 'master_grant_role_aplikasi.internal_role_id', 'users.internal_role_id')
            ->where('master_grant_role_aplikasi.menu', 'Email Notifikasi Laporan JQR')
            ->get();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return []; //new PrivateChannel('channel-name');
    }
}
