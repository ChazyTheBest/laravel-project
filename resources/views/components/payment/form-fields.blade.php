<div class="col-span-6 sm:col-span-4">
    <x-label for="booking_id" value="{{ __('Booking ID') }}" />
    <x-input name="booking_id" id="booking_id" type="number" class="mt-1 block w-full" wire:model="booking_id" />
</div>
<div class="col-span-6 sm:col-span-4">
    <x-label for="status" value="{{ __('Status') }}" />
    <x-select :options="App\Enums\PaymentStatus::getOptions()" name="status" id="status" type="number" class="mt-1 block w-full" wire:model="status" />
</div>
<div class="col-span-6 sm:col-span-4">
    <x-label for="response_data" value="{{ __('Response Data') }}" />
    <textarea wire:model="response_data_form" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"></textarea>
</div>
