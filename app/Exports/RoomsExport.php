<?php

namespace App\Exports;

use App\Models\Room;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoomsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Room::with('building')
            ->get()
            ->map(function ($room) {

                return [

                    'Building Name' => $room->building->building_name,

                    'Room Number' => $room->room_number,

                    'Room Name' => $room->room_name,

                    'Capacity' => $room->capacity,

                    'Floor' => $room->floor,

                ];

            });
    }

    public function headings(): array
    {
        return [

            'Building Name',

            'Room Number',

            'Room Name',

            'Capacity',

            'Floor',

        ];
    }
}