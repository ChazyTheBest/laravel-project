<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="flex mb-5">
            <x-button wire:click.prevent="openUserCreate" class="float-right">
                {{ __('Create User') }}
            </x-button>
        </div>
        <x-table wire:loading.class="opacity-75">
            <x-slot name="header">
                <x-table.header>ID</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('name')"
                    :direction="$sortField === 'name' ? $sortDirection : null">{{ __('Name') }}</x-table.header>
                <x-table.header sortable wire:click.prevent="sortBy('email')"
                    :direction="$sortField === 'email' ? $sortDirection : null">{{ __('Email') }}</x-table.header>
                <x-table.header>Action</x-table.header>
            </x-slot>
            <x-slot name="body">
                @forelse ($users as $key => $user)
                    <x-table.row>
                        <x-table.cell>{{ $user->id }}</x-table.cell>
                        <x-table.cell>{{ $user->name }}</x-table.cell>
                        <x-table.cell>{{ $user->email }}</x-table.cell>
                        <x-table.cell>
                            <button wire:click="openUserEdit('{{ $user->id }}')"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</button>
                            <button wire:click="openUserDelete('{{ $user->id }}')"
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
        @if ($users->hasPages())
            <div class="p-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    <x-modals.form wire:model.live="isUserCreateOpen">
        <x-slot name="title">
            {{ __('Create User') }}
        </x-slot>

        <x-slot name="content">
            @include('components.user.form-fields')
        </x-slot>

        <x-slot name="cancel">closeUserCreate</x-slot>
        <x-slot name="save">storeUser</x-slot>
    </x-modals.form>
    <!-- Edit Modal -->
    <x-modals.form wire:model.live="isUserEditOpen">
        <x-slot name="title">
            {{ __('Edit User') }}
        </x-slot>

        <x-slot name="content">
            @include('components.user.form-fields')
        </x-slot>

        <x-slot name="cancel">closeUserEdit</x-slot>
        <x-slot name="save">updateUser</x-slot>
    </x-modals.form>
    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="isUserDeleteOpen">
        <x-slot name="title">
            {{ __('Delete User') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this user?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click.prevent="closeUserDelete">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click.prevent="destroyUser" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>