<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';
    protected $fillable = [
        'ExamID', // primary key
        'ExamName',
        'Status',
        'Author', // get AdminID
    ];
}
