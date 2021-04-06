<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Lowongan;
use App\Pelamar;
use App\User;

class HRDMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * int id pelamar
     * @return void
     */
    public function __construct($id)
    {
        $this->id_pelamar = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Get data pelamar
        $pelamar = Pelamar::find($this->id_pelamar);
        $pelamar->posisi = Lowongan::find($pelamar->posisi);
        $user = User::find($pelamar->id_user);

        return $this->from('ajifatur2@gmail.com')->markdown('email/hrd')->subject('Notifikasi')->with([
            'pelamar' => $pelamar,
            'user' => $user,
        ]);
    }
}
