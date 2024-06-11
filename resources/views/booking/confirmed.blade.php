<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Booking Confirmed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="w-full rounded-lg text-center text-sm font-bold leading-6 text-white">
                    <p>Booking Successfully Confirmed!</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
