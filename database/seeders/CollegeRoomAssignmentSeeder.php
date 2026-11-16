<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Building;
use App\Models\College;
use App\Models\CollegeRoomAssignment;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CollegeRoomAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $academicYear = AcademicYear::where(
                'year_label',
                '2026-2027'
            )->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Existing buildings
            |--------------------------------------------------------------------------
            */

            $buildingNames = [
                'LA1' => 'DR. LEONCIO ANCHETA BUILDING I',
                'LA2' => 'DR. LEONCIO ANCHETA BUILDING II',
                'HH' => 'HONASAN HALL',
                'BADAR' => 'BADAR BUILDING',
                'SC' => 'DR. SOLEDAD F. CARINGAL BUILDING',
                'N1' => 'NURSING BUILDING I',
                'N2' => 'NURSING BUILDING II/MOCK HOSPITAL',
                'RW' => 'RESORT’S WORLD BUILDING',
                'TEC' => 'DR. TEOFIDEZ E. CALVERO BUILDING',
                'OB1' => 'DR. PEDRO T. ORATA BUILDING I',
                'JEP' => 'HON. JULIO E. PARAYNO BUILDING 1',
                'OB2' => 'DR. PEDRO T. ORATA BUILDING II',
            ];

            $buildingIds = [];

            foreach ($buildingNames as $key => $name) {

                $building = Building::where(
                    'building_name',
                    $name
                )->first();

                if (!$building && $key === 'RW') {
                    $building = Building::where(
                        'building_name',
                        'LIKE',
                        '%RESORT%WORLD%'
                    )->first();
                }

                if (!$building) {
                    throw new RuntimeException(
                        "Building not found: {$name}"
                    );
                }

                $buildingIds[$key] = $building->id;
            }

            /*
            |--------------------------------------------------------------------------
            | Room number prefixes
            |--------------------------------------------------------------------------
            */

            $prefixes = [
                'LA1' => 'LA I',
                'LA2' => 'LA II',
                'HH' => 'HH',
                'BADAR' => 'BB',
                'SC' => 'SC',
                'N1' => 'NI',
                'N2' => 'NII',
                'RW' => 'RW',
                'TEC' => 'TEC',
                'OB1' => 'OB I',
                'JEP' => 'JEP',
                'OB2' => 'OB II',
            ];

            /*
            |--------------------------------------------------------------------------
            | Registrar room assignments
            |--------------------------------------------------------------------------
            |
            | Format:
            | college, building, rooms, type, remarks
            |--------------------------------------------------------------------------
            */

            $assignments = [

                // CAS
                ['CAS', 'TEC', [204, 205], 'laboratory', null],
                ['CAS', 'OB1', [302, 303, 304, 305], 'lecture', null],
                ['CAS', 'RW', ['CHEMISTRY LAB 1'], 'laboratory', 'Shared laboratory room'],

                // CBMA
                ['CBMA', 'OB2', [201, 202, 203, 204, 205, 301, 302, 303], 'lecture', null],

                // CCJE
                ['CCJE', 'LA1', [102], 'lecture', null],
                ['CCJE', 'LA2', [106, 108], 'lecture', 'Shared with IGAS during Sunday classes for regular semesters'],
                ['CCJE', 'BADAR', ['JD1', 'JD2'], 'lecture', 'Shared with College of Law for night classes'],
                ['CCJE', 'OB1', [301], 'lecture', null],
                ['CCJE', 'OB2', [304, 305, 306], 'lecture', null],
                ['CCJE', 'SC', [102, 103], 'lecture', null],

                // CEA
                ['CEA', 'JEP', [101, 201, 202, 401, 402, 403], 'lecture', null],
                ['CEA', 'JEP', [301, 302, 303], 'laboratory', null],
                ['CEA', 'OB1', [402, 403, 404, 405], 'lecture', null],

                // CHS
                ['CHS', 'RW', [301, 302, 303, 304, 401, 402, 403, 404], 'lecture', null],
                ['CHS', 'RW', [305], 'laboratory', 'Shared room with CITE (COMLAB)'],
                ['CHS', 'N1', [101, 201, 202, 203], 'laboratory', null],
                ['CHS', 'N2', ['MH1', 'MH2'], 'laboratory', null],
                ['CHS', 'LA1', [104, 105], 'lecture', null],
                ['CHS', 'LA2', [210, 211], 'lecture', null],

                // CHTM
                ['CHTM', 'SC', [201, 202, 203, 204, 205], 'lecture', null],
                ['CHTM', 'SC', [105, 106, 107], 'laboratory', null],
                ['CHTM', 'LA2', [212, 213], 'lecture', null],

                // CHUMS
                ['CHUMS', 'HH', [201, 202, 203], 'lecture', null],
                ['CHUMS', 'LA1', [101], 'lecture', null],
                ['CHUMS', 'LA2', [214], 'lecture', null],

                // CITE
                ['CITE', 'TEC', [201, 202, 401, 402, 403, 404, 405], 'lecture', null],
                ['CITE', 'TEC', [301, 302, 303, 304], 'laboratory', null],
                ['CITE', 'RW', [305, 306], 'laboratory', 'RW 305 is shared with CHS'],

                // COP
                ['COP', 'RW', [101], 'lecture', null],
                ['COP', 'RW', [103], 'laboratory', null],
                ['COP', 'TEC', [203], 'lecture', null],
                ['COP', 'TEC', [101, 102], 'laboratory', null],

                // CTE
                ['CTE', 'HH', [204], 'lecture', null],
                ['CTE', 'RW', [102, 201, 202, 203, 204, 205, 206, 405, 406], 'lecture', null],
                ['CTE', 'OB2', ['SPED ROOM'], 'lecture', 'SPED Room'],

                // IGAS
                ['IGAS', 'LA2', [106, 108, 210, 211, 212, 213], 'lecture', 'Shared rooms for Sunday classes'],

                // COL
                ['COL', 'BADAR', ['CONFERENCE ROOM', 'JD1', 'JD2'], 'lecture', 'Night classes'],
            ];

            /*
            |--------------------------------------------------------------------------
            | Create or reuse rooms and assignments
            |--------------------------------------------------------------------------
            */

            foreach ($assignments as [
                $collegeCode,
                $buildingKey,
                $roomNumbers,
                $roomType,
                $remarks
            ]) {

                $college = College::where(
                    'college_code',
                    $collegeCode
                )->firstOrFail();

                $buildingId = $buildingIds[$buildingKey];

                foreach ($roomNumbers as $number) {

                    $roomNumber = is_numeric($number)
                        ? $prefixes[$buildingKey] . ' - ' . $number
                        : $number;

                    $room = Room::firstOrCreate(
                        [
                            'building_id' => $buildingId,
                            'room_number' => $roomNumber,
                        ],
                        [
                            'room_name' => $roomNumber,
                        ]
                    );

                    CollegeRoomAssignment::updateOrCreate(
                        [
                            'college_id' => $college->id,
                            'room_id' => $room->id,
                            'academic_year_id' => $academicYear->id,
                            'room_type' => $roomType,
                        ],
                        [
                            'remarks' => $remarks,
                        ]
                    );
                }
            }
        });
    }
}
