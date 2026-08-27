<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('room_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('instructor_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('subject_code');

            $table->string('subject_name');


            $table->string('day');

            $table->time('start_time');

            $table->time('end_time');

            $table->string('semester');

            $table->string('school_year');

            $table->enum('status', [

    'scheduled',

    'in_progress',

    'completed',

    'cancelled'

])->default('scheduled');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};