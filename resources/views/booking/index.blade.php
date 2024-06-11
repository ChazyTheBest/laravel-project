<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="grid grid-cols-4 gap-4 rounded-lg text-center text-sm font-bold leading-6 text-white">
                    @if ($bookings->isEmpty())
                        <p>No bookings found.</p>
                    @else
                        @foreach ($bookings as $booking)
                            <div class="rounded-lg dark:bg-indigo-900 bg-indigo-300 p-4 shadow-lg">
                                <a href="{{ route('booking.show', $booking->id) }}" class="block">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $booking->room->name }}</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Booked for: {{ $booking->profile->name }}</p>
                                    <p class="text-gray-500 dark:text-gray-400">From: {{ $booking->check_in_date }}</p>
                                    <p class="text-gray-500 dark:text-gray-400">To: {{ $booking->check_out_date }}</p>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
