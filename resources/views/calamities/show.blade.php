<x-app-layout>
    <x-slot name="pageTitle">{{ $calamity->title }}</x-slot>
    <x-slot name="pageSubtitle">Calamity Details & Coordination</x-slot>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    <div style="display:grid; grid-template-columns:2fr 1fr; gap:1.5rem;">
        <!-- Main Info -->
        <div>
            <div class="glass-card-static" style="padding:1.5rem; margin-bottom:1.5rem;">
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                    <span class="badge badge-{{ $calamity->severity }} {{ $calamity->severity === 'critical' ? 'pulse-critical' : '' }}" style="font-size:0.8rem; padding:0.3rem 0.8rem;">{{ strtoupper($calamity->severity) }}</span>
                    <span class="badge badge-{{ $calamity->status }}">{{ $calamity->status }}</span>
                    <span style="font-size:0.8rem; color:var(--color-text-muted); padding:0.2rem 0.5rem; background:var(--color-bg-primary); border-radius:var(--radius-sm);">{{ $calamity->display_type }}</span>
                </div>

                <p style="color:var(--color-text-secondary); font-size:0.9rem; line-height:1.6; margin-bottom:1rem;">
                    {{ $calamity->description ?? 'No description provided.' }}
                </p>

                <div style="display:flex; gap:2rem; font-size:0.8rem; color:var(--color-text-muted);">
                    <span>📍 {{ number_format($calamity->latitude, 4) }}, {{ number_format($calamity->longitude, 4) }}</span>
                    <span>📏 Radius: {{ $calamity->radius_km }} km</span>
                    <span>👤 Reported by: {{ $calamity->reporter->name }}</span>
                    <span>🕐 {{ $calamity->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>

            <!-- Map -->
            <div class="glass-card-static" style="overflow:hidden; margin-bottom:1.5rem;">
                <div id="calamity-map" style="height:350px;"></div>
            </div>

            <!-- Update Status -->
            <div class="glass-card-static" style="padding:1.5rem;">
                <h3 style="font-size:0.9rem; font-weight:700; color:var(--color-text-primary); margin-bottom:1rem;">Update Status</h3>
                <form method="POST" action="{{ route('calamities.update', $calamity) }}" style="display:flex; gap:1rem; align-items:end;">
                    @csrf @method('PUT')
                    <div style="flex:1;">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['active'=>'🔴 Active','contained'=>'🟡 Contained','resolved'=>'🟢 Resolved'] as $val => $label)
                                <option value="{{ $val }}" {{ $calamity->status == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1;">
                        <label class="form-label">Severity</label>
                        <select name="severity" class="form-select">
                            @foreach(['low'=>'🟢 Low','medium'=>'🟡 Medium','high'=>'🟠 High','critical'=>'🔴 Critical'] as $val => $label)
                                <option value="{{ $val }}" {{ $calamity->severity == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Related Alerts -->
            <div class="glass-card-static" style="padding:1.25rem; margin-bottom:1.5rem;">
                <h3 style="font-size:0.9rem; font-weight:700; color:var(--color-text-primary); margin-bottom:1rem;">🔔 Related Alerts</h3>
                @forelse($calamity->alerts->take(5) as $alert)
                    <div style="padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--color-border); margin-bottom:0.5rem; background:var(--color-bg-primary);">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="font-size:0.8rem; font-weight:600; color:var(--color-text-primary);">{{ $alert->title }}</span>
                            <span class="badge badge-{{ $alert->priority }}">{{ $alert->priority }}</span>
                        </div>
                        <p style="font-size:0.75rem; color:var(--color-text-muted); margin-top:0.25rem;">{{ Str::limit($alert->message, 60) }}</p>
                    </div>
                @empty
                    <p style="font-size:0.8rem; color:var(--color-text-muted);">No alerts for this calamity.</p>
                @endforelse
                <a href="{{ route('alerts.create') }}?calamity={{ $calamity->id }}" class="btn btn-primary btn-sm" style="margin-top:0.75rem; width:100%; justify-content:center;">+ Send Alert</a>
            </div>

            <!-- Quick Links -->
            <div class="glass-card-static" style="padding:1.25rem;">
                <h3 style="font-size:0.9rem; font-weight:700; color:var(--color-text-primary); margin-bottom:1rem;">Quick Actions</h3>
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    <a href="{{ route('map.index') }}" class="btn btn-secondary btn-sm" style="justify-content:center;">🗺️ View on Map</a>
                    <a href="{{ route('agencies.index') }}" class="btn btn-secondary btn-sm" style="justify-content:center;">🏢 View Agencies</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $calamity->latitude }};
            const lng = {{ $calamity->longitude }};
            const radius = {{ $calamity->radius_km }} * 1000;

            const map = L.map('calamity-map').setView([lat, lng], 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            const severityColors = { low: '#10b981', medium: '#f59e0b', high: '#f97316', critical: '#ef4444' };
            const color = severityColors['{{ $calamity->severity }}'] || '#ef4444';

            L.circle([lat, lng], { radius, color, fillColor: color, fillOpacity: 0.15, weight: 2 }).addTo(map);
            L.marker([lat, lng]).addTo(map).bindPopup('<strong>{{ $calamity->title }}</strong>').openPopup();
        });
    </script>
    @endpush
</x-app-layout>
