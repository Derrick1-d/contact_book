@extends('layouts.app')

@section('content')
<div class="container mt-4">
    @if (session()->has('message'))
        <div id="success-alert" class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <h2>Recycle Bin</h2>
    <div class="table-responsive">
        <table class="table mt-5">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="deleted-contacts-table">
                @foreach ($deletedContacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>
                            <button wire:click="restore({{ $contact->id }})" class="btn btn-primary btn-sm">Restore</button>
                            <button wire:click="forceDelete({{ $contact->id }})" class="btn btn-danger btn-sm">Permanently Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
