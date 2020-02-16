<?php

namespace App\UserAuth\Models;

use Illuminate\Database\Eloquent\Model;

class AuthSessionLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'authenticatable_type', 'authenticatable_id', 'guard', 'session_id', 'last_activity_at', 'logged_out'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    
    //-------------------------------------------------
    // Relations
    //-------------------------------------------------

    public function authenticatable()
    {
        return $this->morphTo();
    }
}
