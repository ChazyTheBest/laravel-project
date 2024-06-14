<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Payments') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <x-table wire:loading.class="opacity-75">
            <x-slot name="header">
                <x-table.header>ID</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('booking_id')"
                    :direction="$sortField === 'booking_id' ? $sortDirection : null">{{ __('Booking ID') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('status')"
                    :direction="$sortField === 'status' ? $sortDirection : null">{{ __('Status') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('response_data')"
                    :direction="$sortField === 'response_data' ? $sortDirection : null">{{ __('Response Data') }}</x-table.header>
                <x-table.header>Action</x-table.header>
            </x-slot>
            <x-slot name="body">
                @forelse ($payments as $key => $payment)
                    <x-table.row>
                        <x-table.cell>{{ $payment->id }}</x-table.cell>
                        <x-table.cell>{{ $payment->booking_id }}</x-table.cell>
                        <x-table.cell>{{ $payment->status->name }}</x-table.cell>
                        <x-table.cell>{{ count($payment->response_data ?? []) }}</x-table.cell>
                        <x-table.cell>
                            <button wire:click="openPaymentEdit('{{ $payment->id }}')"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('View') }}</button>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan=6>
                            <div class="flex justify-center items-center">
                                <span class="font-medium py-8 text-gray-400 text-xl">
                                    No data found...
                                </span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
        @if ($payments->hasPages())
            <div class="p-3">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <!-- Edit Modal -->
    <x-modals.form wire:model.live="isPaymentEditOpen">
        <x-slot name="title">
            {{ __('View Payment') }}
        </x-slot>

        <x-slot name="content">
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
        </x-slot>

        <x-slot name="cancel">closePaymentEdit</x-slot>
        <x-slot name="save">closePaymentEdit</x-slot>
    </x-modals.form>
</div>