<?php declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\RoomAvailability;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ensure profile_id is present in the request
        $this->validate(['profile_id' => 'required']);

        // Check if the user is staff or owns the profile
        return request()->user()->ownsProfile($this->input('profile_id'));
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException('You do not own the selected profile.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile_id' => 'required|exists:profile,id',
            'room_id' => [
                'required',
                'exists:room,id',
                new RoomAvailability,
            ],
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
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
            'profile_id.required' => 'The profile ID field is required.',
            'profile_id.exists' => 'The selected profile does not exist.',
            'room_id.required' => 'The room ID field is required.',
            'room_id.exists' => 'The selected room does not exist.',
            'check_in_date.required' => 'The check-in date field is required.',
            'check_in_date.date' => 'The check-in date must be a valid date.',
            'check_in_date.after' => 'The check-in date must be a future date after today.',
            'check_in_date.after_or_equal' => 'The check-in date must be today or a future date.',
            'check_in_date.availability' => 'The selected date is not available.',
            'check_out_date.required' => 'The check-out date field is required.',
            'check_out_date.date' => 'The check-out date must be a valid date.',
            'check_out_date.after' => 'The check-out date must be after the check-in date.',
            'check_out_date.availability' => 'The selected date is not available.',
        ];
    }
}
