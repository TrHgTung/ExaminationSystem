<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing)
            $table->string('AnswerID')->unique();
            $table->string('AnswerContent')->unique(); 
            $table->string('AnswerImage');
            $table->boolean('IsCorrect')->default(0); // 0: false, 1: true
            // $table->foreignId('QuestionID')->constrained('questions')->onDelete('cascade'); // Foreign key to questions table
            $table->string('QuestionID'); // Foreign key to questions table
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing)
            $table->string('QuestionID')->unique(); 
            $table->string('QuestionContent');
            $table->string('QuestionImage'); 
            // $table->foreignId('ExamID')->constrained('exams')->onDelete('cascade'); // Foreign key to exams table
            $table->string('ExamID'); // Foreign key to questions table
        });

        Schema::create('exams', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing)
            $table->string('ExamID')->unique(); 
            $table->string('ExamName');
            $table->string('ExamType');
            $table->boolean('Status')->default(1); // 0: done, 1: available
        });

        Schema::create('student_answers', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing)
            $table->string('StudentAnswerID')->unique();
            // $table->foreignId('StudentID')->constrained('students')->onDelete('cascade'); // Foreign key to students table
            // $table->foreignId('QuestionID')->constrained('questions')->onDelete('cascade'); // Foreign key to questions table
            // $table->foreignId('AnswerID')->constrained('answers')->onDelete('cascade'); // Foreign key to answers table
            $table->string('StudentID'); // Foreign key 
            $table->string('QuestionID'); // Foreign key
            $table->string('AnswerID'); // Foreign key 
            $table->dateTime('DateTimeSubmitted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('student_answers');
    }
};
