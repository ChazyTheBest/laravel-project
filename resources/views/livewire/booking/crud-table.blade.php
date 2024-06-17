<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <x-table wire:loading.class="opacity-75">
            <x-slot name="header">
                <x-table.header>ID</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('profile_id')"
                                :direction="$sortField === 'profile_id' ? $sortDirection : null">{{ __('Profile') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('room_id')"
                                :direction="$sortField === 'room_id' ? $sortDirection : null">{{ __('Room') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('status')"
                                :direction="$sortField === 'status' ? $sortDirection : null">{{ __('Status') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('check_in_date')"
                                :direction="$sortField === 'check_in_date' ? $sortDirection : null">{{ __('Check-in Date') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('check_out_date')"
                                :direction="$sortField === 'check_out_date' ? $sortDirection : null">{{ __('Check-out Date') }}</x-table.header>
                <x-table.header>Action</x-table.header>
            </x-slot>
            <x-slot name="body">
                @forelse ($bookings as $key => $booking)
                    <x-table.row>
                        <x-table.cell>{{ $booking->id }}</x-table.cell>
                        <x-table.cell>{{ $booking->profile_id }}</x-table.cell>
                        <x-table.cell>{{ $booking->room_id }}</x-table.cell>
                        <x-table.cell>{{ $booking->status->name }}</x-table.cell>
                        <x-table.cell>{{ $booking->check_in_date }}</x-table.cell>
                        <x-table.cell>{{ $booking->check_out_date }}</x-table.cell>
                        <x-table.cell>
                            <button wire:click="openEditModal('{{ $booking->id }}')"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</button>
                            <button wire:click="openDeleteModal('{{ $booking->id }}')"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">{{ __('Delete') }}</button>
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
        @if ($bookings->hasPages())
            <div class="p-3">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    @if ($isModalOpen)

        @if ($modalType === 'delete')
            <!-- Confirmation Delete Modal -->
            <x-confirmation-modal
                wire:model.live="isModalOpen"
                :title="$modalTitle"
                :content="$modalContent"
                :cancel="$modalCancelMethod"
                :save="$modalSaveMethod"
                :btn="$modalBtnName"
            />
        @else
            <!-- Edit Modal -->
            <x-modals.form
                wire:model.live="isModalOpen"
                :title="$modalTitle"
                :content="$modalContent"
                :cancel="$modalCancelMethod"
                :save="$modalSaveMethod"
                :btn="$modalBtnName"
            />
        @endif

    @endif
</div>
