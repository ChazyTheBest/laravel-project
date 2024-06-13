@props(['value', 'options'])

@php
    $classes = 'block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:outline-none focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-600 sm:text-sm rounded-md';
@endphp

<select {{ $attributes->merge(['class' => $classes]) }}>
    @foreach($options as $option)
        @if (is_object($option))
            <option value="{{ $option->id }}">{{ $option->name }}</option>
        @else
            <option value="{{ $option['id'] }}">{{ $option['name'] }}</option>
        @endif
    @endforeach
</select>