<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

/*
 * This class extends Laravel's Default ResetPasswordNotification in order to be able to queue the notification
 * and to localize the sent email.
 */
class ResetPassword extends ResetPasswordNotification implements ShouldQueue
{
    use Queueable;

    // TODO: Override toEmail method to localize it
}
