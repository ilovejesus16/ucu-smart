<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_monitoring_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_monitoring_id')
                ->constrained('room_monitorings')
                ->cascadeOnDelete();

            $table->foreignId('changed_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->enum('old_status', [
                'present',
                'absent',
            ]);

            $table->enum('new_status', [
                'present',
                'absent',
            ]);

            $table->text('old_remarks')->nullable();

            $table->text('new_remarks')->nullable();

            $table->dateTime('changed_at');

            $table->timestamps();

            $table->index('changed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_monitoring_histories');
    }
};
