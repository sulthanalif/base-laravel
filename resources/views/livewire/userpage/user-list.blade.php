<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
    @if (session()->has('message'))
        <div class="toast toast-top toast-end">
            <div class="alert alert-success">
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif
    <div class="flex justify-between items-center">
        <div class="button">
            <x-button type="button" wire:click='toggleForm'>
                Tambah User
            </x-button>
            <x-button type="button" wire:click='toggleModalRole'>
                Role
            </x-button>
            <x-button type="button" wire:click='toggleModalPermission'>
                Permission
            </x-button>
        </div>
        <div class="search">
            <select wire:model.live="perPage" id="perPage" class="border border-gray-300 rounded-md px-4 py-2">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="Cari..."
                class="border border-gray-300 rounded-md px-3 py-2">
        </div>
    </div>
    <div class="flex justify-center mt-6">
        <table class="table table-striped table-hover responsive w-full ">
            <thead>
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        wire:click="sort('name')">
                        Nama
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        wire:click="sort('email')">
                        Email
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        wire:click="sort('address')">
                        Alamat
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($datas as $key => $data)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ ($datas->currentPage() - 1) * $datas->perPage() + $key + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $data->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $data->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $data->address }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{-- {{ $data->id }} --}}
                            <x-button type="button" wire:click="toggleFormEdit('{{ $data->id }}')"><i class="fa-solid fa-pen-to-square"></i></x-button>
                            <x-danger-button type="button" wire:click="delete('{{ $data->id }}')" wire:confirm="Are you sure?"><i class="fa-solid fa-trash-can"></i></x-danger-button>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" colspan="5">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $datas->links() }}
        </div>

        {{-- modal permission --}}
        @if ($modalPermission)
            @include('livewire.userpage.modal.modal-permission')
        @endif
        {{-- modal role --}}
        @if ($modalRole)
            @include('livewire.userpage.modal.modal-role')
        @endif
        {{-- modal create --}}
        @if ($modalForm)
            @include('livewire.userpage.modal.modal-user')
        @endif




    </div>
