<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->change();
            $table->foreignId('instructor_id')->nullable()->change();

            $table->string('section_name')->nullable()->after('subject_code');
            $table->decimal('units', 5, 2)->nullable()->after('subject_name');
            $table->unsignedInteger('enrolled')->nullable()->after('units');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn([
                'section_name',
                'units',
                'enrolled',
            ]);

            // Keep room_id and instructor_id nullable to avoid
            // rollback failures if imported records contain NULL values.
        });
    }
};