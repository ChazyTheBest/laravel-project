<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="flex mb-5">
            <x-button wire:click.prevent="openCreateModal" class="float-right">
                {{ __('Create Room') }}
            </x-button>
        </div>
        <x-table wire:loading.class="opacity-75">
            <x-slot name="header">
                <x-table.header>ID</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('number')"
                                :direction="$sortField === 'number' ? $sortDirection : null">{{ __('Room #') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('name')"
                                :direction="$sortField === 'name' ? $sortDirection : null">{{ __('Room Name') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('capacity')"
                                :direction="$sortField === 'capacity' ? $sortDirection : null">{{ __('Capacity') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('price_per_night')"
                                :direction="$sortField === 'price_per_night' ? $sortDirection : null">{{ __('Price Per Night') }}</x-table.header>
                <x-table.header>Action</x-table.header>
            </x-slot>
            <x-slot name="body">
                @forelse ($rooms as $key => $room)
                    <x-table.row>
                        <x-table.cell>{{ $room->id }}</x-table.cell>
                        <x-table.cell>{{ $room->number }}</x-table.cell>
                        <x-table.cell>{{ $room->name }}</x-table.cell>
                        <x-table.cell>{{ $room->capacity }}</x-table.cell>
                        <x-table.cell>{{ $room->price_per_night }}</x-table.cell>
                        <x-table.cell>
                            <button wire:click="openEditModal('{{ $room->id }}')"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</button>
                            <button wire:click="openDeleteModal('{{ $room->id }}')"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">{{ __('Delete') }}</button>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan=6>
                            <div class="flex justify-center items-center">
                                <span class="font-medium py-8 text-gray-400 text-xl">
                                    No data found...
                                </span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
        @if ($rooms->hasPages())
            <div class="p-3">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>

    @if ($isModalOpen)

            @if ($modalType === 'delete')
                <!-- Confirmation Delete Modal -->
                <x-confirmation-modal
                    wire:model.live="isModalOpen"
                    :title="$modalTitle"
                    :content="$modalContent"
                    :cancel="$modalCancelMethod"
                    :save="$modalSaveMethod"
                    :btn="$modalBtnName"
                />
            @else
                <!-- Create/Edit Modal -->
                <x-modals.form
                    wire:model.live="isModalOpen"
                    :title="$modalTitle"
                    :content="$modalContent"
                    :cancel="$modalCancelMethod"
                    :save="$modalSaveMethod"
                    :btn="$modalBtnName"
                />
            @endif

    @endif
</div>
