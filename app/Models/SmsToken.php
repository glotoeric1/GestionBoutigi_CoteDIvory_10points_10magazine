<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsToken extends Model
{
    use HasFactory;
    protected $fillable = ['access_token', 'expires_in', 'expiration_time'];
}