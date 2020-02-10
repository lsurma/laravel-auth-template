<?php

namespace App;

use App\UserAuth\Common\Interfaces\EmailVerifiableInterface;
use App\UserAuth\Common\Interfaces\HasSessionAuthInterface;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class User extends Authenticatable implements EmailVerifiableInterface, HasSessionAuthInterface
{
    use Notifiable;

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
        'password', 'remember_token', 'session_auth_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(?Notification $notification = null)
    {
        $this->notify($notification ?: new VerifyEmail);
    }

    /**
     * Get session authentication token
     *
     * @return string
     */
    public function getSessionAuthToken()
    {
        return $this->{$this->getSessionAuthTokenName()};
    }

    public function getSessionAuthTokenName()
    {
        return 'session_auth_token';
    }

    /**
     * Regenerate and saves session auth token
     *
     * @return bool
     */
    public function regenerateSessionAuthToken(): bool
    {
        $this->{$this->getSessionAuthTokenName()} = Str::random(32);

        return $this->save();
    }
}
