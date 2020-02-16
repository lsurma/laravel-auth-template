<?php

namespace App\UserAuth\Services;

use App\UserAuth\Common\Config;
use App\UserAuth\Common\Interfaces\EmailVerifiableInterface;
use App\UserAuth\Common\Interfaces\EmailVerificationNotificationInterface;
use App\UserAuth\Models\AuthSessionLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserAuthService
{
    /**
     * @var \App\UserAuth\Common\Config
     */
    public Config $config;
    
    protected string $guard;

    public function __construct()
    {
    }

    public function setGuard(string $guard)
    {
        $this->guard = $guard;
        $this->config = new Config($guard);

        return $this;
    }

    /**
     * @param Authenticatable $authenticatable
     * @param boolean $isLoggedOut
     * @return AuthSessionLog|null
     */
    public function handleSessionLog(Authenticatable $authenticatable, bool $isLoggedOut = false)
    {
        if(!$this->config->sessionLogEnabled()) {
            return null;
        }

        return $this->logSession($authenticatable, [
            'last_activity_at' => now(),
            'is_logged_out' => $isLoggedOut
        ]);
    }

    /**
     * @param Authenticatable $authenticatable
     * @param array $attributes
     * @return AuthSessionLog
     */
    public function logSession(Authenticatable $authenticatable, array $attributes = [])
    {
        // Session related with current guard
        $session = Auth::guard($this->guard)->getSession();

        // Base params for finding and creating session
        $params = [
            'authenticatable_type' => get_class($authenticatable),
            'authenticatable_id' => $authenticatable->getAuthIdentifier(),
            'guard' => $this->guard,
            'session_id' => $session->getId()
        ];

        /** @var AuthSessionLog $log */
        $log = AuthSessionLog::where($params)->first(['id']);

        if($log) {
            $log->update($attributes);
        } else {
            // Gather information about requst data
            $log = AuthSessionLog::create($params + $attributes);
        }

        return $log;
    }

    /**
     * Send e-mail verification notification based on configuration
     *
     * @param EmailVerifiableInterface $user
     * @return void
     */
    public function sendEmailVerificationNotification(EmailVerifiableInterface $user)
    {
        /** @var \Illuminate\Notifications\Notification $notification */
        $notification = resolve($this->config->getEmailVerificationNotification());

        if(!$notification) {
            throw new \Exception("Notification class not found in service container");
        }
            
        if($notification instanceof EmailVerificationNotificationInterface) {
            $notification->setVerificationRoute($this->config->getEmailVerificationRoute());
            $notification->setVerificationLinkExpireTime($this->config->getEmailVerificationLinkExpireTime());
        }

        return $user->sendEmailVerificationNotification($notification);
    }
}