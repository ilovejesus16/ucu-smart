<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_monitorings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('schedule_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('room_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('instructor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('verified_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->date('monitoring_date');

            $table->enum('attendance_status', [
                'present',
                'absent',
            ]);

            $table->text('remarks')->nullable();

            $table->dateTime('verified_at');

            $table->timestamps();

            $table->unique(
                ['schedule_id', 'monitoring_date'],
                'room_monitorings_schedule_date_unique'
            );

            $table->index(['room_id', 'monitoring_date']);
            $table->index(['instructor_id', 'monitoring_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_monitorings');
    }
};
