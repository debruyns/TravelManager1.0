<?php

namespace App\Mail;

use App\Mail\Mailer\Mailable;
use App\Models\User;

class Activate extends Mailable
{
    protected $user;
    protected $activationcode;
    protected $translator;

    public function __construct($translator, User $user, $activationcode)
    {
        $this->user = $user;
        $this->activationcode = $activationcode;
        $this->translator = $translator;
    }

    public function build()
    {

        $host = $this->getHost();

        return $this->subject($this->translator->trans('email.activate.subject'))
            ->view('emails/activate.twig')
            ->with([
                'user' => $this->user,
                'host' => $host,
                'activationcode' => $this->activationcode
            ]);

    }
}
