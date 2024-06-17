@props([
    'title' => '',
    'content' => '',
    'cancel' => '',
    'save' => '',
    'btn' => '',
])

<x-modal wire:model.live="isModalOpen">
    <div class="px-6 py-4">
        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            @include($content)
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 dark:bg-gray-800 text-end">
        <x-secondary-button wire:click.prevent="{{ $cancel }}" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-secondary-button>

        <x-button class="ms-3" wire:click.prevent="{{ $save }}" wire:loading.attr="disabled">
            {{ $btn }}
        </x-button>
        <div wire:loading class="z-50 static flex fixed left-0 top-0 bottom-0 w-full bg-gray-400 bg-opacity-50">
            <img src="https://paladins-draft.com/img/circle_loading.gif" width="64" height="64" class="m-auto mt-1/4">
        </div>
    </div>
</x-modal>
