<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campus_locations', function (Blueprint $table) {
            $table->unsignedSmallInteger('marker_size')
                ->default(28)
                ->after('map_y');
        });
    }

    public function down(): void
    {
        Schema::table('campus_locations', function (Blueprint $table) {
            $table->dropColumn('marker_size');
        });
    }
};
