<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profiles') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="flex mb-5">
            <x-button wire:click.prevent="openProfileCreate" class="float-right">
                {{ __('Create Profile') }}
            </x-button>
        </div>
        <x-table wire:loading.class="opacity-75">
            <x-slot name="header">
                @if(auth()->user()->hasRole(\App\Enums\Role::STAFF))
                        <x-table.header>ID</x-table.header>
                @endif
                <x-table.header sortable wire:click.prevent="sortBy('name')"
                    :direction="$sortField === 'name' ? $sortDirection : null">{{ __('Name') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('phone')"
                    :direction="$sortField === 'phone' ? $sortDirection : null">{{ __('Phone') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('address')"
                    :direction="$sortField === 'address' ? $sortDirection : null">{{ __('Address') }}</x-table.header>
                <x-table.header>Action</x-table.header>
            </x-slot>
            <x-slot name="body">
                @forelse ($profiles as $key => $profile)
                    <x-table.row>
                        @if(auth()->user()->hasRole(\App\Enums\Role::STAFF))
                                <x-table.cell>{{ $profile->id }}</x-table.cell>
                        @endif
                        <x-table.cell>{{ $profile->name }}</x-table.cell>
                        <x-table.cell>{{ $profile->phone }}</x-table.cell>
                        <x-table.cell>{{ $profile->address }}</x-table.cell>
                        <x-table.cell>
                            <button wire:click="openProfileEdit('{{ $profile->id }}')"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</button>
                            <button wire:click="openProfileDelete('{{ $profile->id }}')"
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
        @if ($profiles->hasPages())
            <div class="p-3">
                {{ $profiles->links() }}
            </div>
        @endif
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
            {{ __('Delete Profile') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this profile?') }}
        </x-slot>

        <x-slot name="footer">
            <x-action-message class="me-3" on="destroyError">
                {{ __('Cannot delete profile because it was used to book rooms.') }}
            </x-action-message>
            <x-secondary-button wire:click.prevent="closeProfileDelete">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click.prevent="destroyProfile" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>