<?php

namespace App\UserAuth\Events;

class EventData
{
    public string $guard;
    public string $configGroup;

    public function __construct(string $configGroup, string $guard)
    {
        $this->guard = $guard;
        $this->configGroup = $configGroup;
    }
}