<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="RescueNet — Centralized rescue agency coordination platform for disaster management">

        <title>{{ config('app.name', 'RescueNet') }} — {{ $pageTitle ?? 'Dashboard' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body style="font-family: 'Inter', sans-serif;">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="toast" id="flash-toast">{{ session('success') }}</div>
        @endif

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">🛡️</div>
                <span class="brand-text">RescueNet</span>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-section">Main</div>

                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="link-icon">📊</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('map.index') }}" class="sidebar-link {{ request()->routeIs('map.*') ? 'active' : '' }}">
                    <span class="link-icon">🗺️</span>
                    <span>Live Map</span>
                </a>

                <div class="sidebar-section">Operations</div>

                <a href="{{ route('calamities.index') }}" class="sidebar-link {{ request()->routeIs('calamities.*') ? 'active' : '' }}">
                    <span class="link-icon">⚠️</span>
                    <span>Calamities</span>
                </a>

                <a href="{{ route('alerts.index') }}" class="sidebar-link {{ request()->routeIs('alerts.*') ? 'active' : '' }}">
                    <span class="link-icon">🔔</span>
                    <span>Alerts</span>
                    @php
                        $unacknowledgedAlerts = \App\Models\Alert::where(function($q) {
                            $q->where('target_user_id', auth()->id())->orWhere('is_broadcast', true);
                        })->whereNull('acknowledged_at')->count();
                    @endphp
                    @if($unacknowledgedAlerts > 0)
                        <span class="link-badge">{{ $unacknowledgedAlerts }}</span>
                    @endif
                </a>

                <a href="{{ route('resources.index') }}" class="sidebar-link {{ request()->routeIs('resources.*') ? 'active' : '' }}">
                    <span class="link-icon">📦</span>
                    <span>Resources</span>
                </a>

                <div class="sidebar-section">Network</div>

                <a href="{{ route('agencies.index') }}" class="sidebar-link {{ request()->routeIs('agencies.*') ? 'active' : '' }}">
                    <span class="link-icon">🏢</span>
                    <span>Agencies</span>
                </a>

                <a href="{{ route('messages.index') }}" class="sidebar-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <span class="link-icon">💬</span>
                    <span>Messages</span>
                    @php
                        $unreadMsgs = \App\Models\Message::where('receiver_id', auth()->id())->whereNull('read_at')->count();
                    @endphp
                    @if($unreadMsgs > 0)
                        <span class="link-badge">{{ $unreadMsgs }}</span>
                    @endif
                </a>

                <div class="sidebar-section">Account</div>

                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <span class="link-icon">👤</span>
                    <span>Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link" style="width:100%; border:none; background:none; cursor:pointer; text-align:left;">
                        <span class="link-icon">🚪</span>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <div class="topbar">
                <div style="display:flex; align-items:center; gap:1rem;">
                    <button id="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')" style="display:none; background:none; border:none; color:var(--color-text-primary); font-size:1.5rem; cursor:pointer;">☰</button>
                    <div>
                        <h1 style="font-size:1.1rem; font-weight:700; color:var(--color-text-primary); margin:0;">{{ $pageTitle ?? 'Dashboard' }}</h1>
                        @isset($pageSubtitle)
                            <p style="font-size:0.75rem; color:var(--color-text-muted); margin:0;">{{ $pageSubtitle }}</p>
                        @endisset
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:1rem;">
                    <span style="font-size:0.8rem; color:var(--color-text-secondary);">{{ auth()->user()->organization ?? auth()->user()->name }}</span>
                    <div style="width:32px; height:32px; border-radius:50%; background:var(--gradient-accent); display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:700;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="page-content">
                {{ $slot }}
            </div>
        </div>

        <script>
            // Show mobile hamburger on small screens
            if (window.innerWidth <= 1024) {
                document.getElementById('sidebar-toggle').style.display = 'block';
            }
            window.addEventListener('resize', () => {
                document.getElementById('sidebar-toggle').style.display = window.innerWidth <= 1024 ? 'block' : 'none';
            });
            // Auto-hide toast
            const toast = document.getElementById('flash-toast');
            if (toast) setTimeout(() => toast.remove(), 3500);
        </script>

        @stack('scripts')
    </body>
</html>
