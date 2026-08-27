<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_usages', function (Blueprint $table) {

            $table->id();

            // Room being used
            $table->foreignId('room_id')
                ->constrained()
                ->cascadeOnDelete();

            // Schedule responsible for the usage
            $table->foreignId('schedule_id')
                ->constrained()
                ->cascadeOnDelete();

            // Instructor who started the class
            $table->foreignId('instructor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Actual class start
            $table->dateTime('started_at');

            // Actual class end
            $table->dateTime('ended_at')
                ->nullable();

            // Total actual usage in minutes
            $table->unsignedInteger('duration_minutes')
                ->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_usages');
    }
};