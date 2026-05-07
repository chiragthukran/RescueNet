<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h2 style="font-size: 1.25rem; font-weight: 700; color: #3b2f2f; margin-bottom: 0.25rem;">Welcome Back</h2>
        <p style="font-size: 0.85rem; color: #5c4d4d; margin-bottom: 1.5rem;">Sign in to your RescueNet account</p>

        <!-- Email -->
        <div style="margin-bottom: 1.25rem;">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-input" placeholder="you@agency.com">
            @error('email') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.3rem;">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div style="margin-bottom: 1.25rem;">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-input" placeholder="••••••••">
            @error('password') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.3rem;">{{ $message }}</p> @enderror
        </div>

        <!-- Remember Me -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="remember" style="accent-color: #d97706; width: 16px; height: 16px;">
                <span style="font-size: 0.8rem; color: #5c4d4d;">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: #d97706; text-decoration: none;">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.75rem;">Sign In</button>

        <p style="text-align: center; margin-top: 1.25rem; font-size: 0.85rem; color: #5c4d4d;">
            Don't have an account? <a href="{{ route('register') }}" style="color: #d97706; text-decoration: none; font-weight: 600;">Register</a>
        </p>
    </form>
</x-guest-layout>
