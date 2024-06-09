@props(['id' => null, 'maxWidth' => null, 'submit' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <form wire:submit="{{ $submit }}">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $title }}
            </div>

            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                {{ $content }}
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 dark:bg-gray-800 text-end">
            <x-secondary-button wire:click.prevent="{{ $cancel }}" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click.prevent="{{ $save }}" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </div>
    </form>
</x-modal>