<?php

namespace App\UserAuth\Common\Interfaces;

interface HasSessionAuthInterface
{
    /**
     * Get session authentication token
     *
     * @return string
     */
    public function getSessionAuthToken();

    /**
     * Get session authentication token name
     *
     * @return string
     */
    public function getSessionAuthTokenName();

    /**
     * Regenerates and saved new token
     *
     * @return bool
     */
    public function regenerateSessionAuthToken(): bool;
}