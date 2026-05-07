<x-app-layout>
    <x-slot name="pageTitle">Edit Resource</x-slot>
    <x-slot name="pageSubtitle">Update resource details</x-slot>

    <div style="max-width:640px;">
        <div class="glass-card-static" style="padding:2rem;">
            <form method="POST" action="{{ route('resources.update', $resource) }}">
                @csrf @method('PUT')

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Resource Name</label>
                    <input type="text" name="name" value="{{ old('name', $resource->name) }}" class="form-input" required>
                    @error('name') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        @foreach(['personnel'=>'👥 Personnel','vehicle'=>'🚗 Vehicle','equipment'=>'🔧 Equipment','medical'=>'🏥 Medical','supplies'=>'📦 Supplies'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type', $resource->type) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label">Total Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $resource->quantity) }}" class="form-input" min="1" required>
                        @error('quantity') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Available Quantity</label>
                        <input type="number" name="available_quantity" value="{{ old('available_quantity', $resource->available_quantity) }}" class="form-input" min="0" required>
                        @error('available_quantity') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach(['available'=>'✅ Available','deployed'=>'🚀 Deployed','maintenance'=>'🔧 Maintenance'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $resource->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea name="notes" class="form-textarea" rows="3">{{ old('notes', $resource->notes) }}</textarea>
                    @error('notes') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display:flex; gap:1rem;">
                    <button type="submit" class="btn btn-primary">Update Resource</button>
                    <a href="{{ route('resources.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
