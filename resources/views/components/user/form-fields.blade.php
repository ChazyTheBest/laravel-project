<div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="role" value="{{ __('User Role') }}:" />
        <x-select :options="App\Enums\Role::getOptions()" class="mt-1 block w-full" id="profile" wire:model="role"></x-select>
        <x-input-error for="role" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="name" value="{{ __('Name') }}" />
        <x-input name="name" id="name" type="text" class="mt-1 block w-full" wire:model="name" />
        <x-input-error for="name" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input id="email" type="text" class="mt-1 block w-full" wire:model="email" />
        <x-input-error for="email" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="password" value="{{ __('Password') }}" />
        <x-input id="password" type="text" class="mt-1 block w-full" wire:model="password" />
        <x-input-error for="password" class="mt-2" />
    </div>
    <div class="col-span-6 sm:col-span-4">
        <x-label for="password_confirmation" value="{{ __(' Confirm Password') }}" />
        <x-input id="password_confirmation" type="text" class="mt-1 block w-full" wire:model="password_confirmation" />
        <x-input-error for="password_confirmation" class="mt-2" />
    </div>
</div>