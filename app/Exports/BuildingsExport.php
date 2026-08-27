<?php

namespace App\Exports;

use App\Models\Building;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BuildingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Building::select(
            'building_name'
        )->orderBy('building_name')->get();
    }

    public function headings(): array
    {
        return [

            'Building Name',

        ];
    }
}