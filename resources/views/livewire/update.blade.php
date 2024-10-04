<form>
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" wire:model="name">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" wire:model="email">
        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label for="phone">Phone:</label>
        <input type="text" class="form-control" id="phone" wire:model="phone">
        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label for="image">Image:</label>
        <input type="file" class="form-control" id="image" wire:model="image">
        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <button wire:click.prevent="update()" class="btn btn-success">Update</button>
    <button wire:click.prevent="cancel()" class="btn btn-secondary">Cancel</button>
</form>
