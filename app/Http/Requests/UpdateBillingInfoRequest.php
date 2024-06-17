<?php declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBillingInfoRequest extends FormRequest
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
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
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
            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',
            'city.required' => 'The city field is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than 255 characters.',
            'state.required' => 'The state field is required.',
            'state.string' => 'The state must be a string.',
            'state.max' => 'The state may not be greater than 255 characters.',
            'postal_code.required' => 'The postal code field is required.',
            'postal_code.string' => 'The postal code must be a string.',
            'postal_code.max' => 'The postal code may not be greater than 20 characters.',
            'country.required' => 'The country field is required.',
            'country.string' => 'The country must be a string.',
            'country.max' => 'The country may not be greater than 255 characters.',
        ];
    }
}
