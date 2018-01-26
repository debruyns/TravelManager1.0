<?php

namespace App\Mail;

use App\Mail\Mailer\Mailable;
use App\Models\User;

class PasswordReset extends Mailable
{
    protected $user;
    protected $resetcode;
    protected $translator;

    public function __construct($translator, User $user, $resetcode)
    {
        $this->user = $user;
        $this->resetcode = $resetcode;
        $this->translator = $translator;
    }

    public function build()
    {

        $host = $this->getHost();

        return $this->subject($this->translator->trans('email.reset.subject'))
            ->view('emails/passwordreset.twig')
            ->with([
                'user' => $this->user,
                'host' => $host,
                'resetcode' => $this->resetcode
            ]);

    }
}
