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
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing)
            $table->string('AdminID')->unique(); // Unique StudentID
            $table->string('Email')->unique(); // Email address
            $table->string('Password'); // Password
        });

        Schema::create('lecturers', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing)
            $table->string('LecturerID')->unique(); // Unique StudentID
            $table->string('HoTen'); // Full name
            $table->string('Email')->unique(); // Email address
            $table->string('Password'); // Password
            $table->enum('GioiTinh', [0, 1]); // Gender: 0 = Male, 1 = Female
            $table->year('NamSinh'); // Year of birth
            $table->boolean('Status')->default(1); // Status: 0 = Banned, 1 = Active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
        Schema::dropIfExists('lecturers');
    }
};
