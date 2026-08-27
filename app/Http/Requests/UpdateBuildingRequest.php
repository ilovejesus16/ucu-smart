<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBuildingRequest extends FormRequest
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

            'building_name' => [

                'required',
                'string',
                'max:255',

                Rule::unique('buildings', 'building_name')
                    ->ignore($this->building),

            ],

            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ];
    }
}