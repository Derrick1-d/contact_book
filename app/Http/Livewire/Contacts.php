<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class Contacts extends Component
{
    use WithFileUploads;

    public $contacts, $name, $email, $phone, $image, $contact_id, $searchTerm;
    public $deletedContacts;
    public $updateMode = false;

    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        $this->contacts = Contact::where('user_id', Auth::id())
                                ->where(function($query) use ($searchTerm) {
                                    $query->where('name', 'LIKE', $searchTerm)
                                          ->orWhere('phone', 'LIKE', $searchTerm);
                                })
                                ->orderBy('name')
                                ->get();

        $this->deletedContacts = Contact::onlyTrashed()->where('user_id', Auth::id())->get();

        return view('livewire.contacts', [
            'contacts' => $this->contacts,
            'deletedContacts' => $this->deletedContacts,
        ]);
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->image = '';
        $this->contact_id = null;
        $this->updateMode = false;
    }

    public function store()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'image' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $validatedData['user_id'] = Auth::id();

        if ($this->image) {
            $imageName = $this->image->store('images', 'public');
            $validatedData['image'] = $imageName;
        }

        Contact::create($validatedData);

        session()->flash('message', 'Contact Created Successfully.');

        $this->resetInputFields();
        $this->emit('contactStore'); // Trigger the close modal event
    }

    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        $this->contact_id = $id;
        $this->name = $contact->name;
        $this->email = $contact->email;
        $this->phone = $contact->phone;
        $this->image = $contact->image;

        $this->updateMode = true;
        $this->emit('openEditModal'); // Trigger the open edit modal event
    }

    public function update()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'image' => 'nullable|image|max:1024', // 1MB Max
        ]);

        if ($this->image && !is_string($this->image)) {
            // Delete the old image if it exists
            $contact = Contact::find($this->contact_id);
            if ($contact->image) {
                \Storage::disk('public')->delete($contact->image);
            }

            // Store the new image
            $imageName = $this->image->store('images', 'public');
            $validatedData['image'] = $imageName;
        }

        if ($this->contact_id) {
            $contact = Contact::find($this->contact_id);
            $contact->update($validatedData);

            session()->flash('message', 'Contact Updated Successfully.');

            $this->resetInputFields();
            $this->updateMode = false;
            $this->emit('contactUpdate'); // Trigger the close modal event
        }
    }

    public function delete($id)
    {
        Contact::find($id)->delete();
        session()->flash('message', 'Contact Deleted Successfully.');
    }

    public function restore($id)
    {
        $contact = Contact::withTrashed()->find($id);
        $contact->restore();

        session()->flash('message', 'Contact Restored Successfully.');
    }

    public function forceDelete($id)
    {
        $contact = Contact::withTrashed()->find($id);

        // Delete the image from storage
        if ($contact->image) {
            \Storage::disk('public')->delete($contact->image);
        }

        $contact->forceDelete();

        session()->flash('message', 'Contact Permanently Deleted.');
    }

    public function view($id)
    {
        $contact = Contact::findOrFail($id);
        $this->contact_id = $id;
        $this->name = $contact->name;
        $this->email = $contact->email;
        $this->phone = $contact->phone;
        $this->image = $contact->image;
        $this->emit('openViewModal'); // Trigger the open view modal event
    }

    public function cancel()
    {
        $this->resetInputFields();
        $this->updateMode = false;
        $this->emit('closeModal'); // Trigger the close modal event
    }
}
