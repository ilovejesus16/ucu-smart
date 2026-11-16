<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('college_room_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('college_id')
                ->constrained('colleges')
                ->cascadeOnDelete();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->enum('room_type', [
                'lecture',
                'laboratory',
            ]);

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'college_id',
                    'room_id',
                    'academic_year_id',
                    'room_type',
                ],
                'college_room_assignment_unique'
            );

            $table->index(['academic_year_id', 'college_id']);
            $table->index(['academic_year_id', 'room_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('college_room_assignments');
    }
};
