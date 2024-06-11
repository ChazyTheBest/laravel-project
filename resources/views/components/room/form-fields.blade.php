<div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="number" value="{{ __('Number') }}" />
        <x-input name="number" id="number" type="number" class="mt-1 block w-full" wire:model="number" />
        <x-input-error for="number" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="capacity" value="{{ __('Capacity') }}" />
        <x-input id="capacity" type="number" class="mt-1 block w-full" wire:model="capacity" />
        <x-input-error for="capacity" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="beds" value="{{ __('Beds') }}" />
        <x-input id="beds" type="number" class="mt-1 block w-full" wire:model="beds" />
        <x-input-error for="beds" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="name" value="{{ __('Name') }}" />
        <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
        <x-input-error for="name" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="description" value="{{ __('Description') }}" />
        <x-input id="description" type="text" class="mt-1 block w-full" wire:model="description" />
        <x-input-error for="description" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="price_per_night" value="{{ __('Price Per Night') }}" />
        <x-input id="price_per_night" type="number" class="mt-1 block w-full" wire:model="price_per_night" />
        <x-input-error for="price_per_night" class="mt-2" />
    </div>
</div>