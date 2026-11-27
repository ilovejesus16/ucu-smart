<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facility_id')
                ->constrained('facilities')
                ->restrictOnDelete();

            $table->string('event_name');
            $table->text('description')->nullable();

            $table->date('reservation_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('requesting_office');
            $table->string('status')->default('approved');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['facility_id', 'reservation_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_reservations');
    }
};
