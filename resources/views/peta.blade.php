<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peta Sekolah</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    
   
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />

    <style>
        html, body { height: 100%; margin: 0; font-family: system-ui, sans-serif; }
        header { height: 56px; box-sizing: border-box; padding: 0 16px; display: flex; align-items: center; gap: 16px; background: #1e3a8a; color: #fff; }
        header h1 { font-size: 18px; margin: 0; }
        header a { color: #bfdbfe; margin-left: auto; text-decoration: none; }
        header a:hover { text-decoration: underline; }
        #map { height: calc(100% - 56px); }
    </style>
</head>
<body>
    <header>
        <h1>Peta Sekolah</h1>
        <select id="filter-jenjang">
            <option value="">Semua jenjang</option>
            <option value="SD">SD</option>
            <option value="SMP">SMP</option>
            <option value="SMA">SMA</option>
            <option value="SMK">SMK</option>
        </select>
        
        <select id="filter-status">
            <option value="">Semua status</option>
            <option value="Negeri">Negeri</option>
            <option value="Swasta">Swasta</option>
        </select>

        <input type="text" id="cari-sekolah" placeholder="Cari nama sekolah..." class="px-2 py-1 text-black rounded text-sm bg-white">

        <span id="jumlah"></span>
        
        <!-- TAMBAHAN MODUL: Otentikasi Dashboard / Masuk -->
        @auth
            <a href="{{ route('dashboard') }}">Dashboard</a>
        @else
            <a href="{{ route('login') }}">Masuk</a>
        @endauth
    </header>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <script>
     
        const osmStandard = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });

        const topographic = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            maxZoom: 17,
            attribution: 'Map data: &copy; <a href="https://opentopomap.org">OpenStreetMap</a> contributors, SRTM | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a>'
        });

       
        const map = L.map('map', {
            center: [-3.3194, 114.5908],
            zoom: 12,
            layers: [osmStandard]
        });

     
        const baseMaps = {
            "OpenStreetMap Standard": osmStandard,
            "Topographic / Relief": topographic
        };

     
        L.control.layers(baseMaps).addTo(map);

        function esc(teks) {
            const d = document.createElement('div');
            d.textContent = teks ?? '';
            return d.innerHTML;
        }

        const markers = L.markerClusterGroup();

        const lapisan = L.geoJSON(null, {
            pointToLayer: (feature, latlng) => {
                const j = feature.properties.jenjang;
                let warnaIcon = 'blue';
                if (j === 'SD') warnaIcon = 'green';
                else if (j === 'SMP') warnaIcon = 'orange';
                else if (j === 'SMA') warnaIcon = 'red';
                else if (j === 'SMK') warnaIcon = 'violet';

                const customIcon = L.icon({
                    iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${warnaIcon}.png`,
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                return L.marker(latlng, { icon: customIcon });
            },
            onEachFeature: (fitur, layer) => {
                const p = fitur.properties;
                const [lng, lat] = fitur.geometry.coordinates;
                layer.bindPopup(`
                    <strong>${esc(p.nama)}</strong><br>
                    NPSN: ${esc(p.npsn)}<br>
                    ${esc(p.jenjang)} - ${esc(p.status)}<br>
                    ${esc(p.alamat)}, ${esc(p.kecamatan)}<br>
                    Telp: ${esc(p.telepon) || '-'}<br>
                    Kepala sekolah: ${esc(p.kepala_sekolah) || '-'}<br>
                    Akreditasi: ${esc(p.akreditasi) || '-'}<br>
                    Siswa: ${p.jumlah_siswa ?? '-'} | Guru: ${p.jumlah_guru ?? '-'}<br>
                    <small>Koordinat: ${lat}, ${lng}</small>
                `);
            }
        });

        markers.addLayer(lapisan);
        map.addLayer(markers);

        async function muatData() {
            const jenjang = document.getElementById('filter-jenjang').value;
            const status = document.getElementById('filter-status').value; 
            
            const url = `{{ route('peta.data') }}?jenjang=${jenjang}&status=${status}`;
            const respon = await fetch(url);
            const data = await respon.json();
            
            lapisan.clearLayers();
            markers.clearLayers();
            
            lapisan.addData(data);
            markers.addLayer(lapisan);

            document.getElementById('jumlah').textContent = data.features.length + ' sekolah';
            if (data.features.length > 0) {
                map.fitBounds(lapisan.getBounds().pad(0.1));
            }
        }

        document.getElementById('filter-jenjang').addEventListener('change', muatData);
        document.getElementById('filter-status').addEventListener('change', muatData); 

        document.getElementById('cari-sekolah').addEventListener('input', (e) => {
            const keyword = e.target.value.toLowerCase();
            lapisan.eachLayer((layer) => {
                const nama = layer.feature.properties.nama.toLowerCase();
                if (nama.includes(keyword) && keyword !== '') {
                    map.setView(layer.getLatLng(), 16);
                    layer.openPopup();
                }
            });
        });

        muatData();
    </script>
</body>
</html>