<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LivreurPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $livreur;
    public $password;

    public function __construct($livreur, $password)
    {
        $this->livreur = $livreur;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Compte Livreur - Gaz Express')
                    ->view('emails.livreur_password');
    }
}
