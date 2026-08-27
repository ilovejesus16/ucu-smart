<?php

namespace App\Imports;

use App\Models\Building;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithColumnLimit;

class BuildingsImport implements ToCollection, WithColumnLimit
{
    public array $buildings = [];

    protected array $uploaded = [];

    /*
    |--------------------------------------------------------------------------
    | Only read columns A-B
    |--------------------------------------------------------------------------
    */

    public function endColumn(): string
    {
        return 'B';
    }

    public function collection(Collection $rows)
    {
        /*
        |--------------------------------------------------------------------------
        | Additional UCU buildings/facilities
        |--------------------------------------------------------------------------
        |
        | These are also treated as buildings in UCU Smart+.
        |
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

        foreach ($rows as $row) {

            /*
            |--------------------------------------------------------------------------
            | Actual Excel structure:
            |
            | Column A = mostly blank
            | Column B = Building / Floor
            | Column C = Room
            |
            | We only read A-B here to keep memory usage low.
            |--------------------------------------------------------------------------
            */

            $value = $this->clean($row[1] ?? '');

            if ($value === '') {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Numbered Buildings
            |
            | Example:
            | BUILDING 1 – DR. LEONCIO ANCHETA BUILDING I
            |--------------------------------------------------------------------------
            */

            if (
                preg_match(
                    '/^BUILDING\s+(\d+)\s*[–—-]\s*(.+)$/iu',
                    $value,
                    $matches
                )
            ) {

                $buildingNumber = (int) $matches[1];

                $buildingCode =
                    'B' .
                    str_pad(
                        $buildingNumber,
                        2,
                        '0',
                        STR_PAD_LEFT
                    );

                $buildingName = trim($matches[2]);

                $this->addBuilding(
                    $buildingCode,
                    $buildingName
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Ignore Floor headings
            |
            | Example:
            | 1ST FLOOR
            | 2ND FLOOR
            | 3RD FLOOR
            |--------------------------------------------------------------------------
            */

            if (
                preg_match(
                    '/^\d+(ST|ND|RD|TH)\s+FLOOR\b/iu',
                    $value
                )
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Additional UCU Buildings
            |--------------------------------------------------------------------------
            */

            foreach ($additionalBuildings as $index => $additionalBuilding) {

                if (
                    strcasecmp(
                        $value,
                        $additionalBuilding
                    ) === 0
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Numbered after the official Building 1-12 list
                    |
                    | B13 = Fitness Gym I
                    | B14 = Fitness Gym II
                    | ...
                    | B19 = UCU Mini-Gymnasium
                    |--------------------------------------------------------------------------
                    */

                    $buildingCode =
                        'B' .
                        str_pad(
                            13 + $index,
                            2,
                            '0',
                            STR_PAD_LEFT
                        );

                    $this->addBuilding(
                        $buildingCode,
                        $additionalBuilding
                    );

                    break;
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Add Building
    |--------------------------------------------------------------------------
    */

    protected function addBuilding(
        string $buildingCode,
        string $buildingName
    ): void {

        $buildingName = $this->clean($buildingName);

        if ($buildingName === '') {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate key
        |--------------------------------------------------------------------------
        */

        $key = strtolower($buildingName);

        /*
        |--------------------------------------------------------------------------
        | Duplicate inside Excel
        |--------------------------------------------------------------------------
        */

        if (in_array($key, $this->uploaded, true)) {

            $this->buildings[] = [
                'building_code' => $buildingCode,
                'building_name' => $buildingName,
                'status' => 'duplicate',
            ];

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate in database
        |--------------------------------------------------------------------------
        */

        $exists = Building::whereRaw(
            'LOWER(building_name) = ?',
            [$key]
        )->exists();

        if ($exists) {

            $this->buildings[] = [
                'building_code' => $buildingCode,
                'building_name' => $buildingName,
                'status' => 'duplicate',
            ];

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Mark as processed
        |--------------------------------------------------------------------------
        */

        $this->uploaded[] = $key;

        /*
        |--------------------------------------------------------------------------
        | New Building
        |--------------------------------------------------------------------------
        */

        $this->buildings[] = [
            'building_code' => $buildingCode,
            'building_name' => $buildingName,
            'status' => 'new',
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