<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admins';
    protected $fillable = [
        'AdminID',
        'Email',
        'Password',
    ];

    protected $hidden = [
        'Password',
    ];
}
