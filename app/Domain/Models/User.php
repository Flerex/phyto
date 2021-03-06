<?php

namespace App\Domain\Models;

use App\Notifications\ActivateAccountNotification;
use App\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be visible in JSON and array casting.
     *
     * @var array
     */
    protected $visible = [
        'name', 'email',
    ];

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new ActivateAccountNotification);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Defines the relationship that allows to navigate from the user
     * model to the project the user has been assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)->oldest();
    }

    /**
     * Defines the relationship that allows to navigate from the user
     * model to the project the user manages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function managedProjects()
    {
        return $this->hasMany(Project::class);
    }


    /**
     * Defines the relationship that allows to navigate from the user
     * model to their assignments.
     */
    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    /**
     * Defines the relationship that allows to navigate from the user
     * model to their unfinished assignments.
     */
    public function unfinishedAssignments()
    {
        return $this->hasMany(TaskAssignment::class)->unfinished();
    }

}
