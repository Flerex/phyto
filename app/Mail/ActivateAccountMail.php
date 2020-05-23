<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivateAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $name;

    protected string $link;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $link
     */
    public function __construct(string $name, string $link)
    {
        $this->name = $name;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('emails.activate_account'))
            ->markdown('emails.activate_account', [
                'name' => $this->name,
                'link' => $this->link,
            ]);
    }
}
