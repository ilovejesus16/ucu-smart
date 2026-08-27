<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBuildingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'building_name' => 'required|string|max:255|unique:buildings,building_name',

            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ];
    }
}