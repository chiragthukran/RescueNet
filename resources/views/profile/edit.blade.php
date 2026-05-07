<x-app-layout>
    <x-slot name="pageTitle">Profile</x-slot>
    <x-slot name="pageSubtitle">Manage your account settings</x-slot>

    <div style="max-width:640px; display:flex; flex-direction:column; gap:1.5rem;">
        <div class="glass-card-static" style="padding:2rem;">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="glass-card-static" style="padding:2rem;">
            @include('profile.partials.update-password-form')
        </div>

        <div class="glass-card-static" style="padding:2rem;">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
