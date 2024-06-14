<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @if ($step === 'room-show')
                {{ $room->name }}
            @elseif ($step === 'booking-create')
                {{ __('Booking Room') }}
            @endif
        </h2>
    </x-slot>

    @if ($step === 'room-show')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                    <div class="w-full rounded-lg text-center text-sm font-bold leading-6 text-white">
                        @livewire('room.booking-form', ['room' => $room])
                    </div>
                </div>
            </div>
        </div>
    @elseif ($step === 'booking-create')
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-10 sm:mt-0">
                @livewire('booking.create-form', ['room' => $room, 'bookingDates' => $bookingDates])
            </div>
        </div>
    @endif
</div>