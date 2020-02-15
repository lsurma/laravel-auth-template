<?php

namespace App\UserAuth\Events\Registration;

use App\UserAuth\Events\EventData;
use Illuminate\Contracts\Auth\Authenticatable;

class Registered
{
    /**
     * @var Authenticatable
     */
    public $user;

    /**
     * @var string
     */
    public $guard;

    /**
     *
     * @param Authenticatable $user
     * @param string $guard
     */
    public function __construct(Authenticatable $user, string $guard)
    {
        $this->user = $user;
        $this->guard = $guard;
    }
}