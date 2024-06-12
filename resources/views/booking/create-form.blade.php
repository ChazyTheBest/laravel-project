<x-form-section submit="book">
    <x-slot name="title">
        {{ __('Booking :room_name', ['room_name' => $room->name]) }}
    </x-slot>

    <x-slot name="description">
        <span>{{ __('We just need a few details before we can book the room for you.') }}</span>
        <span>Check-in Date: {{ $check_in_date }}</span>
        <span>Check-out Date: {{ $check_out_date }}</span>
        <span>Room Price per Night: {{ $room->price_per_night }}</span>
    </x-slot>

    <x-slot name="form">
        <x-input type="hidden" wire:model="room_id" />
        <x-input type="hidden" wire:model="check_in_date" />
        <x-input type="hidden" wire:model="check_out_date" />

        <div class="col-span-6 sm:col-span-4">
            <x-label for="profile" value="{{ __('Select Profile') }}:" />
            <x-select :options="$profiles" class="mt-1 block w-full" id="profile" wire:model="profile_id"></x-select>
            <x-input-error for="profile_id" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label value="{{ __('Select Payment Method') }}:"/></label>
            <div class="mt-2">
                <div class="flex items-center">
                    <x-input id="credit_debit" type="radio" class="mt-1 mr-2" wire:model="payment_method" value="1" required />
                    <x-label for="credit_debit" value="{{ __('Credit/Debit Card') }}" />
                </div>
                <div class="flex items-center mt-2">
                    <x-input id="paypal" type="radio" class="mt-1 mr-2" wire:model="payment_method" value="2" required />
                    <x-label for="paypal" value="{{ __('PayPal') }}" />
                </div>
            </div>
            <x-input-error for="payment_method" class="mt-2" />
        </div>

        <x-label value="{{ __('Billing Information') }}:" class="col-span-6 sm:col-span-4" /></label>

        <!-- Address -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="address" value="{{ __('Address') }}" />
            <x-input id="address" type="text" class="mt-1 block w-full" wire:model="address" />
            <x-input-error for="address" class="mt-2" />
        </div>

        <!-- City -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="city" value="{{ __('City') }}" />
            <x-input id="city" type="text" class="mt-1 block w-full" wire:model="city" />
            <x-input-error for="city" class="mt-2" />
        </div>

        <!-- State -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="state" value="{{ __('State') }}" />
            <x-input id="state" type="text" class="mt-1 block w-full" wire:model="state" />
            <x-input-error for="state" class="mt-2" />
        </div>

        <!-- Postal Code -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="postal_code" value="{{ __('Postal Code') }}" />
            <x-input id="postal_code" type="text" class="mt-1 block w-full" wire:model="postal_code" />
            <x-input-error for="postal_code" class="mt-2" />
        </div>

        <!-- Country -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="country" value="{{ __('Country') }}" />
            <x-input id="country" type="text" class="mt-1 block w-full" wire:model="country" />
            <x-input-error for="country" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-input-error for="room_id" />
        <x-button>
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>