<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <title>BFP Geolocation</title>

    <!-- Load Leaflet from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@3.0.12/dist/esri-leaflet.js"></script>
    <script src="https://unpkg.com/esri-leaflet-vector@4.2.3/dist/esri-leaflet-vector.js"></script>

    <!-- Load ArcGIS REST JS from CDN -->
    <script src="https://unpkg.com/@esri/arcgis-rest-request@4.0.0/dist/bundled/request.umd.js"></script>
    <script src="https://unpkg.com/@esri/arcgis-rest-routing@4.0.0/dist/bundled/routing.umd.js"></script>

    <!-- Load Esri Leaflet Routing from CDN -->
    <script src="https://unpkg.com/esri-leaflet-routing@3.1.1/dist/esri-leaflet-routing.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            /* Use a font similar to Waze.com */
            background-image: linear-gradient(to bottom right, black, red);
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 20px;
            color: #323232;
            width: 100%;
            height: 100%;
            height: calc(100% - 50px);
            /* Adjust height for direction panel */
        }

        #directions {
            position: absolute;
            z-index: 1000;
            width: 30%;
            max-height: 50%;
            right: 20px;
            top: 20px;
            overflow-y: auto;
            background: white;
            font-family: Arial, Helvetica, Verdana;
            line-height: 2.25;
            font-size: 20px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            #directions {
                width: 90%;
                /* Adjust width for smaller screens */
                max-height: 40%;
                top: unset;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
            }
        }

        .popup-content {
            background-color: #fff;
            /* White background */
            border-radius: 5px;
            padding: 10px;
            font-family: 'Arial', sans-serif;
            /* Use a font similar to Waze.com */
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Light shadow */
        }

        #directions {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 50%;
            bottom: 0;
            background: white;
            font-family: Arial, Helvetica, Verdana;
            line-height: 1.5;
            font-size: 16px;
            padding: 10px;
            overflow-y: auto;
            display: none;
            /* Initially hidden */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .map-card {
            width: 90%;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding: 20px;
            box-sizing: border-box;
            position: relative;
        }

        .bfp-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            /* Waze.com header color */
            padding: 20px 0;
            background-color: #f8f9fa;
            /* Waze.com header background color */
            margin: 0;
        }

        #map-container {
            width: 100%;
            height: 50vh;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #007bff;
            /* Waze.com button color */
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-family: 'Arial', sans-serif;
            /* Use a font similar to Waze.com */
        }

        .btn-back:hover {
            background-color: #0056b3;
            /* Darker shade for hover effect */
        }

        .bfp-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            padding: 20px 0;
            background-color: #f8f9fa;
            margin: 0;
        }


        .show-steps {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Arial', sans-serif;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            #directions {
                font-size: 14px;
            }
        }

        @media (min-width: 768px) {
            #directions {
                font-size: 35px;
                width: 30%;
                max-height: 40%;
            }

            #map-container {
                width: 100%;
                height: 65vh;
            }

            #map {
                width: 100%;
                height: 80%;
            }

            .hydrant-suggestion {
                background-color: #fff;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .hydrant-suggestion h4 {
                margin: 0;
                color: #333;
            }

            .hydrant-suggestion p {
                margin: 5px 0;
                color: #666;
            }

            .navigate-btn {
                background-color: #007bff;
                color: #fff;
                border: none;
                padding: 8px 16px;
                border-radius: 5px;
                cursor: pointer;
                font-family: 'Arial', sans-serif;
                margin-top: 10px;
            }

            .navigate-btn:hover {
                background-color: #0056b3;
            }
    </style>

</head>

<body>


    <div class="map-card">
        <div class="bfp-header">
            <button onclick="history.go(-1);" class="btn-back">Back</button>
            <div id="philippineTime" class="philippine-time"></div>
        </div>

        <div id="map-container">
            <div id="map"></div>
        </div>
        <div class="bfp-header">
            <label for="hydrant-suggestions">Suggested Nearby Fire Hydrants: </label>
            <div id="hydrant-suggestions" name="hydrant-suggestions" class="hydrant-suggestions"></div>
            <div id="directions"> Click on the map to create a start and end for the route.</div>
        </div>
    </div>

    <script>
        function getCurrentTimeInPhilippines() {
            const currentTime = new Date();
            const utcOffset = 0; // GMT+8:00 for Philippines
            const philippinesTime = new Date(currentTime.getTime() + (utcOffset * 3600000)); // Adding offset in milliseconds

            // Format date components
            const day = philippinesTime.getDate();
            const month = philippinesTime.toLocaleString('default', {
                month: 'long'
            });
            const year = philippinesTime.getFullYear();
            const time = philippinesTime.toLocaleTimeString();

            return `${month} ${day}, ${year} ${time}`;
        }

        // Function to update time display every second
        function updateTime() {
            document.getElementById("philippineTime").textContent = "Philippine Standard Time: " + getCurrentTimeInPhilippines();
            setTimeout(updateTime, 1000); // Update time every second
        }
        updateTime();


        const apiKey = "AAPKb07ff7b9da8148cd89a46acc88c3c668OJ1KYSZifeA8-33Ign-Rw9GTSTMh1yjCUysmmuS7xd1_ydOreuns29W-y8JC5gBs";

        const basemapEnum = "arcgis/navigation";

        const map = L.map("map", {
            zoom: 18
        })

        map.setView([13.3839, 121.1860], 14); // Calapan City location

        L.esri.Vector.vectorBasemapLayer(basemapEnum, {
            apiKey: apiKey
        }, 'Streets').addTo(map);
        const directions = document.createElement("div");
        directions.id = "directions";
        directions.innerHTML = "Click on the map to create a start and end for the route.";
        document.body.appendChild(directions);

        const startLayerGroup = L.layerGroup().addTo(map);
        const endLayerGroup = L.layerGroup().addTo(map);
        const routeLines = L.layerGroup().addTo(map);

        // Define fire hydrant
        const fireHydrants = [{
                name: "Barangay Bayanan 1, Calapan City, Oriental Mindoro (beside Calapan Waterworks Corp. Compound)",
                lat: 13.370076,
                lng: 121.167853,
                color: "lightgreen"
            },
            {
                name: "Cor. JP Rizal, Barangay Lalud, Calapan City, Oriental Mindoro (Near LGC)",
                lat: 13.400788,
                lng: 121.171269,
                color: "lightgreen"
            },
            {
                name: "Ubas St., Barangay Lalud, Calapan City, Oriental Mindoro (near Barangay Hall)",
                lat: 13.399337,
                lng: 121.173764,
                color: "lightgreen"
            },
            {
                name: "Barangay Camilmil, Calapan City, Oriental Mindoro ( near elementary school)",
                lat: 13.404487,
                lng: 121.178001,
                color: "lightgreen"
            }
        ];

        // Define fire hydrant icon
        const hydrantIcon = L.icon({
            iconUrl: 'https://img.icons8.com/cotton/64/000000/fire-hydrant--v1.png',
            iconSize: [60, 60],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });


        fireHydrants.forEach(hydrant => {
            const marker = L.marker([hydrant.lat, hydrant.lng], {
                icon: hydrantIcon
            }).addTo(map);

            marker.bindPopup(`
            <div>
                <p><strong>${hydrant.name}</strong></p>
                <button onclick="navigateToHydrant(${hydrant.lat}, ${hydrant.lng})">Go now</button>
                <button onclick="cancelRoute()">Cancel Route</button>
            </div>
            `);
        });

        

        /* Define a custom icon for users in need */
        const userIcon = L.icon({
            iconUrl: 'https://img.icons8.com/flat-round/64/000000/fire-element.png',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        async function getRecentReports() {
            try {
                const response = await fetch('http://localhost:8080/reports-recent');
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (error) {
                    console.error('Error parsing JSON:', error, 'Response Text:', text);
                    return;
                }

                if (response.ok && Array.isArray(data)) {
                    data.forEach(report => {
                        if (report.location && report.location.coordinates && report.location.coordinates.length === 2) {
                            const [longitude, latitude] = report.location.coordinates;
                            const marker = L.marker([latitude, longitude]).addTo(map);
                            marker.bindPopup(`
                                <div class="popup-content">
                                    <h3>${report.incidentType}</h3>
                                    <p>${report.date}</p>
                                    <p>${report.details}</p>
                                </div>
                            `);
                        } else {
                            console.warn('Invalid report location:', report.location);
                        }
                    });
                } else {
                    console.error('Failed to fetch recent reports:', response.statusText);
                }
            } catch (error) {
                console.error('Error fetching recent reports:', error);
            }
        }

        getRecentReports();
        async function fetchRecentReports() {
            try {
                const response = await fetch('reports-recent');
                const data = await response.json();
                return data;
            } catch (error) {
                console.error("Error fetching reports:", error);
            }
        }

        async function initializeMap() {
            const usersInNeed = await fetchRecentReports();

            if (usersInNeed && Array.isArray(usersInNeed)) {
                usersInNeed.forEach(user => {
                    const marker = L.marker([user.latitude, user.longitude], { icon: userIcon }).addTo(map);
                    marker.bindPopup(`
                        <div class="popup-content">
                            <h4>User in Need: ${user.fullName}</h4>
                            <p><strong>File Proof: </strong> ${user.fileproof}</p>
                            <button onclick="navigateTo(${user.latitude}, ${user.longitude})" class="navigate-btn">Go Now</button>
                        </div>
                    `);
                });
            }
        }

        initializeMap();
        
        function cancelRoute() {
            startCoords = null;
            endCoords = null;
            routeLines.clearLayers();
            toggleDirections();
            document.getElementById("directions").innerHTML = "Route canceled. Click on the map to create a new route.";
        }

        // Function to show rescuer's geolocation
        function showRescuerLocation(position) {
            const rescuerLatitude = position.coords.latitude;
            const rescuerLongitude = position.coords.longitude;

            map.setView([rescuerLatitude, rescuerLongitude], 16); // Set map view to rescuer's location

            const rescuerMarker = L.marker([rescuerLatitude, rescuerLongitude]).addTo(map);
            rescuerMarker.bindPopup("'You are here.' -Rescuer").openPopup();

            // Set rescuer's geolocation as starting point
            startCoords = [rescuerLongitude, rescuerLatitude];
        }

        // Call function to get rescuer's geolocation
        getRescuerLocation();

        function toggleDirections() {
            const directionsDiv = document.getElementById("directions");
            const showStepsBtn = document.getElementById("show-steps");
            if (directionsDiv.style.display === "none") {
                directionsDiv.style.display = "block";
                showStepsBtn.textContent = "Hide Steps";
            } else {
                directionsDiv.style.display = "none";
                showStepsBtn.textContent = "Show Steps";
            }
        }

        function navigateToHydrant(lat, lng) {
            endCoords = [lng, lat];
            updateRoute();
        }

        let currentStep = "start";
        let startCoords = null;
        let endCoords = null;

        function updateRoute() {
            if (!startCoords || !endCoords) {
                alert("Please reload the page.");
                return;
            }

            // Create the arcgis-rest-js authentication object to use later.
            const authentication = arcgisRest.ApiKeyManager.fromKey(apiKey);

            // Make the API request
            arcgisRest.solveRoute({
                stops: [startCoords, endCoords],
                endpoint: "https://route-api.arcgis.com/arcgis/rest/services/World/Route/NAServer/Route_World/solve",
                authentication
            }).then((response) => {
                routeLines.clearLayers();
                L.geoJSON(response.routes.geoJson).addTo(routeLines);
                const directionsHTML = response.directions[0].features.map((f) => f.attributes.text).join("<br/>");
                document.getElementById("directions").innerHTML = directionsHTML;
                startCoords = null;
                endCoords = null;
            }).catch((error) => {
                console.error(error);
                alert("There was a problem using the route service. See the console for details.");
            });
        }

        // Function to show rescuer's geolocation
        function showRescuerLocation(position) {
            const rescuerLatitude = position.coords.latitude;
            const rescuerLongitude = position.coords.longitude;

            map.setView([rescuerLatitude, rescuerLongitude], 16); // Set map view to rescuer's location

            const rescuerMarker = L.marker([rescuerLatitude, rescuerLongitude]).addTo(map);
            rescuerMarker.bindPopup("'You are here.' -Rescuer").openPopup();

            // Set rescuer's geolocation as starting point
            startCoords = [rescuerLongitude, rescuerLatitude];

            // Suggest nearest fire hydrants
            suggestNearestHydrants({
                lat: rescuerLatitude,
                lng: rescuerLongitude
            });
        }

        // Call function to get rescuer's geolocation
        getRescuerLocation();


        // Function to get rescuer's geolocation
        function getRescuerLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showRescuerLocation);
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
        }


        map.on("click", (e) => {
            const coordinates = [e.latlng.lng, e.latlng.lat];

            if (currentStep === "start") {

                startLayerGroup.clearLayers();
                endLayerGroup.clearLayers();
                routeLines.clearLayers();

                L.marker(e.latlng).addTo(startLayerGroup);
                startCoords = coordinates;

                currentStep = "end";
                document.getElementById("directions").innerHTML = "Your Location";

                // Directly calculate the route when the rescuer clicks on the map
                if (startCoords && endCoords) {
                    updateRoute();
                }

            } else {

                L.marker(e.latlng).addTo(endLayerGroup);
                endCoords = coordinates;

                currentStep = "start";
                document.getElementById("directions").innerHTML = "Your Location";
                // Directly calculate the route when the rescuer clicks on the map
                if (startCoords && endCoords) {
                    updateRoute();
                }
            }

        });

        function suggestNearestHydrants(location) {
            const nearestHydrants = fireHydrants.filter(hydrant => {
                const distance = getDistance(location.lat, location.lng, hydrant.lat, hydrant.lng);
                return distance <= 2000; // 2000 meters (2 kilometers)
            });

            // Display suggestions
            const suggestionsContainer = document.getElementById("hydrant-suggestions");
            suggestionsContainer.innerHTML = ""; // Clear previous suggestions

            nearestHydrants.forEach(hydrant => {
                const suggestionDiv = document.createElement("div");
                suggestionDiv.classList.add("hydrant-suggestion");

                suggestionDiv.innerHTML = `
                    <h4>${hydrant.name}</h4>
                    <p>Distance: ${getDistance(location.lat, location.lng, hydrant.lat, hydrant.lng).toFixed(2)}meters</p>
                    <p>Estimated Time: (Unavailable)</p>
                    <button class="navigate-btn" onclick="navigateToHydrant(${hydrant.lat}, ${hydrant.lng})">Go now</button>
                    <button class="show-steps" onclick="toggleDirections()">Show Steps</button>
                `;

                suggestionsContainer.appendChild(suggestionDiv);
            });
        }

        // Function to calculate distance between two points (in meters)
        function getDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // Earth's radius in meters
            const φ1 = toRadians(lat1);
            const φ2 = toRadians(lat2);
            const Δφ = toRadians(lat2 - lat1);
            const Δλ = toRadians(lon2 - lon1);

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            const distance = R * c;
            return distance;
        }

        // Function to convert degrees to radians
        function toRadians(degrees) {
            return degrees * (Math.PI / 180);
        }

        // Call function to get rescuer's geolocation
        getRescuerLocation();

        // Map event listener to suggest nearest hydrants when clicking on the map
        map.on("click", (e) => {
            const location = {
                lat: e.latlng.lat,
                lng: e.latlng.lng
            };
            suggestNearestHydrants(location);
        });


    </script>
</body>

</html>