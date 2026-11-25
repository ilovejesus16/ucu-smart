<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('building_announcements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('building_id')
                ->nullable()
                ->constrained('buildings')
                ->nullOnDelete();

            $table->string('building_code', 50);
            $table->string('building_name');
            $table->string('title')->default('New Building Added');
            $table->text('message');

            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('building_announcements');
    }
};
