<div>
    <form wire:submit.prevent="submitForm" class="rounded-lg dark:bg-indigo-900 bg-indigo-300 p-4 shadow-lg">
        @csrf
        <span class="font-semibold text-gray-600 dark:text-gray-500">{{ $room->number }}</span>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $room->name }}</h3>
        <p class="text-gray-500 dark:text-gray-400">{{ $room->description }}</p>
        <p class="text-gray-500 dark:text-gray-400">{{ trans_choice('Accommodation: :capacity guest|Accommodation: :capacity guests', $room->capacity, ['capacity' => $room->capacity]) }}</p>
        <p class="text-gray-500 dark:text-gray-400">{{ __('# of beds: :beds', ['beds' => $room->beds]) }}</p>
        <div class="flex flex-col md:flex-row mt-4 justify-center">
            <div class="mb-4 md:mb-0">
                <label for="check_in_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-in Date</label>
                <input value="{{ $checkInDateMin }}" type="text" id="check_in_date" wire:model="check_in_date" class="mt-1 block w-full shadow-sm sm:text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md flatpickr">
                <x-input-error for="check_in_date" class="mt-2" />
            </div>
            <div class="ml-0 md:ml-4">
                <label for="check_out_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-out Date</label>
                <input value="{{ $checkOutDateMin }}" type="text" id="check_out_date" wire:model="check_out_date" class="mt-1 block w-full shadow-sm sm:text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md flatpickr">
                <x-input-error for="check_out_date" class="mt-2" />
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Price Per Night: ${{ $room->price_per_night }}</p>
            <p id="totalPrice" class="text-md font-medium text-gray-700 dark:text-gray-300">Total Price: $<span>{{ $room->price_per_night }}</span></p>
        </div>
        <x-input-error for="room_id" />
        <button id="calculateTotalPrice" type="button" class="mt-4 inline-block bg-blue-500 dark:bg-indigo-600 hover:bg-blue-700 dark:hover:bg-indigo-700 text-white py-2 px-4 rounded">Calculate Total Price</button>
        <button type="submit" class="mt-4 inline-block bg-blue-500 dark:bg-indigo-600 hover:bg-blue-700 dark:hover:bg-indigo-700 text-white py-2 px-4 rounded">Book Now</a>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkInDateInput = document.getElementById('check_in_date');
            const checkOutDateInput = document.getElementById('check_out_date');
            const totalPriceElement = document.getElementById('totalPrice').querySelector('span');
            const calculateButton = document.getElementById('calculateTotalPrice');
            const pricePerNight = {{ $room->price_per_night }};
            const unavailableDates = @json($room->getUnavailableDates());
            const checkInDateMin = '{{ $checkInDateMin }}';
            const checkOutDateMin = '{{ $checkOutDateMin }}';

            flatpickr(checkInDateInput, {
                dateFormat: 'Y-m-d',
                minDate: checkInDateMin,
                disable: unavailableDates,
                defaultDate: checkInDateMin,
                allowInput: true,

            });

            flatpickr(checkOutDateInput, {
                dateFormat: 'Y-m-d',
                minDate: checkOutDateMin,
                disable: unavailableDates,
                defaultDate: checkOutDateMin,
                allowInput: true,
            });

            function calculateTotalPrice() {
                const checkInDate = new Date(checkInDateInput.value);
                const checkOutDate = new Date(checkOutDateInput.value);
                const dayDifference = (checkOutDate - checkInDate) / (1000 * 3600 * 24);

                if (dayDifference > 0) {
                    totalPriceElement.innerText = (dayDifference * pricePerNight).toFixed(2);
                }
            }

            calculateButton.addEventListener('click', calculateTotalPrice);
        });
    </script>
</div>
