<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // building_code already exists in the database.
        // This migration only makes sure existing records have codes.

        $buildings = DB::table('buildings')
            ->orderBy('id')
            ->get();

        $number = 1;

        foreach ($buildings as $building) {

            if (empty($building->building_code)) {
                DB::table('buildings')
                    ->where('id', $building->id)
                    ->update([
                        'building_code' => 'B' . str_pad(
                            $number,
                            2,
                            '0',
                            STR_PAD_LEFT
                        ),
                    ]);
            }

            $number++;
        }
    }

    public function down(): void
    {
        // Nothing to undo because building_code
        // already existed before this migration.
    }
};