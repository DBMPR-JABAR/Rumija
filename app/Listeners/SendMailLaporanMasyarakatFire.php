<?php

namespace App\Listeners;

use App\Events\SendMailLaporanMasyarakat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailLaporanMasyarakatFire implements ShouldQueue
{
    // use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendMailLaporanMasyarakat  $event
     * @return void
     */
    public function handle(SendMailLaporanMasyarakat $event)
    {
        foreach ($event->email_list_notifications as $user) {
            $data = [
                'no_aduan' => $event->id,
                'status_laporan' => $event->status,
                'email' => $user->email,
                'name' => $user->name
            ];

            Mail::send('laporanMasyarakatFromJQR', $data, function ($message) use ($data) {
                $message->to($data['email'], $data['name']);
                $message->subject('Laporan Marsyarakat from JQR (' . $data['no_aduan'] . ')');
                $message->priority(3);
            });
        }
    }
}
