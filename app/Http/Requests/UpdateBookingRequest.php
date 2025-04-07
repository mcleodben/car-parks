<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
            'number_plate' => 'sometimes|string',
            'car_park_id'  => 'sometimes|exists:car_parks,id',
            'date_from'    => 'sometimes|date_format:Y-m-d|after_or_equal:today',
            'date_to'      => 'sometimes|date_format:Y-m-d|after_or_equal:date_from',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('number_plate')) {
            $this->merge([
                'number_plate' => preg_replace('/\s+/', '', $this->number_plate),
            ]);
        }
    }
}
