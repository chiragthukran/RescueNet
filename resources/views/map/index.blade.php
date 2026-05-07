<x-app-layout>
    <x-slot name="pageTitle">Live Map</x-slot>
    <x-slot name="pageSubtitle">Real-time agency and calamity tracking</x-slot>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    <div style="display:flex; gap:1rem; margin-bottom:1rem; flex-wrap:wrap; align-items:center;">
        <button onclick="toggleLayer('agencies')" id="btn-agencies" class="btn btn-primary btn-sm">🏢 Agencies</button>
        <button onclick="toggleLayer('calamities')" id="btn-calamities" class="btn btn-danger btn-sm">⚠️ Calamities</button>
        <button onclick="shareMyLocation()" class="btn btn-secondary btn-sm">📍 Share My Location</button>
        <button onclick="refreshMapData()" class="btn btn-secondary btn-sm">🔄 Refresh</button>
        <span id="last-update" style="font-size:0.7rem; color:var(--color-text-muted); margin-left:auto;">Loading map data...</span>
    </div>

    <div class="map-container" style="height:calc(100vh - 260px);">
        <div id="main-map" style="height:100%; width:100%;"></div>
    </div>

    <!-- Legend -->
    <div style="display:flex; gap:1.5rem; margin-top:1rem; font-size:0.75rem; color:var(--color-text-muted); flex-wrap:wrap;">
        <span>🔵 Agency</span>
        <span style="color:#10b981;">● Low Severity</span>
        <span style="color:#f59e0b;">● Medium</span>
        <span style="color:#f97316;">● High</span>
        <span style="color:#ef4444;">● Critical</span>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, agencyLayer, calamityLayer;
        let showAgencies = true, showCalamities = true;

        document.addEventListener('DOMContentLoaded', function() {
            map = L.map('main-map').setView([20.5937, 78.9629], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            agencyLayer = L.layerGroup().addTo(map);
            calamityLayer = L.layerGroup().addTo(map);

            refreshMapData();
            setInterval(refreshMapData, 15000);
        });

        function refreshMapData() {
            fetch('{{ route("map.data") }}')
                .then(r => r.json())
                .then(data => {
                    agencyLayer.clearLayers();
                    calamityLayer.clearLayers();

                    data.agencies.forEach(a => {
                        const marker = L.circleMarker([a.latitude, a.longitude], {
                            radius: 8, fillColor: '#d97706', color: '#b45309',
                            weight: 2, opacity: 1, fillOpacity: 0.8
                        });
                        marker.bindPopup(`
                            <div style="min-width:180px;">
                                <strong>${a.name}</strong><br>
                                ${a.organization ? '<em>' + a.organization + '</em><br>' : ''}
                                ${a.agency_type ? '🏷️ ' + a.agency_type + '<br>' : ''}
                                ${a.phone ? '📞 ' + a.phone + '<br>' : ''}
                                📦 ${a.resources_count} resources
                            </div>
                        `);
                        agencyLayer.addLayer(marker);
                    });

                    const severityColors = { low: '#10b981', medium: '#f59e0b', high: '#f97316', critical: '#ef4444' };
                    data.calamities.forEach(c => {
                        const color = severityColors[c.severity] || '#ef4444';

                        const circle = L.circle([c.latitude, c.longitude], {
                            radius: c.radius_km * 1000,
                            color: color, fillColor: color,
                            fillOpacity: 0.12, weight: 2
                        });

                        const marker = L.circleMarker([c.latitude, c.longitude], {
                            radius: 10, fillColor: color, color: '#fff',
                            weight: 2, opacity: 1, fillOpacity: 0.9
                        });

                        marker.bindPopup(`
                            <div style="min-width:180px;">
                                <strong>⚠️ ${c.title}</strong><br>
                                🏷️ ${c.display_type}<br>
                                Severity: <strong style="color:${color}">${c.severity.toUpperCase()}</strong><br>
                                Status: ${c.status}<br>
                                Radius: ${c.radius_km} km
                            </div>
                        `);

                        calamityLayer.addLayer(circle);
                        calamityLayer.addLayer(marker);
                    });

                    document.getElementById('last-update').textContent =
                        `${data.agencies.length} agencies, ${data.calamities.length} calamities · Updated ${new Date().toLocaleTimeString()}`;
                })
                .catch(() => {
                    document.getElementById('last-update').textContent = 'Failed to load map data';
                });
        }

        function toggleLayer(type) {
            if (type === 'agencies') {
                showAgencies = !showAgencies;
                showAgencies ? map.addLayer(agencyLayer) : map.removeLayer(agencyLayer);
                document.getElementById('btn-agencies').className = showAgencies ? 'btn btn-primary btn-sm' : 'btn btn-secondary btn-sm';
            } else {
                showCalamities = !showCalamities;
                showCalamities ? map.addLayer(calamityLayer) : map.removeLayer(calamityLayer);
                document.getElementById('btn-calamities').className = showCalamities ? 'btn btn-danger btn-sm' : 'btn btn-secondary btn-sm';
            }
        }

        function shareMyLocation() {
            if (!navigator.geolocation) return alert('Geolocation not supported');
            navigator.geolocation.getCurrentPosition(pos => {
                fetch('{{ route("location.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ latitude: pos.coords.latitude, longitude: pos.coords.longitude })
                })
                .then(r => r.json())
                .then(() => {
                    refreshMapData();
                    map.setView([pos.coords.latitude, pos.coords.longitude], 12);
                });
            }, () => alert('Unable to get location'));
        }
    </script>
    @endpush
</x-app-layout>
