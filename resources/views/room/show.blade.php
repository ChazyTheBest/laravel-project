<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $room->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="w-full rounded-lg text-center text-sm font-bold leading-6 text-white">
                    <form id="roomForm" action="{{ route('booking.info', ['room' => $room->id]) }}" method="POST" class="rounded-lg dark:bg-indigo-900 bg-indigo-300 p-4 shadow-lg">
                        @csrf
                        <span class="font-semibold text-gray-600 dark:text-gray-500">{{ $room->number }}</span>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $room->name }}</h3>
                        <p class="text-gray-500 dark:text-gray-400">{{ $room->description }}</p>
                        <p class="text-gray-500 dark:text-gray-400">{{ trans_choice('Accommodation: :capacity guest|Accommodation: :capacity guests', $room->capacity, ['capacity' => $room->capacity]) }}</p>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('# of beds: :beds', ['beds' => $room->beds]) }}</p>
                        <div class="flex flex-col md:flex-row mt-4 justify-center">
                            <div class="mb-4 md:mb-0">
                                <label for="check_in_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-in Date</label>
                                <input type="date" id="check_in_date" name="check_in_date" class="mt-1 block w-full shadow-sm sm:text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                                <div id="checkInError" class="text-red-500 text-sm mb-2 hidden"></div>
                            </div>
                            <div class="ml-0 md:ml-4">
                                <label for="check_out_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-out Date</label>
                                <input type="date" id="check_out_date" name="check_out_date" class="mt-1 block w-full shadow-sm sm:text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                                <div id="checkOutError" class="text-red-500 text-sm mb-2 hidden"></div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Price Per Night: ${{ $room->price_per_night }}</p>
                            <p id="totalPrice" class="text-md font-medium text-gray-700 dark:text-gray-300">Total Price: $<span>{{ $room->price_per_night }}</span></p>
                        </div>
                        <button id="calculateTotalPrice" type="button" class="mt-4 inline-block bg-blue-500 dark:bg-indigo-600 hover:bg-blue-700 dark:hover:bg-indigo-700 text-white py-2 px-4 rounded">Calculate Total Price</button>
                        <button type="submit" class="mt-4 inline-block bg-blue-500 dark:bg-indigo-600 hover:bg-blue-700 dark:hover:bg-indigo-700 text-white py-2 px-4 rounded">Book Now</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('roomForm');
            const checkInDateInput = document.getElementById('check_in_date');
            const checkOutDateInput = document.getElementById('check_out_date');
            const checkInErrorDiv = document.getElementById('checkInError');
            const checkOutErrorDiv = document.getElementById('checkOutError');
            const totalPriceElement = document.getElementById('totalPrice').querySelector('span');
            const calculateButton = document.getElementById('calculateTotalPrice');
            const pricePerNight = {{ $room->price_per_night }};
            const rules = @json($rules);
            const checkInDateRules = rules.check_in_date.split('|');
            const messages = @json($messages);
            const unavailableDates = @json($room->getUnavailableDates());
            const appTimezone = "{{ config('app.timezone') }}";
            const now = new Date();
            const today = new Date(Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate())).toISOString().split('T')[0];

            let tomorrow = new Date(now);
            let dayAfterTomorrow = new Date(now);
            let checkInDateMin = null;

            tomorrow.setUTCDate(tomorrow.getUTCDate() + 1);
            tomorrow = tomorrow.toISOString().split('T')[0];

            dayAfterTomorrow.setUTCDate(dayAfterTomorrow.getUTCDate() + 2);
            dayAfterTomorrow = dayAfterTomorrow.toISOString().split('T')[0];

            function showError(element, message) {
                element.innerText = message;
                element.classList.remove('hidden');
            }

            function hideError(element) {
                element.innerText = '';
                element.classList.add('hidden');
            }

            function convertToUTCDateString(inputDate) {
                // Parse the input date string into its individual components
                const [year, month, day] = inputDate.split('-').map(Number);

                // Create a new Date object with the parsed components (using 0-based month)
                const utcDate = new Date(Date.UTC(year, month - 1, day));

                // Get the UTC date components
                const utcYear = utcDate.getUTCFullYear();
                const utcMonth = utcDate.getUTCMonth() + 1; // Add 1 because months are zero-based
                const utcDay = utcDate.getUTCDate();

                // Format the UTC date string as 'YYYY-MM-DD'
                const utcDateString = `${utcYear}-${utcMonth.toString().padStart(2, '0')}-${utcDay.toString().padStart(2, '0')}`;

                return utcDateString;
            }

            function validateDate(input, rules, messages, errorElement) {
                const value = convertToUTCDateString(input.value);
                hideError(errorElement);

                if (rules.includes('required') && !value) {
                    showError(errorElement, messages[input.id + '.required']);
                    return false;
                }

                if (rules.includes('date') && isNaN(Date.parse(value))) {
                    showError(errorElement, messages[input.id + '.date']);
                    return false;
                }

                if (rules.includes('after_or_equal:today')) {
                    if (value < today) {
                        showError(errorElement, messages[input.id + '.after_or_equal']);
                        return false;
                    }
                }

                if (rules.includes('after:today')) {
                    if (value <= today) {
                        showError(errorElement, messages[input.id + '.after']);
                        return false;
                    }
                }

                if (rules.includes('after:check_in_date')) {
                    const checkInDate = checkInDateInput.value;
                    const checkOutDate = value;
                    if (checkOutDate <= checkInDate) {
                        showError(errorElement, messages[input.id + '.after']);
                        return false;
                    }
                }

                if (unavailableDates.includes(value)) {
                    showError(errorElement, messages[input.id + '.availability']);
                    return false;
                }

                return true;
            }

            if (checkInDateRules.includes('after_or_equal:today')) {
                checkInDateMin = today;
            } else if (checkInDateRules.includes('after:today')) {
                checkInDateMin = tomorrow;
            }

            checkInDateInput.min = checkInDateMin;
            checkOutDateInput.min = dayAfterTomorrow;

            checkInDateInput.addEventListener('change', () => {
                validateDate(checkInDateInput, rules.check_in_date.split('|'), messages['check_in_date'], checkInErrorDiv);
            });

            checkOutDateInput.addEventListener('change', () => {
                validateDate(checkOutDateInput, rules.check_out_date.split('|'), messages['check_out_date'], checkOutErrorDiv);
            });

            function calculateTotalPrice() {
                if (!validateDate(checkInDateInput, rules.check_in_date.split('|'), messages['check_in_date'], checkInErrorDiv) ||
                    !validateDate(checkOutDateInput, rules.check_out_date.split('|'), messages['check_out_date'], checkOutErrorDiv)) {
                    return;
                }

                const checkInDate = new Date(convertToUTCDateString(checkInDateInput.value));
                const checkOutDate = new Date(convertToUTCDateString(checkOutDateInput.value));
                const dayDifference = (checkOutDate - checkInDate) / (1000 * 3600 * 24);

                if (dayDifference > 0) {
                    totalPriceElement.innerText = (dayDifference * pricePerNight).toFixed(2);
                }
            }

            calculateButton.addEventListener('click', calculateTotalPrice);

            form.addEventListener('submit', (event) => {
                event.preventDefault();

                // Validate check-in date
                const checkInValid = validateDate(checkInDateInput, rules.check_in_date.split('|'), messages['check_in_date'], checkInErrorDiv);

                // Validate check-out date
                const checkOutValid = validateDate(checkOutDateInput, rules.check_out_date.split('|'), messages['check_out_date'], checkOutErrorDiv);

                // Check if both check-in and check-out dates are valid
                if (checkInValid && checkOutValid) {
                    form.submit(); // Submit the form
                } else {
                    // If any validation fails, do not submit the form
                    return false;
                }
            });
        });
    </script>
</x-app-layout>