<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Incidents Visualization</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <!-- Mapbox GL CSS -->
    <link href="https://unpkg.com/mapbox-gl/dist/mapbox-gl.css" rel="stylesheet">
    <!-- Marker Cluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css">

    <style>
        #map {
            height: 600px;
            width: 100%;
        }

        .legend {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            font-size: 14px;
        }

        .legend i {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 5px;
        }

        .heatmap-legend {
            margin-top: 10px;
        }
                .container-fluid {
            padding-right: 0px !important;
            padding-left: 0px !important;
            margin-right: 0px !important;
            margin-left: 0px !important;
        }
    </style>
</head>

<body>
        <div class="container-fluid">
         <?= view('ACOMPONENTS/adminheader'); ?>
        <div class="row">
            <?= view('ACOMPONENTS/amanagesidebar'); ?>

            <div class="col-md-9">
    <h1 style="text-align: center;">Fire Incidents Map</h1>
    <div id="map"></div>
    <div id="legend" class="legend">
        <strong>Legend:</strong>
        <div>
            <i style="background: rgba(255, 0, 0, 0.7);"></i> High density of fire incidents
        </div>
        <div>
            <i style="background: rgba(255, 165, 0, 0.7);"></i> Moderate density of fire incidents
        </div>
        <div>
            <i style="background: rgba(255, 255, 0, 0.7);"></i> Low density of fire incidents
        </div>
        <div class="heatmap-legend">
            <i style="background: #3388ff; border: 1px solid black;"></i> Clustered fire incidents (click for details)
        </div>
    </div>
</div>
</div>
<?= view('hf/footer'); ?>
</div>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- Esri Leaflet -->
    <script src="https://unpkg.com/esri-leaflet@3.0.4/dist/esri-leaflet.js"></script>
    <script src="https://unpkg.com/esri-leaflet-vector@3.0.0/dist/esri-leaflet-vector.js"></script>
    <!-- Heatmap Plugin -->
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    <!-- Marker Cluster Plugin -->
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <script>
        const apiKey = "https://basemaps-api.arcgis.com/arcgis/rest/servic…aETJip3ISvXcpq_5cB296OQurtGW79gpbXuMKZPe9kx-6mGW";
        const basemapEnum = "arcgis/street"; // Replace with a valid basemap style

        // Initialize the map
        const map = L.map("map").setView([13.3839, 121.1860], 15);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Add Esri basemap
        L.esri.Vector.vectorBasemapLayer(basemapEnum, { apiKey }).addTo(map);

        // Add legend to map
        const legend = L.control({ position: "bottomright" });
        legend.onAdd = function () {
            const div = L.DomUtil.get("legend");
            return div;
        };
        legend.addTo(map);

        // Fetch data for heatmap and cluster
        fetch('<?= site_url('rescuer-report/getHeatmapData') ?>')
            .then(response => response.json())
            .then(data => {
                if (!data || data.length === 0) {
                    alert('No data available.');
                    return;
                }

                // Heatmap Data
                const heatMapData = data.map(item => [
                    parseFloat(item.latitude),
                    parseFloat(item.longitude),
                    parseFloat(item.intensity) || 0.7
                ]);

                // Add Heatmap Layer
                L.heatLayer(heatMapData, { radius: 25, blur: 15, maxZoom: 17 }).addTo(map);

                // Add Cluster Markers
                const markers = L.markerClusterGroup();
                data.forEach(item => {
                    const marker = L.marker([parseFloat(item.latitude), parseFloat(item.longitude)])
                        .bindPopup(`
                            <strong>Address:</strong> ${item.address}<br>
                            <strong>Cause:</strong> ${item.cause_of_fire}<br>
                            <strong>Damage Cost:</strong> ₱${item.property_damage_cost_estimate}<br>
                            <strong>Injuries:</strong> ${item.number_of_injuries}
                        `);
                    markers.addLayer(marker);
                });

                map.addLayer(markers);
            })
            .catch(error => {
                console.error('Error loading data:', error);
                alert('An error occurred while loading data.');
            });
    </script>
</body>

</html>
