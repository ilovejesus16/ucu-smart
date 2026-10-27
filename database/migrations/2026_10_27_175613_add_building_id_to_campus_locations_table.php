<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campus_locations', function (Blueprint $table) {
            $table->foreignId('building_id')
                ->nullable()
                ->constrained('buildings')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('campus_locations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('building_id');
        });
    }
};