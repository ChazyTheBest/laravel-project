<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="w-full rounded-lg text-center text-sm font-bold leading-6 text-white">
                    <span class="font-semibold text-gray-600 dark:text-gray-500">{{ $booking->room->number }}</span>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $booking->room->name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400">{{ $booking->room->description }}</p>
                    <p class="text-gray-500 dark:text-gray-400">{{ trans_choice('Accommodation: :capacity guest|Accommodation: :capacity guests', $booking->room->capacity, ['capacity' => $booking->room->capacity]) }}</p>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('# of beds: :beds', ['beds' => $booking->room->beds]) }}</p>
                    <p class="text-lg text-gray-400 dark:text-gray-300 mt-4">{{ __('Booking Information:') }}</p>
                    <div class="mt-4">
                        <p class="text-md font-medium text-gray-700 dark:text-gray-300">Contact Name: <span>{{ $booking->profile->name }}</span></p>
                        <p class="text-md font-medium text-gray-700 dark:text-gray-300">Contact Phone: <span>{{ $booking->profile->phone }}</span></p>
                    </div>
                    <div class="flex flex-col md:flex-row mt-4 justify-center">
                        <div class="mb-4 md:mb-0">
                            <label for="check_in_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-in Date</label>
                            <span>{{ $booking->check_in_date }}</span>
                        </div>
                        <div class="ml-0 md:ml-4">
                            <label for="check_out_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-out Date</label>
                            <span>{{ $booking->check_out_date }}</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-md font-medium text-gray-700 dark:text-gray-300">{{ __('Booking Status: :status', ['status' => \App\Enums\BookingStatus::getStatusText($booking->status)]) }}</p>
                    </div>
                    <div class="mt-4">
                        @if($booking->status === App\Enums\BookingStatus::CONFIRMED)
                            <p class="text-md font-medium text-gray-700 dark:text-gray-300">Total Paid: $<span>{{ $booking->room->price_per_night }}</span></p>
                        @else
                            <p class="text-md font-medium text-gray-700 dark:text-gray-300">Total Due: $<span>{{ $booking->room->price_per_night }}</span></p>
                            @if ($isAvailable)
                                <a href="{{ route('payment.mockup', [$booking->payment->id]) }}">Retry payment</a>
                            @else
                                <p class="text-md font-medium text-red-700 dark:text-red-300">{{ __('This room is no longer available for the chosen dates.') }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>