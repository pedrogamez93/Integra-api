<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class UserNova extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'users';

    /*public function findForPassport($rut)
    {
    return $this->where('rut', $rut)->first();
    }*/

    /*public function getAuthUsername()
    {
    return $this->rut;
    }*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'surname',
        'rut',
        'dv',
        'werks',
        'address',
        'persk',
        'text20',
        'position',
        'tipest',
        'phone',
        'politics',
        'email',
        'password',
        'personal_mail',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function AauthAcessToken()
    {
        return $this->hasMany('\App\OauthAccessToken');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        "is_notification_settlement" => "boolean",
        "is_notification_new" => "boolean",
    ];
}