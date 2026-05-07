<x-app-layout>
    <x-slot name="pageTitle">Alerts</x-slot>
    <x-slot name="pageSubtitle">Emergency alerts and notifications</x-slot>

    <div style="display:flex; justify-content:flex-end; margin-bottom:1.5rem;">
        <a href="{{ route('alerts.create') }}" class="btn btn-primary">🔔 Send New Alert</a>
    </div>

    <div class="glass-card-static" style="overflow:hidden;">
        @if($alerts->count())
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Calamity</th>
                        <th>From</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th>Time</th>
                        <th style="text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alerts as $alert)
                        <tr>
                            <td>
                                <span style="font-weight:600;">{{ $alert->title }}</span>
                                <br><span style="font-size:0.75rem; color:var(--color-text-muted);">{{ Str::limit($alert->message, 60) }}</span>
                            </td>
                            <td><span class="badge badge-{{ $alert->priority }} {{ $alert->priority === 'critical' ? 'pulse-critical' : '' }}">{{ $alert->priority }}</span></td>
                            <td style="font-size:0.8rem;">{{ $alert->calamity?->title ?? '—' }}</td>
                            <td style="font-size:0.8rem;">{{ $alert->creator->name }}</td>
                            <td>
                                @if($alert->is_broadcast)
                                    <span class="badge badge-active">📢 Broadcast</span>
                                @else
                                    <span style="font-size:0.8rem;">{{ $alert->targetUser?->name ?? '—' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($alert->acknowledged_at)
                                    <span style="color:var(--color-success); font-size:0.8rem;">✓ Ack'd</span>
                                @else
                                    <span style="color:var(--color-warning); font-size:0.8rem;">⏳ Pending</span>
                                @endif
                            </td>
                            <td style="font-size:0.75rem; color:var(--color-text-muted);">{{ $alert->created_at->diffForHumans() }}</td>
                            <td style="text-align:right;">
                                @if(!$alert->acknowledged_at && ($alert->target_user_id == auth()->id() || $alert->is_broadcast))
                                    <form method="POST" action="{{ route('alerts.acknowledge', $alert) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Acknowledge</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-icon">🔕</div>
                <p class="empty-text">No alerts yet</p>
            </div>
        @endif
    </div>

    <div style="margin-top:1.5rem;">
        {{ $alerts->links() }}
    </div>
</x-app-layout>
