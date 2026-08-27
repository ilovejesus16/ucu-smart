<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'building_id' => 'required|exists:buildings,id',
            'room_number' => 'required|string|max:50',
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'floor' => 'required|integer|min:1',
        ];
    }
}