{{-- <x-app-layout> --}}
{{-- @extends('layouts.app')
@section('content') --}}

<div>
    <div class="container w-70 mt-4">
        @if (session()->has('message'))
            <div id="success-alert" class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="d-flex justify-content-between mt-4 flex-wrap">
            <div id="buttonsWrapper" class="mb-2">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add
                    Contact</button>
                <button type="button" class="btn btn-warning" data-toggle="modal"
                    data-target="#recycleBinModal">Recycle Bin</button>
            </div>
            <div id="formWrapper" class="mb-2">
                <div class="input-group mb-4">
                    <input type="text" class="form-control" placeholder="Search Contacts" wire:model="searchTerm">
                </div>
            </div>
        </div>
        <div class="text-center">
            <h2 class="display-5">Contacts</h2>
        </div>
        @if ($contacts->isEmpty())
            <p class="text-center">No contacts found.</p>
        @else
        <div class="table-responsive">
            <table class="table table-bordered mt-5 text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="contact-table">
                    @foreach ($contacts as $contact)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/'.$image) }}"style="width: 50px; height: 50px; border-radius: 50%;">
                                {{ $contact->name }}
                            </td>
                            <td>{{ $contact->phone }}</td>
                            <td>
                                <button wire:click="edit({{ $contact->id }})" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal">Edit</button>
                                <button wire:click="delete({{ $contact->id }})" class="btn btn-danger btn-sm">Delete</button>
                                <button wire:click="view({{ $contact->id }})" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewModal">View</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @endif
    </div>

    <!-- Create Modal -->
    <div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" role="dialog"
        aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Add Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('livewire.create')
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" role="dialog"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Update Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('livewire.update')
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div wire:ignore.self class="modal fade" id="viewModal" tabindex="-1" role="dialog"
        aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Contact Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('livewire.view')
                </div>
            </div>
        </div>
    </div>

    <!-- Recycle Bin Modal -->
    <div wire:ignore.self class="modal fade" id="recycleBinModal" tabindex="-1" role="dialog"
        aria-labelledby="recycleBinModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="recycleBinModalLabel">Recycle Bin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($deletedContacts->isEmpty())
                        <p class="text-center">No deleted contacts found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered mt-5 text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="deleted-contacts-table">
                                    @foreach ($deletedContacts as $contact)
                                        <tr>
                                            <td>{{ $contact->name }}</td>
                                            <td>{{ $contact->phone }}</td>
                                            <td>
                                                <button wire:click="restore({{ $contact->id }})"
                                                    class="btn btn-primary btn-sm">Restore</button>
                                                <button wire:click="forceDelete({{ $contact->id }})"
                                                    class="btn btn-danger btn-sm">Permanently Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('contactStore', () => {
            $('#createModal').modal('hide');
            showAlert();
        });

        Livewire.on('contactUpdate', () => {
            $('#editModal').modal('hide');
            showAlert();
        });

        Livewire.on('openEditModal', () => {
            $('#editModal').modal('show');
        });

        Livewire.on('openViewModal', () => {
            $('#viewModal').modal('show');
        });

        Livewire.on('closeModal', () => {
            $('#createModal, #editModal, #viewModal').modal('hide');
        });

        Livewire.on('openRecycleBinModal', () => {
            $('#recycleBinModal').modal('show');
        });

        // Function to show alert and hide it after 3 seconds
        function showAlert() {
            var alertEl = document.getElementById('success-alert');
            if (alertEl) {
                alertEl.style.display = 'block';
                setTimeout(function() {
                    alertEl.style.display = 'none';
                }, 3000); // 3 seconds
            }
        }

        // Immediately hide the alert after 3 seconds if it is already displayed on page load
        setTimeout(function() {
            var alertEl = document.getElementById('success-alert');
            if (alertEl) {
                alertEl.style.display = 'none';
            }
        }, 3000); // 3 seconds
    });
</script>
{{-- </x-app-layout> --}}
{{-- @endsection --}}
