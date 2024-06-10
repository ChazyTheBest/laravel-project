<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment Gateway Mockup') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-10 sm:mt-0">
                <x-form-section submit="">
                    <x-slot name="title">
                        {{ __('Payment Information') }}
                    </x-slot>

                    <x-slot name="description">
                        {{ __('We need your credit/debit card details.') }}
                    </x-slot>

                    <x-slot name="form">
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
</x-app-layout>