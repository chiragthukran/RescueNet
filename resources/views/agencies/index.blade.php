<x-app-layout>
    <x-slot name="pageTitle">Agencies</x-slot>
    <x-slot name="pageSubtitle">Registered rescue agencies network</x-slot>

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:1rem;">
        @forelse($agencies as $agency)
            <a href="{{ route('agencies.show', $agency) }}" class="glass-card" style="padding:1.25rem; text-decoration:none; display:block;">
                <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.75rem;">
                    <div style="width:48px; height:48px; border-radius:var(--radius-md); background:var(--gradient-accent); display:flex; align-items:center; justify-content:center; font-size:1.25rem; font-weight:700; color:white; flex-shrink:0;">
                        {{ strtoupper(substr($agency->name, 0, 1)) }}
                    </div>
                    <div style="min-width:0;">
                        <h3 style="font-size:0.95rem; font-weight:700; color:var(--color-text-primary); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $agency->name }}</h3>
                        @if($agency->organization)
                            <p style="font-size:0.8rem; color:var(--color-text-muted); margin:0;">{{ $agency->organization }}</p>
                        @endif
                    </div>
                </div>

                <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.75rem;">
                    @if($agency->agency_type)
                        <span style="font-size:0.7rem; padding:0.2rem 0.5rem; background:rgba(217,119,6,0.1); border:1px solid rgba(217,119,6,0.2); border-radius:var(--radius-sm); color:#d97706;">{{ ucfirst($agency->agency_type) }}</span>
                    @endif
                    <span class="badge badge-active">{{ $agency->status }}</span>
                </div>

                <div style="display:flex; align-items:center; justify-content:space-between; font-size:0.75rem; color:var(--color-text-muted);">
                    <span>📦 {{ $agency->resources_count }} resources</span>
                    @if($agency->phone)
                        <span>📞 {{ $agency->phone }}</span>
                    @endif
                </div>
            </a>
        @empty
            <div class="glass-card-static" style="padding:3rem; grid-column:1/-1;">
                <div class="empty-state">
                    <div class="empty-icon">🏢</div>
                    <p class="empty-text">No agencies registered yet</p>
                </div>
            </div>
        @endforelse
    </div>

    <div style="margin-top:1.5rem;">
        {{ $agencies->links() }}
    </div>
</x-app-layout>
