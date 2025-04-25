<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';
    protected $fillable = [
        'AnswerID',
        'AnswerContent',
        'AnswerImage',
        'IsCorrect', // 0: false, 1: true
        'QuestionID', // foreign key
    ];
}
