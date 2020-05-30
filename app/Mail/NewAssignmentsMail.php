<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewAssignmentsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected string $user;

    protected string $project;

    protected int $assignmentCount;

    protected string $link;

    /**
     * Create a new message instance.
     *
     * @param  string  $user
     * @param  string  $project
     * @param  int  $assignmentCount
     * @param  string  $link
     */
    public function __construct(string $user, string $project, int $assignmentCount, string $link)
    {
        $this->user = $user;
        $this->project = $project;
        $this->assignmentCount = $assignmentCount;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('emails.new_assignments'))
            ->markdown('emails.new_assignments', [
                'user' => $this->user,
                'project' => $this->project,
                'assignmentCount' => $this->assignmentCount,
                'link' => $this->link,
            ]);
    }
}
