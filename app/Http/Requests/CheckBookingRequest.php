<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Role;
use App\Rules\RoomAvailability;

class CheckBookingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => [
                'required',
                'exists:room,id',
                new RoomAvailability,
            ],
            'check_in_date' => [
                'required',
                'date',
                'after:today',
            ],
            'check_out_date' => [
                'required',
                'date',
                'after:check_in_date',
            ],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'room_id.required' => 'The room ID field is required.',
            'room_id.exists' => 'The selected room does not exist.',
            'check_in_date.required' => 'The check-in date field is required.',
            'check_in_date.date' => 'The check-in date must be a valid date.',
            'check_in_date.after' => 'The check-in date must be a future date after today.',
            'check_in_date.availability' => 'The selected date is not available.',
            'check_out_date.required' => 'The check-out date field is required.',
            'check_out_date.date' => 'The check-out date must be a valid date.',
            'check_out_date.after' => 'The check-out date must be after the check-in date.',
            'check_out_date.availability' => 'The selected date is not available.',
        ];
    }
}
