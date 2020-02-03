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
     * @var EventData
     */
    public $eventData;

    /**
     *
     * @param Authenticatable $user
     * @param EventData $eventData
     */
    public function __construct(Authenticatable $user, EventData $eventData)
    {
        $this->user = $user;
        $this->eventData = $eventData;
    }
}