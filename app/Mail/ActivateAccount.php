<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivateAccount extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string $name */
    protected $name;

    /** @var string $link */
    protected $link;

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
