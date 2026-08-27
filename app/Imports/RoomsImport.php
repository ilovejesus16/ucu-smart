<?php

namespace App\Imports;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithColumnLimit;

class RoomsImport implements ToCollection, WithColumnLimit
{
    public array $rooms = [];

    protected array $uploaded = [];

    protected ?string $currentBuildingName = null;

    protected ?int $currentFloor = null;

    /*
    |--------------------------------------------------------------------------
    | Only read columns A-C
    |--------------------------------------------------------------------------
    */

    public function endColumn(): string
    {
        return 'C';
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $columnB = $this->clean($row[1] ?? '');
            $columnC = $this->clean($row[2] ?? '');

            /*
            |--------------------------------------------------------------------------
            | NUMBERED BUILDING
            |--------------------------------------------------------------------------
            */

            if (
                preg_match(
                    '/^BUILDING\s+(\d+)\s*[–—-]\s*(.+)$/iu',
                    $columnB,
                    $matches
                )
            ) {

                $this->currentBuildingName =
                    trim($matches[2]);

                $this->currentFloor = null;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | ADDITIONAL BUILDINGS
            |--------------------------------------------------------------------------
            */

            $additionalBuildings = [
                'FITNESS GYM I',
                'FITNESS GYM II',
                'GREEN HOME I',
                'GREEN HOME II',
                'UNIVERSITY GYMNASIUM',
                'HTM MOCK HOTEL',
                'UCU MINI–GYMNASIUM',
            ];

            $matchedAdditionalBuilding = false;

            foreach ($additionalBuildings as $buildingName) {

                if (
                    strcasecmp(
                        $columnB,
                        $buildingName
                    ) === 0
                ) {

                    $this->currentBuildingName =
                        $buildingName;

                    $this->currentFloor = null;

                    $matchedAdditionalBuilding = true;

                    break;
                }
            }

            if ($matchedAdditionalBuilding) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | FLOOR
            |--------------------------------------------------------------------------
            */

            if (
                preg_match(
                    '/^(\d+)(ST|ND|RD|TH)\s+FLOOR/iu',
                    $columnB,
                    $matches
                )
            ) {

                $this->currentFloor =
                    (int) $matches[1];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Additional buildings without floor
            |--------------------------------------------------------------------------
            */

            if (
                $this->currentBuildingName &&
                $columnC !== '' &&
                !$this->currentFloor
            ) {

                $this->currentFloor = 1;
            }

            /*
            |--------------------------------------------------------------------------
            | Ignore non-room rows
            |--------------------------------------------------------------------------
            */

            if (
                !$this->currentBuildingName ||
                $columnC === ''
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Room entries begin with a number
            |--------------------------------------------------------------------------
            */

            if (!preg_match('/^\d+\.\s*/', $columnC)) {
                continue;
            }

            $parsed = $this->parseRoom($columnC);

            if (!$parsed) {
                continue;
            }

            $roomNumber = $parsed['room_number'];

            $roomName = $parsed['room_name'];

            $capacity = $parsed['capacity'];

            /*
            |--------------------------------------------------------------------------
            | Find Building
            |--------------------------------------------------------------------------
            */

            $building = Building::whereRaw(
                'LOWER(TRIM(building_name)) = ?',
                [
                    strtolower(
                        trim(
                            $this->currentBuildingName
                        )
                    )
                ]
            )->first();

            if (!$building) {

                $this->rooms[] = [
                    'building_id' => null,
                    'building_name' =>
                        $this->currentBuildingName,
                    'room_number' => $roomNumber,
                    'room_name' => $roomName,
                    'capacity' => $capacity ?? 0,
                    'floor' => $this->currentFloor,
                    'status' => 'invalid',
                    'remarks' =>
                        'Building not found. Import the buildings first.',
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            |
            | Same room number is allowed in different buildings.
            |
            | Building A + 202 = valid
            | Building B + 202 = valid
            |
            | Only the SAME building + SAME room number is duplicate.
            |--------------------------------------------------------------------------
            */

            $duplicateKey =
                $building->id .
                '|' .
                strtolower(
                    trim($roomNumber)
                );

            /*
            |--------------------------------------------------------------------------
            | Duplicate inside Excel
            |--------------------------------------------------------------------------
            */

            if (
                isset(
                    $this->uploaded[$duplicateKey]
                )
            ) {

                $this->rooms[] = [
                    'building_id' =>
                        $building->id,

                    'building_name' =>
                        $building->building_name,

                    'room_number' =>
                        $roomNumber,

                    'room_name' =>
                        $roomName,

                    'capacity' =>
                        $capacity ?? 0,

                    'floor' =>
                        $this->currentFloor,

                    'status' =>
                        'duplicate',

                    'remarks' =>
                        'Same room number already appears in this building.',
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Duplicate already in database
            |--------------------------------------------------------------------------
            */

            $exists = Room::where(
                'building_id',
                $building->id
            )
                ->whereRaw(
                    'LOWER(TRIM(room_number)) = ?',
                    [
                        strtolower(
                            trim($roomNumber)
                        )
                    ]
                )
                ->exists();

            if ($exists) {

                $this->rooms[] = [
                    'building_id' =>
                        $building->id,

                    'building_name' =>
                        $building->building_name,

                    'room_number' =>
                        $roomNumber,

                    'room_name' =>
                        $roomName,

                    'capacity' =>
                        $capacity ?? 0,

                    'floor' =>
                        $this->currentFloor,

                    'status' =>
                        'duplicate',

                    'remarks' =>
                        'This room already exists in this building.',
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Mark as processed
            |--------------------------------------------------------------------------
            */

            $this->uploaded[$duplicateKey] = true;

            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            $remarks =
                'Ready to import.';

            if ($capacity === null) {

                $remarks .=
                    ' Capacity not specified in source file.';
            }

            /*
            |--------------------------------------------------------------------------
            | Add room
            |--------------------------------------------------------------------------
            */

            $this->rooms[] = [
                'building_id' =>
                    $building->id,

                'building_name' =>
                    $building->building_name,

                'room_number' =>
                    $roomNumber,

                'room_name' =>
                    $roomName,

                'capacity' =>
                    $capacity ?? 0,

                'floor' =>
                    $this->currentFloor,

                'status' =>
                    'new',

                'remarks' =>
                    $remarks,
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Parse Room
    |--------------------------------------------------------------------------
    */

    protected function parseRoom(string $value): ?array
    {
        $value = $this->clean($value);

        /*
        |--------------------------------------------------------------------------
        | Remove list number
        |--------------------------------------------------------------------------
        */

        $value = preg_replace(
            '/^\d+\.\s*/',
            '',
            $value
        );

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Remove trailing dash
        |
        | Example:
        |
        | HH – 202–
        |
        | becomes:
        |
        | HH – 202
        |--------------------------------------------------------------------------
        */

        $value = preg_replace(
            '/\s*[–—-]\s*$/u',
            '',
            $value
        );

        $value = trim($value);

        /*
        |--------------------------------------------------------------------------
        | Extract Capacity
        |--------------------------------------------------------------------------
        */

        $capacity = null;

        if (
            preg_match(
                '/\bCapacity\s*:?\s*(\d+)\b/iu',
                $value,
                $matches
            )
        ) {

            $capacity =
                (int) $matches[1];

            $value = preg_replace(
                '/\s*[–—-]?\s*Capacity\s*:?\s*\d+\b/iu',
                '',
                $value
            );

            $value = trim($value);
        }

        /*
        |--------------------------------------------------------------------------
        | Extract description in parentheses
        |--------------------------------------------------------------------------
        */

        $description = null;

        if (
            preg_match(
                '/\(([^()]*)\)\s*$/u',
                $value,
                $matches
            )
        ) {

            $description =
                trim($matches[1]);

            $position =
                strpos($value, '(');

            if ($position !== false) {

                $value = trim(
                    substr(
                        $value,
                        0,
                        $position
                    )
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | STANDARD ROOM FORMAT
        |
        | Examples:
        |
        | HH - 202
        | HH - 203
        | HH - 204
        | LA I - 101
        | RW - 305
        | TEC - 401
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/^(.+?)\s*[–—-]\s*(\d{3})(?:\s*[–—-]\s*(.+))?$/u',
                $value,
                $matches
            )
        ) {

            $prefix =
                $this->clean(
                    $matches[1]
                );

            $number =
                $matches[2];

            $trailingDescription =
                isset($matches[3])
                    ? $this->clean(
                        $matches[3]
                    )
                    : '';

            $roomNumber =
                $prefix .
                ' - ' .
                $number;

            $roomName =
                $description
                ?: $trailingDescription
                ?: $roomNumber;

            return [
                'room_number' =>
                    $roomNumber,

                'room_name' =>
                    $roomName,

                'capacity' =>
                    $capacity,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Named Room / Facility
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/^([A-Z][A-Z0-9 ]{0,30}?)\s*[–—-]\s*(.+)$/u',
                $value,
                $matches
            )
        ) {

            $roomNumber =
                $this->clean(
                    $matches[1]
                );

            $roomName =
                $description
                ?: $this->clean(
                    $matches[2]
                );

            return [
                'room_number' =>
                    $roomNumber,

                'room_name' =>
                    $roomName,

                'capacity' =>
                    $capacity,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback
        |--------------------------------------------------------------------------
        */

        return [
            'room_number' =>
                $value,

            'room_name' =>
                $description ?: $value,

            'capacity' =>
                $capacity,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Clean Excel Text
    |--------------------------------------------------------------------------
    */

    protected function clean($value): string
    {
        if ($value === null) {
            return '';
        }

        $value = (string) $value;

        /*
        | Normalize dash characters
        */

        $value = str_replace(
            ['—', '-'],
            '–',
            $value
        );

        /*
        | Normalize whitespace
        */

        $value = preg_replace(
            '/\s+/u',
            ' ',
            $value
        );

        return trim($value);
    }
}