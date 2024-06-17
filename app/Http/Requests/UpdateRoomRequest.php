<?php declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->hasRole(Role::STAFF);
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException('You are not authorized to perform this action.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number' => [
                'required',
                'numeric',
                Rule::unique('room', 'number')->ignore($this->input('id')),
            ],
            'capacity' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'name' => [
                'required',
                'string',
                Rule::unique('room', 'name')->ignore($this->input('id')),
            ],
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:1',
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
            'number.required' => 'The room number is required.',
            'number.numeric' => 'The room number must be a number.',
            'number.unique' => 'The room number has already been taken.',
            'capacity.required' => 'The room capacity is required.',
            'capacity.integer' => 'The room capacity must be an integer.',
            'capacity.min' => 'The room capacity must be at least :min.',
            'beds.required' => 'The number of beds is required.',
            'beds.integer' => 'The number of beds must be an integer.',
            'beds.min' => 'The number of beds must be at least :min.',
            'name.required' => 'The room name is required.',
            'name.string' => 'The room name must be a string.',
            'name.unique' => 'The room name has already been taken.',
            'description.required' => 'The room description is required.',
            'description.string' => 'The room description must be a string.',
            'price_per_night.required' => 'The price per night is required.',
            'price_per_night.numeric' => 'The price per night must be a number.',
            'price_per_night.min' => 'The price per night must be at least :min.',
        ];
    }
}
