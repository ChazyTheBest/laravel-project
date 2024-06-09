<div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="name" value="{{ __('Name') }}" />
        <x-input name="name" id="name" type="text" class="mt-1 block w-full" wire:model="name" />
        <x-input-error for="name" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="phone" value="{{ __('Phone') }}" />
        <x-input id="phone" type="text" class="mt-1 block w-full" wire:model="phone" />
        <x-input-error for="phone" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="phone_2" value="{{ __('Phone 2') }}" />
        <x-input id="phone_2" type="text" class="mt-1 block w-full" wire:model="phone_2" />
        <x-input-error for="phone_2" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="address" value="{{ __('Address') }}" />
        <x-input id="address" type="text" class="mt-1 block w-full" wire:model="address" />
        <x-input-error for="address" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="city" value="{{ __('City') }}" />
        <x-input id="city" type="text" class="mt-1 block w-full" wire:model="city" />
        <x-input-error for="city" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="state" value="{{ __('State') }}" />
        <x-input id="state" type="text" class="mt-1 block w-full" wire:model="state" />
        <x-input-error for="state" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="postal_code" value="{{ __('Postal Code') }}" />
        <x-input id="postal_code" type="text" class="mt-1 block w-full" wire:model="postal_code" />
        <x-input-error for="postal_code" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="country" value="{{ __('Country') }}" />
        <x-input id="country" type="text" class="mt-1 block w-full" wire:model="country" />
        <x-input-error for="country" class="mt-2" />
    </div>
</div>