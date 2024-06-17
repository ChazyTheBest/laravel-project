<div>
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
                            <button wire:click="openViewModal('{{ $payment->id }}')"
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

    @if ($isModalOpen)

            <!-- View Modal -->
            <x-modals.form
                wire:model.live="isModalOpen"
                :title="$modalTitle"
                :content="$modalContent"
                :cancel="$modalCancelMethod"
                :save="$modalSaveMethod"
                :btn="$modalBtnName"
            />

    @endif
</div>
