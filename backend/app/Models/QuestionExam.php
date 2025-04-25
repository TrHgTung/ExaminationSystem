<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionExam extends Model
{
    use HasFactory;

    protected $table = 'question_exams';
    protected $fillable = [
        'QuestionExamID',
        'QuesionID', // foreign key
        'ExamID', // foreign key
    ];
}
