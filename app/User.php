<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'usuarios';
    protected $fillable = ['username', 'email', 'password', 'activo', 'usertype_id', 'permiso_id'];


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



    public function empresas()
    {
        return $this->belongsToMany('App\Empresa');
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->usertype_id == '1';
    }


}
