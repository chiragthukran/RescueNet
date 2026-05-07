<x-app-layout>
    <x-slot name="pageTitle">Add Resource</x-slot>
    <x-slot name="pageSubtitle">Register a new resource for your agency</x-slot>

    <div style="max-width:640px;">
        <div class="glass-card-static" style="padding:2rem;">
            <form method="POST" action="{{ route('resources.store') }}">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Resource Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="e.g. Emergency Medical Kit" required>
                    @error('name') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="">Select type...</option>
                        <option value="personnel" {{ old('type') == 'personnel' ? 'selected' : '' }}>👥 Personnel</option>
                        <option value="vehicle" {{ old('type') == 'vehicle' ? 'selected' : '' }}>🚗 Vehicle</option>
                        <option value="equipment" {{ old('type') == 'equipment' ? 'selected' : '' }}>🔧 Equipment</option>
                        <option value="medical" {{ old('type') == 'medical' ? 'selected' : '' }}>🏥 Medical</option>
                        <option value="supplies" {{ old('type') == 'supplies' ? 'selected' : '' }}>📦 Supplies</option>
                    </select>
                    @error('type') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label">Total Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" class="form-input" min="1" required>
                        @error('quantity') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Available Quantity</label>
                        <input type="number" name="available_quantity" value="{{ old('available_quantity', 1) }}" class="form-input" min="0" required>
                        @error('available_quantity') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>✅ Available</option>
                        <option value="deployed" {{ old('status') == 'deployed' ? 'selected' : '' }}>🚀 Deployed</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                    </select>
                    @error('status') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea name="notes" class="form-textarea" rows="3" placeholder="Any additional details...">{{ old('notes') }}</textarea>
                    @error('notes') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display:flex; gap:1rem;">
                    <button type="submit" class="btn btn-primary">Add Resource</button>
                    <a href="{{ route('resources.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
