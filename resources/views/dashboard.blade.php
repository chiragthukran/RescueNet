<x-app-layout>
    @section('pageTitle', 'Dashboard')

    <x-slot name="pageTitle">Dashboard</x-slot>
    <x-slot name="pageSubtitle">Welcome back, {{ auth()->user()->name }}</x-slot>

    <!-- Stats Grid -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; margin-bottom:2rem;">
        <div class="stat-card fade-in fade-in-delay-1">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                <div class="stat-icon" style="background:rgba(217,119,6,0.15); color:#d97706;">🏢</div>
            </div>
            <div class="stat-value">{{ $stats['total_agencies'] }}</div>
            <div class="stat-label">Active Agencies</div>
        </div>

        <div class="stat-card fade-in fade-in-delay-2">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                <div class="stat-icon" style="background:rgba(239,68,68,0.15); color:#ef4444;">⚠️</div>
            </div>
            <div class="stat-value">{{ $stats['active_calamities'] }}</div>
            <div class="stat-label">Active Calamities</div>
        </div>

        <div class="stat-card fade-in fade-in-delay-3">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                <div class="stat-icon" style="background:rgba(16,185,129,0.15); color:#10b981;">📦</div>
            </div>
            <div class="stat-value">{{ $stats['available_resources'] }}<span style="font-size:0.9rem; color:var(--color-text-muted);">/{{ $stats['total_resources'] }}</span></div>
            <div class="stat-label">Available Resources</div>
        </div>

        <div class="stat-card fade-in fade-in-delay-4">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                <div class="stat-icon" style="background:rgba(239,68,68,0.15); color:#ef4444;" class="{{ $stats['critical_calamities'] > 0 ? 'pulse-critical' : '' }}">🚨</div>
            </div>
            <div class="stat-value">{{ $stats['critical_calamities'] }}</div>
            <div class="stat-label">Critical Events</div>
        </div>
    </div>

    <!-- Two Column Grid -->
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">

        <!-- Recent Alerts -->
        <div class="glass-card-static" style="padding:1.5rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                <h2 style="font-size:1rem; font-weight:700; color:var(--color-text-primary);">🔔 Recent Alerts</h2>
                <a href="{{ route('alerts.index') }}" class="btn btn-secondary btn-sm">View All</a>
            </div>

            @forelse($recentAlerts as $alert)
                <div style="padding:0.85rem; border-radius:var(--radius-md); border:1px solid var(--color-border); margin-bottom:0.6rem; background:var(--color-bg-primary);">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.35rem;">
                        <span style="font-weight:600; font-size:0.85rem; color:var(--color-text-primary);">{{ $alert->title }}</span>
                        <span class="badge badge-{{ $alert->priority }}">{{ $alert->priority }}</span>
                    </div>
                    <p style="font-size:0.8rem; color:var(--color-text-muted); margin:0;">
                        {{ Str::limit($alert->message, 80) }}
                    </p>
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-top:0.4rem;">
                        <span style="font-size:0.7rem; color:var(--color-text-muted);">by {{ $alert->creator->name }} · {{ $alert->created_at->diffForHumans() }}</span>
                        @if(!$alert->acknowledged_at)
                            <form method="POST" action="{{ route('alerts.acknowledge', $alert) }}" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm" style="padding:0.25rem 0.6rem; font-size:0.7rem;">Acknowledge</button>
                            </form>
                        @else
                            <span style="font-size:0.7rem; color:var(--color-success);">✓ Acknowledged</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">🔕</div>
                    <p class="empty-text">No alerts yet</p>
                </div>
            @endforelse
        </div>

        <!-- Active Calamities -->
        <div class="glass-card-static" style="padding:1.5rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                <h2 style="font-size:1rem; font-weight:700; color:var(--color-text-primary);">⚠️ Active Calamities</h2>
                <a href="{{ route('calamities.index') }}" class="btn btn-secondary btn-sm">View All</a>
            </div>

            @forelse($activeCalamities as $calamity)
                <a href="{{ route('calamities.show', $calamity) }}" style="text-decoration:none; display:block; padding:0.85rem; border-radius:var(--radius-md); border:1px solid var(--color-border); margin-bottom:0.6rem; background:var(--color-bg-primary); transition: border-color 0.2s;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.35rem;">
                        <span style="font-weight:600; font-size:0.85rem; color:var(--color-text-primary);">{{ $calamity->title }}</span>
                        <span class="badge badge-{{ $calamity->severity }}">{{ $calamity->severity }}</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.75rem; margin-top:0.35rem;">
                        <span class="badge badge-{{ $calamity->status }}">{{ $calamity->status }}</span>
                        <span style="font-size:0.75rem; color:var(--color-text-muted);">{{ $calamity->display_type }}</span>
                        <span style="font-size:0.7rem; color:var(--color-text-muted); margin-left:auto;">{{ $calamity->created_at->diffForHumans() }}</span>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">🌤️</div>
                    <p class="empty-text">No active calamities</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="margin-top:1.5rem; display:flex; gap:1rem; flex-wrap:wrap;">
        <a href="{{ route('calamities.create') }}" class="btn btn-danger">⚠️ Report Calamity</a>
        <a href="{{ route('alerts.create') }}" class="btn btn-primary">🔔 Send Alert</a>
        <a href="{{ route('resources.create') }}" class="btn btn-secondary">📦 Add Resource</a>
        <a href="{{ route('map.index') }}" class="btn btn-secondary">🗺️ Open Map</a>
    </div>

</x-app-layout>
