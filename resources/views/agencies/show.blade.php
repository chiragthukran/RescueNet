<x-app-layout>
    <x-slot name="pageTitle">{{ $user->name }}</x-slot>
    <x-slot name="pageSubtitle">Agency Profile</x-slot>

    <div style="display:grid; grid-template-columns:1fr 2fr; gap:1.5rem;">
        <!-- Profile Card -->
        <div class="glass-card-static" style="padding:1.5rem;">
            <div style="text-align:center; margin-bottom:1.25rem;">
                <div style="width:80px; height:80px; border-radius:50%; background:var(--gradient-accent); display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:700; color:white; margin:0 auto 1rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 style="font-size:1.15rem; font-weight:700; color:var(--color-text-primary);">{{ $user->name }}</h2>
                @if($user->organization)
                    <p style="font-size:0.85rem; color:var(--color-text-muted);">{{ $user->organization }}</p>
                @endif
            </div>

            <div style="border-top:1px solid var(--color-border); padding-top:1rem;">
                @if($user->agency_type)
                <div style="display:flex; justify-content:space-between; margin-bottom:0.6rem;">
                    <span style="font-size:0.8rem; color:var(--color-text-muted);">Type</span>
                    <span style="font-size:0.8rem; color:var(--color-text-primary); font-weight:500;">{{ ucfirst($user->agency_type) }}</span>
                </div>
                @endif
                <div style="display:flex; justify-content:space-between; margin-bottom:0.6rem;">
                    <span style="font-size:0.8rem; color:var(--color-text-muted);">Email</span>
                    <span style="font-size:0.8rem; color:var(--color-text-primary);">{{ $user->email }}</span>
                </div>
                @if($user->phone)
                <div style="display:flex; justify-content:space-between; margin-bottom:0.6rem;">
                    <span style="font-size:0.8rem; color:var(--color-text-muted);">Phone</span>
                    <span style="font-size:0.8rem; color:var(--color-text-primary);">{{ $user->phone }}</span>
                </div>
                @endif
                <div style="display:flex; justify-content:space-between; margin-bottom:0.6rem;">
                    <span style="font-size:0.8rem; color:var(--color-text-muted);">Status</span>
                    <span class="badge badge-{{ $user->status }}">{{ $user->status }}</span>
                </div>
            </div>

            @if($user->description)
                <div style="border-top:1px solid var(--color-border); padding-top:1rem; margin-top:0.5rem;">
                    <p style="font-size:0.8rem; color:var(--color-text-secondary); line-height:1.6;">{{ $user->description }}</p>
                </div>
            @endif

            @if($user->id !== auth()->id())
                <a href="{{ route('messages.show', $user) }}" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:1rem;">💬 Send Message</a>
            @endif
        </div>

        <!-- Resources -->
        <div class="glass-card-static" style="padding:1.5rem;">
            <h3 style="font-size:1rem; font-weight:700; color:var(--color-text-primary); margin-bottom:1rem;">📦 Resources ({{ $user->resources->count() }})</h3>

            @if($user->resources->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Available</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->resources as $resource)
                            <tr>
                                <td>{{ $resource->name }}</td>
                                <td><span class="badge badge-active">{{ ucfirst($resource->type) }}</span></td>
                                <td>{{ $resource->available_quantity }}/{{ $resource->quantity }}</td>
                                <td><span class="badge badge-{{ $resource->status }}">{{ $resource->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <p class="empty-text">No resources registered</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
