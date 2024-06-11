<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment Gateway Mockup') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-10 sm:mt-0">
                <x-form-section submit="" formId="paymentForm">
                    <x-slot name="title">
                        {{ __('Payment Information') }}
                    </x-slot>

                    <x-slot name="description">
                        {{ __('We need your credit/debit card details.') }}
                    </x-slot>

                    <x-slot name="form">
                        @csrf
                        <!-- Card Number -->
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="number" value="{{ __('Number') }}" />
                            <x-input id="number" type="text" class="mt-1 block w-full" />
                        </div>
                        <div class="mt-2">
                            <div class="flex items-center">
                                <x-input id="p1" type="radio" class="mt-1 mr-2" name="payment_status" value="1" required />
                                <x-label for="p1" value="{{ __('Payment Succeds') }}" />
                            </div>
                            <div class="flex items-center mt-2">
                                <x-input id="p2" type="radio" class="mt-1 mr-2" name="payment_status" value="2" required />
                                <x-label for="p2" value="{{ __('Payment Fails') }}" />
                            </div>
                        </div>
                    </x-slot>

                    <x-slot name="actions">
                        <x-button>
                            {{ __('Pay') }}
                        </x-button>
                    </x-slot>
                </x-form-section>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('#paymentForm');

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const paymentStatus = document.querySelector('input[name="payment_status"]:checked').value;

                // Mockup payment gateway response data
                const paymentData = {
                    payment_status: paymentStatus,
                    // Add other mockup data here
                };

                fetch('{{ route('payment.callback', ['payment' => $payment->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(paymentData)
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Network response was not ok.');
                    }
                })
                .then(data => {
                    if (data.hasOwnProperty('success')) {
                        // Payment status updated successfully
                        window.location.href = '{{ route('booking.confirmed') }}';
                    } else if (data.hasOwnProperty('error')) {
                        // Error updating payment status
                        window.location.href = '{{ route('booking.failed') }}';
                    } else {
                        // Unexpected response from server
                        throw new Error('Unexpected response from server.');
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    // Handle errors or display error message to the user
                });
            });
        });
    </script>
</x-app-layout>