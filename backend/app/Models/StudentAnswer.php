<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $table = 'student_answers';
    protected $fillable = [
        'StudentAnswerID',
        'StudentID', // foreign key
        'QuestionID', // foreign key
        'AnswerID', // foreign key - the answer submitted by the student
        'DateTimeSubmitted', // the time the student submitted the answer
    ];
}
