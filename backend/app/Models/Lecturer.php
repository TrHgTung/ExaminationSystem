<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'lecturers';
    protected $fillable = [
        'LecturerID',
        'HoTen',
        'Email',
        'Password',
        'GioiTinh',
        'NamSinh',
        'Status'
    ];

    protected $hidden = [
        'Password',
    ];
}
