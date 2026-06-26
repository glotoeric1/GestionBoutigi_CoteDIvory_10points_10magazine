<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact',
        'statut',
        'roles',
        'secret',
        'id_setting',
        'id_boutigue'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function updateUser($data, $id)
    {
        return User::find($id)->update($data);
    }

    public function deleteUser($id)
    {
        return User::find($id)->delete();
    }

    public function ShowUserName($id)
    {
        if ($id == "") {
            return "Non";
        }
        return trim(User::where('id', $id)->pluck("name"), '[""]');
    }

    public function getBoutique($id)
    {
        if ($id == "") {
            return "Non";
        }
        return Boutique::where('id', $id)->first();
    }

}