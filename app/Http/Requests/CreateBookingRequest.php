<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number_plate' => 'required|string',
            'car_park_id'  => 'required|exists:car_parks,id',
            'date_from'    => 'required|date_format:Y-m-d|after_or_equal:today',
            'date_to'      => 'required|date_format:Y-m-d|after_or_equal:date_from',
        ];
    }
}
