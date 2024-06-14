<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Bookings') }}
        </h2>
    </x-slot>

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
                            <button wire:click="openBookingEdit('{{ $booking->id }}')"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</button>
                            <button wire:click="openBookingDelete('{{ $booking->id }}')"
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

    <!-- Edit Modal -->
    <x-modals.form wire:model.live="isBookingEditOpen">
        <x-slot name="title">
            {{ __('Edit Booking') }}
        </x-slot>

        <x-slot name="content">
            @include('components.booking.form-fields')
        </x-slot>

        <x-slot name="cancel">closeBookingEdit</x-slot>
        <x-slot name="save">updateBooking</x-slot>
    </x-modals.form>
    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="isBookingDeleteOpen">
        <x-slot name="title">
            {{ __('Delete Booking') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this booking?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click.prevent="closeBookingDelete">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click.prevent="destroyBooking" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>