<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarParkAvailabilityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date_from' => 'sometimes|nullable|date_format:Y-m-d|after_or_equal:today',
            'date_to'   => 'sometimes|nullable|date_format:Y-m-d|after_or_equal:date_from',
        ];
    }
}
