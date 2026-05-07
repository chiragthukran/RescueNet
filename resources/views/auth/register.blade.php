<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h2 style="font-size: 1.25rem; font-weight: 700; color: #3b2f2f; margin-bottom: 0.25rem;">Register Your Agency</h2>
        <p style="font-size: 0.85rem; color: #5c4d4d; margin-bottom: 1.5rem;">Join the RescueNet coordination platform</p>

        <!-- Name -->
        <div style="margin-bottom: 1rem;">
            <label for="name" class="form-label">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-input" placeholder="John Doe">
            @error('name') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.3rem;">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div style="margin-bottom: 1rem;">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="you@agency.com">
            @error('email') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.3rem;">{{ $message }}</p> @enderror
        </div>

        <!-- Organization & Type -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
            <div>
                <label for="organization" class="form-label">Organization</label>
                <input id="organization" type="text" name="organization" value="{{ old('organization') }}" class="form-input" placeholder="Agency name">
            </div>
            <div>
                <label for="agency_type" class="form-label">Agency Type</label>
                <select id="agency_type" name="agency_type" class="form-select">
                    <option value="">Select...</option>
                    <option value="government" {{ old('agency_type') == 'government' ? 'selected' : '' }}>Government</option>
                    <option value="ngo" {{ old('agency_type') == 'ngo' ? 'selected' : '' }}>NGO</option>
                    <option value="fire_rescue" {{ old('agency_type') == 'fire_rescue' ? 'selected' : '' }}>Fire & Rescue</option>
                    <option value="medical" {{ old('agency_type') == 'medical' ? 'selected' : '' }}>Medical</option>
                    <option value="coast_guard" {{ old('agency_type') == 'coast_guard' ? 'selected' : '' }}>Coast Guard</option>
                    <option value="volunteer" {{ old('agency_type') == 'volunteer' ? 'selected' : '' }}>Volunteer</option>
                    <option value="military" {{ old('agency_type') == 'military' ? 'selected' : '' }}>Military</option>
                    <option value="other" {{ old('agency_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <!-- Phone -->
        <div style="margin-bottom: 1rem;">
            <label for="phone" class="form-label">Phone (Optional)</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="+91-9876543210">
        </div>

        <!-- Password -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.5rem;">
            <div>
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" required class="form-input" placeholder="••••••••">
                @error('password') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.3rem;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="form-label">Confirm</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="form-input" placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.75rem;">Create Account</button>

        <p style="text-align: center; margin-top: 1.25rem; font-size: 0.85rem; color: #5c4d4d;">
            Already registered? <a href="{{ route('login') }}" style="color: #d97706; text-decoration: none; font-weight: 600;">Sign In</a>
        </p>
    </form>
</x-guest-layout>
