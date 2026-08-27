<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {

            $table->id();

            $table->foreignId('building_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('room_number');

            $table->string('room_name');

            $table->integer('capacity')->default(0);

            $table->integer('floor')->default(1);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};