<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/*
 * This class extends Laravel's Default ResetPasswordNotification in order to be able to queue the notification
 * and to localize the sent email.
 */
class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    // TODO: Override toEmail method to localize it
}
