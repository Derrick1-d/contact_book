<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class RecycleBin extends Component
{
    public $deletedContacts;

    public function render()
    {
        $this->deletedContacts = Contact::onlyTrashed()->where('user_id', Auth::id())->get();

        return view('livewire.recycle-bin', [
            'deletedContacts' => $this->deletedContacts,
        ])->extends('layouts.app');
    }

    public function restore($id)
    {
        $contact = Contact::withTrashed()->find($id);
        if ($contact && $contact->user_id == Auth::id()) {
            $contact->restore();
            session()->flash('message', 'Contact restored successfully.');
        }
    }

    public function forceDelete($id)
    {
        $contact = Contact::withTrashed()->find($id);
        if ($contact && $contact->user_id == Auth::id()) {
            $contact->forceDelete();
            session()->flash('message', 'Contact permanently deleted.');
        }
    }
}
