<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements Authenticatable, JWTSubject
{
    use AuthenticatableTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password'
    ];

    // WAJIB ADA UNTUK JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // WAJIB ADA UNTUK JWT
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}
