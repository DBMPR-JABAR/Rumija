<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailNotifikasi extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->data['type_mail'] =="Disposisi") {

            return $this->subject('Notifikasi Disposisi')
                ->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'))
                ->view('mail.notifikasiDisposisi', ['data' => $this->data]);
        } else if ($this->data['type_mail'] =="TindakLanjut") {
            return $this->subject('Notifikasi Disposisi')
            ->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'))
            ->view('mail.notifikasiTindakLanjut', ['data' => $this->data]);
        } else if ($this->data['type_mail'] == "Accepted"){
            return $this->subject('Notifikasi Disposisi')
            ->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'))
            ->view('mail.notifikasiAccepted', ['data' => $this->data]);
        }
    }
}
