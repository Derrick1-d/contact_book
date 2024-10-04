<div>
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" wire:model="name" disabled>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" wire:model="email" disabled>
    </div>
    <div class="form-group">
        <label for="phone">Phone:</label>
        <input type="text" class="form-control" id="phone" wire:model="phone" disabled>
    </div>
    <div class="form-group">
        <label for="image">Image:</label>
        <img src="{{ asset('storage/'.$image) }}" width="50" height="50">
    </div>
</div>
