
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->decimal('total_floor_area', 12, 2)->nullable();
            $table->unsignedSmallInteger('year_constructed')->nullable();
            $table->unsignedInteger('classroom_count')->nullable();
            $table->unsignedInteger('laboratory_count')->nullable();
            $table->unsignedInteger('office_count')->nullable();
            $table->unsignedInteger('restroom_count')->nullable();
            $table->unsignedInteger('function_room_count')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn([
                'total_floor_area',
                'year_constructed',
                'classroom_count',
                'laboratory_count',
                'office_count',
                'restroom_count',
                'function_room_count',
            ]);
        });
    }
};
