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
        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing)
            $table->string('QuestionID')->unique(); 
            $table->string('QuestionContent');
            $table->string('QuestionImage');
            $table->string('QuestionType'); // physic, chemistry, biology,...
            // $table->foreignId('ExamID')->constrained('exams')->onDelete('cascade'); // Foreign key to exams table
            // $table->string('ExamID'); // Foreign key to questions table
        });

        Schema::create('exams', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing)
            $table->string('ExamID')->unique(); 
            $table->string('ExamName');
            $table->boolean('Status')->default(1); // 0: done, 1: available
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
        Schema::dropIfExists('exams');
    }
};
