<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function pegawai()
    {
        return $this->hasOne('App\Model\Transactional\Pegawai', 'user_id');
    }

    public function internalRole()
    {
        return $this->belongsTo('App\Model\Transactional\Role', 'internal_role_id');
    }

    public function push()
    {
        return $this->hasOne('App\Model\Push\UserPushNotification', 'user_id');
    }

    public function ruas()
    {
        // return $this->belongsToMany('App\Model\Transactional\RuasJalan');
        return $this->belongsToMany('App\Model\Transactional\RuasJalan', 'user_master_ruas_jalan', 'user_id', 'master_ruas_jalan_id');
    }

    // public function sup()
    // {
    //     return $this->hasOne('App\Model\Transactional\SUP', 'user_id');
    // }
}
