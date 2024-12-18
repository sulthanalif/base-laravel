<div class="relative z-10" aria-labelledby="modal-xc" role="dialog">
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" ></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold text-gray-900" id="modal-xc">Permission</h3>
                            <div class="row">
                                <div class="flex w-full justify-center items-center">
                                    <table class="table table-striped table-hover responsive">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($permissions as $key => $permission)
                                                <tr>
                                                    <td>{{ ($permissions->currentPage() - 1) * $permissions->perPage() + $key + 1 }}</td>
                                                    <td>{{ $permission->name }}</td>
                                                    <td>
                                                        <x-button wire:click=''>Assign</x-button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="3">Tidak ada data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    {{ $permissions->links() }}
                                </div>
                            </div>
                            <div class="flex justify-content-center items-center gap-3 mt-3">
                                <div class="w-full">
                                    <label for="permission_name" class="block text-sm font-medium leading-6 text-gray-900">Nama Permission</label>
                                    <input type="text" wire:model.live="permission_name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" wire:click="saveEditPermission" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Save</button>
                    <button type="button" wire:click="toggleModalPermission" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
