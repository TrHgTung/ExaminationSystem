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
        Schema::create('question_exams', function (Blueprint $table) {
            $table->id();
            $table->string('QuestionExamID')->unique(); 
            $table->string('QuestionID'); // foreign key
            $table->string('ExamID'); // foreign key
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_exams');
    }
};
