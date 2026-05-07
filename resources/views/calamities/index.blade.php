<x-app-layout>
    <x-slot name="pageTitle">Calamities</x-slot>
    <x-slot name="pageSubtitle">Track and manage disaster events</x-slot>

    <div style="display:flex; justify-content:flex-end; margin-bottom:1.5rem;">
        <a href="{{ route('calamities.create') }}" class="btn btn-danger">⚠️ Report New Calamity</a>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap:1rem;">
        @forelse($calamities as $calamity)
            <a href="{{ route('calamities.show', $calamity) }}" class="glass-card" style="padding:1.25rem; text-decoration:none; display:block;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                    <span class="badge badge-{{ $calamity->severity }} {{ $calamity->severity === 'critical' ? 'pulse-critical' : '' }}">{{ $calamity->severity }}</span>
                    <span class="badge badge-{{ $calamity->status }}">{{ $calamity->status }}</span>
                </div>
                <h3 style="font-size:1rem; font-weight:700; color:var(--color-text-primary); margin-bottom:0.4rem;">{{ $calamity->title }}</h3>
                <p style="font-size:0.8rem; color:var(--color-text-muted); margin-bottom:0.6rem;">{{ Str::limit($calamity->description, 100) }}</p>
                <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                    <span style="font-size:0.75rem; padding:0.2rem 0.5rem; background:var(--color-bg-primary); border-radius:var(--radius-sm); color:var(--color-text-secondary);">
                        {{ $calamity->display_type }}
                    </span>
                    <span style="font-size:0.7rem; color:var(--color-text-muted);">📍 {{ number_format($calamity->latitude, 4) }}, {{ number_format($calamity->longitude, 4) }}</span>
                    <span style="font-size:0.7rem; color:var(--color-text-muted); margin-left:auto;">{{ $calamity->created_at->diffForHumans() }}</span>
                </div>
            </a>
        @empty
            <div class="glass-card-static" style="padding:3rem; grid-column:1/-1;">
                <div class="empty-state">
                    <div class="empty-icon">🌤️</div>
                    <p class="empty-text">No calamities reported</p>
                    <a href="{{ route('calamities.create') }}" class="btn btn-danger" style="margin-top:1rem;">Report First Calamity</a>
                </div>
            </div>
        @endforelse
    </div>

    <div style="margin-top:1.5rem;">
        {{ $calamities->links() }}
    </div>
</x-app-layout>
