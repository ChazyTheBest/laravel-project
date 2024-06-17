<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckPaymentMethodRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_method' => 'required|numeric|in:1,2',
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
            'payment_method.required' => 'The payment method is required. Please select one.',
            'payment_method.numeric' => 'Please select a valid payment method.',
            'payment_method.in' => 'The selected payment method is invalid.',
        ];
    }
}
