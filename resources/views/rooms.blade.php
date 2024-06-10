<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Our Rooms') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="grid grid-cols-4 gap-4 rounded-lg text-center text-sm font-bold leading-6 text-white">
                    @foreach ($rooms as $room)
                        <div class="rounded-lg dark:bg-indigo-900 bg-indigo-300 p-4 shadow-lg">
                            <a href="{{ route('room.show', $room->id) }}" class="block">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $room->name }}</h3>
                                <p class="text-gray-500 dark:text-gray-400">{{ $room->description }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
