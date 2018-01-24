<?php

namespace App\Mail;

use App\Mail\Mailer\Mailable;
use App\Models\User;

class Welcome extends Mailable
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject("Welcome to Codecourse {$this->user->firstname}")
            ->view('emails/welcome.twig')
            ->with([
                'user' => $this->user
            ]);
    }
}
