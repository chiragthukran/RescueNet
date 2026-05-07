<x-app-layout>
    <x-slot name="pageTitle">Report Calamity</x-slot>
    <x-slot name="pageSubtitle">Report a new disaster event</x-slot>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    <div style="max-width:800px;">
        <div class="glass-card-static" style="padding:2rem;">
            <form method="POST" action="{{ route('calamities.store') }}" id="calamity-form">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-input" placeholder="e.g. Major Earthquake in North Region" required>
                    @error('title') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label">Type</label>
                        <select name="type" id="calamity-type" class="form-select" required onchange="document.getElementById('custom-type-wrap').style.display = this.value === 'other' ? 'block' : 'none'">
                            <option value="">Select type...</option>
                            @foreach(['earthquake'=>'🌍 Earthquake','flood'=>'🌊 Flood','fire'=>'🔥 Fire','cyclone'=>'🌪️ Cyclone','tsunami'=>'🌊 Tsunami','landslide'=>'⛰️ Landslide','industrial'=>'🏭 Industrial','other'=>'📋 Other'] as $val => $label)
                                <option value="{{ $val }}" {{ old('type') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Severity</label>
                        <select name="severity" class="form-select" required>
                            <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                            <option value="medium" {{ old('severity', 'medium') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>🟠 High</option>
                            <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>🔴 Critical</option>
                        </select>
                        @error('severity') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div id="custom-type-wrap" style="margin-bottom:1.25rem; display:{{ old('type') == 'other' ? 'block' : 'none' }};">
                    <label class="form-label">Custom Type</label>
                    <input type="text" name="custom_type" value="{{ old('custom_type') }}" class="form-input" placeholder="Describe the calamity type...">
                    @error('custom_type') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="3" placeholder="Describe the situation...">{{ old('description') }}</textarea>
                    @error('description') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">📍 Location — Click on the map to set coordinates</label>
                    <div id="location-map" class="map-container" style="height:300px; margin-bottom:0.75rem;"></div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem;">
                        <div>
                            <label class="form-label">Latitude</label>
                            <input type="number" name="latitude" id="lat-input" value="{{ old('latitude', '28.6139') }}" class="form-input" step="any" required>
                        </div>
                        <div>
                            <label class="form-label">Longitude</label>
                            <input type="number" name="longitude" id="lng-input" value="{{ old('longitude', '77.2090') }}" class="form-input" step="any" required>
                        </div>
                        <div>
                            <label class="form-label">Radius (km)</label>
                            <input type="number" name="radius_km" value="{{ old('radius_km', 5) }}" class="form-input" step="0.1" min="0.1" max="500" required>
                        </div>
                    </div>
                    @error('latitude') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                    @error('longitude') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display:flex; gap:1rem;">
                    <button type="submit" class="btn btn-danger">⚠️ Report Calamity</button>
                    <a href="{{ route('calamities.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = parseFloat(document.getElementById('lat-input').value) || 28.6139;
            const lng = parseFloat(document.getElementById('lng-input').value) || 77.2090;

            const map = L.map('location-map').setView([lat, lng], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                document.getElementById('lat-input').value = pos.lat.toFixed(7);
                document.getElementById('lng-input').value = pos.lng.toFixed(7);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('lat-input').value = e.latlng.lat.toFixed(7);
                document.getElementById('lng-input').value = e.latlng.lng.toFixed(7);
            });
        });
    </script>
    @endpush
</x-app-layout>
