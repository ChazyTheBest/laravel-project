<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profiles') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="flex mb-5">
                <x-button wire:click.prevent="openProfileCreate" class="float-right">
                    {{ __('Create Profile') }}
                </x-button>
            </div>
            <x-table wire:loading.class="opacity-75">
                <x-slot name="header">
                    <x-table.header>No.</x-table.header>
                    <x-table.header sortable wire:click.prevent="sortBy('name')"
                        :direction="$sortField === 'name' ? $sortDirection : null">Name</x-table.header>
                    <x-table.header sortable wire:click.prevent="sortBy('phone')"
                        :direction="$sortField === 'phone' ? $sortDirection : null">Phone</x-table.header>
                    <x-table.header>Action</x-table.header>
                </x-slot>
                <x-slot name="body">
                    @php
                        $i = (request()->input('page', 1) - 1) * $perPage;
                    @endphp
                    @forelse ($profiles as $key => $profile)
                        <x-table.row>
                            <x-table.cell> {{ $profile->id }}</x-table.cell>
                            <x-table.cell>{{ $profile->name }}</x-table.cell>
                            <x-table.cell> {{ $profile->phone }}</x-table.cell>
                            <x-table.cell>
                                <button wire:click="openProfileEdit('{{ $profile->id }}')"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                                <button wire:click="openProfileDelete('{{ $profile->id }}')"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan=4>
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
            @if ($profiles->hasPages())
                <div class="p-3">
                    {{ $profiles->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <x-modals.form wire:model.live="isProfileCreateOpen">
        <x-slot name="title">
            {{ __('Create Profile') }}
        </x-slot>

        <x-slot name="content">
            @include('components.profile.form-fields')
        </x-slot>

        <x-slot name="cancel">closeProfileCreate</x-slot>
        <x-slot name="save">storeProfile</x-slot>
    </x-modals.form>
    <!-- Edit Modal -->
    <x-modals.form wire:model.live="isProfileEditOpen">
        <x-slot name="title">
            {{ __('Edit Profile') }}
        </x-slot>

        <x-slot name="content">
            @include('components.profile.form-fields')
        </x-slot>

        <x-slot name="cancel">closeProfileEdit</x-slot>
        <x-slot name="save">updateProfile</x-slot>
    </x-modals.form>
    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="isProfileDeleteOpen">
        <x-slot name="title">
            {{ __('Delete Record') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this record?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click.prevent="closeProfileDelete">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click.prevent="destroyProfile" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>