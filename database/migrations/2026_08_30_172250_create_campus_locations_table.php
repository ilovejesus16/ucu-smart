<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campus_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number')->unique();
            $table->string('name');
            $table->string('type')->default('Building');
            $table->decimal('map_x', 8, 4)->nullable();
            $table->decimal('map_y', 8, 4)->nullable();
            $table->timestamps();
        });

        DB::table('campus_locations')->insert([
            ['number' => 1, 'name' => 'Dr. Leoncio Ancheta Bldg. 1', 'type' => 'Building'],
            ['number' => 2, 'name' => 'Dr. Leoncio Ancheta Bldg. 2', 'type' => 'Building'],
            ['number' => 3, 'name' => 'Honasan Hall', 'type' => 'Building'],
            ['number' => 4, 'name' => 'Badar Building', 'type' => 'Building'],
            ['number' => 5, 'name' => 'Dr. Solidad F. Caringal Bldg.', 'type' => 'Building'],
            ['number' => 6, 'name' => 'Nursing Bldg. 1', 'type' => 'Building'],
            ['number' => 7, 'name' => 'Nursing Bldg. 2', 'type' => 'Building'],
            ['number' => 8, 'name' => 'Resorts World', 'type' => 'Building'],
            ['number' => 9, 'name' => 'Dr. Teofidez E. Calvero', 'type' => 'Building'],
            ['number' => 10, 'name' => 'Dr. Pedro T. Orata Bldg. 1', 'type' => 'Building'],
            ['number' => 11, 'name' => 'Julio E. Parayno Bldg.', 'type' => 'Building'],
            ['number' => 12, 'name' => 'Dr. Pedro T. Orata Bldg. 2', 'type' => 'Building'],
            ['number' => 13, 'name' => 'Gymnasium', 'type' => 'Facility'],
            ['number' => 14, 'name' => 'Sewer Treatment Plant', 'type' => 'Facility'],
            ['number' => 15, 'name' => 'Mini Gymnasium', 'type' => 'Facility'],
            ['number' => 16, 'name' => 'P.E. Office', 'type' => 'Facility'],
            ['number' => 17, 'name' => 'Fitness Gym. 2', 'type' => 'Facility'],
            ['number' => 18, 'name' => 'Fitness Gym. 1', 'type' => 'Facility'],
            ['number' => 19, 'name' => 'Wellness Spa', 'type' => 'Facility'],
            ['number' => 20, 'name' => 'E.M.A.S.', 'type' => 'Facility'],
            ['number' => 21, 'name' => 'Green Home', 'type' => 'Facility'],
            ['number' => 22, 'name' => 'Generator Set', 'type' => 'Facility'],
            ['number' => 23, 'name' => 'Animal Clinic', 'type' => 'Facility'],
            ['number' => 24, 'name' => 'University Clinic', 'type' => 'Facility'],
            ['number' => 25, 'name' => 'Mock Hotel', 'type' => 'Facility'],
            ['number' => 26, 'name' => 'Audio Visual Room', 'type' => 'Facility'],
            ['number' => 27, 'name' => 'Dr. Orata Park', 'type' => 'Open Space'],
            ['number' => 28, 'name' => 'Quadrangle', 'type' => 'Open Space'],
            ['number' => 29, 'name' => 'Square Garden', 'type' => 'Open Space'],
            ['number' => 30, 'name' => 'NDRRMO', 'type' => 'Facility'],
            ['number' => 31, 'name' => 'Airplane Bldg.', 'type' => 'Facility'],
            ['number' => 32, 'name' => 'Turnstile', 'type' => 'Facility'],
            ['number' => 33, 'name' => 'Entrep. & Law Bldg.', 'type' => 'Building'],
            ['number' => 34, 'name' => 'Toilet', 'type' => 'Facility'],
            ['number' => 35, 'name' => 'Parking', 'type' => 'Open Space']
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('campus_locations');
    }
};
