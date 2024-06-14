<div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="profile_id" value="{{ __('Profile ID') }}" />
        <x-input name="profile_id" id="profile_id" type="number" class="mt-1 block w-full" wire:model="profile_id" />
        <x-input-error for="profile_id" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="room_id" value="{{ __('Room ID') }}" />
        <x-input id="room_id" type="number" class="mt-1 block w-full" wire:model="room_id" />
        <x-input-error for="room_id" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="status" value="{{ __('Status') }}" />
        <x-select :options="App\Enums\BookingStatus::getOptions()" name="status" id="status" type="number" class="mt-1 block w-full" wire:model="status" />
        <x-input-error for="status" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="check_in_date" value="{{ __('Check-In Date') }}" />
        <x-input id="check_in_date" type="date" class="mt-1 block w-full" wire:model="check_in_date" />
        <x-input-error for="check_in_date" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="check_out_date" value="{{ __('Check-Out Date') }}" />
        <x-input id="check_out_date" type="date" class="mt-1 block w-full" wire:model="check_out_date" />
        <x-input-error for="check_out_date" class="mt-2" />
    </div>
</div>