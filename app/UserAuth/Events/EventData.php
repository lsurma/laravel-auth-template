<?php

namespace App\UserAuth\Events;

class EventData
{
    public string $guard;
    public string $configGroup;

    public function __construct(string $guard, string $configGroup)
    {
        $this->guard = $guard;
        $this->configGroup = $configGroup;
    }
}