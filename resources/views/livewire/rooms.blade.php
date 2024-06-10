<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rooms') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="flex mb-5">
                <x-button wire:click.prevent="openRoomCreate" class="float-right">
                    {{ __('Create Room') }}
                </x-button>
            </div>
            <x-table wire:loading.class="opacity-75">
                <x-slot name="header">
                    <x-table.header>ID</x-table.header>
                    <x-table.header sortable wire:click.prevent="sortBy('name')"
                        :direction="$sortField === 'name' ? $sortDirection : null">{{ __('Name') }}</x-table.header>
                    <x-table.header sortable wire:click.prevent="sortBy('phone')"
                        :direction="$sortField === 'phone' ? $sortDirection : null">{{ __('Phone') }}</x-table.header>
                    <x-table.header sortable wire:click.prevent="sortBy('address')"
                        :direction="$sortField === 'address' ? $sortDirection : null">{{ __('Address') }}</x-table.header>
                    <x-table.header>Action</x-table.header>
                </x-slot>
                <x-slot name="body">
                    @forelse ($rooms as $key => $room)
                        <x-table.row>
                            <x-table.cell>{{ $room->id }}</x-table.cell>
                            <x-table.cell>{{ $room->name }}</x-table.cell>
                            <x-table.cell>{{ $room->phone }}</x-table.cell>
                            <x-table.cell>{{ $room->address }}</x-table.cell>
                            <x-table.cell>
                                <button wire:click="openRoomEdit('{{ $room->id }}')"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</button>
                                <button wire:click="openRoomDelete('{{ $room->id }}')"
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
    </div>

    <!-- Create Modal -->
    <x-modals.form wire:model.live="isRoomCreateOpen">
        <x-slot name="title">
            {{ __('Create Room') }}
        </x-slot>

        <x-slot name="content">
            @include('components.room.form-fields')
        </x-slot>

        <x-slot name="cancel">closeRoomCreate</x-slot>
        <x-slot name="save">storeRoom</x-slot>
    </x-modals.form>
    <!-- Edit Modal -->
    <x-modals.form wire:model.live="isRoomEditOpen">
        <x-slot name="title">
            {{ __('Edit Room') }}
        </x-slot>

        <x-slot name="content">
            @include('components.room.form-fields')
        </x-slot>

        <x-slot name="cancel">closeRoomEdit</x-slot>
        <x-slot name="save">updateRoom</x-slot>
    </x-modals.form>
    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="isRoomDeleteOpen">
        <x-slot name="title">
            {{ __('Delete Room') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this room?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click.prevent="closeRoomDelete">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click.prevent="destroyRoom" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>