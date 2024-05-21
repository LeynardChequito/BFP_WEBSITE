<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <title>Rescue Map</title>

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
            font-size: 16px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
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
                width: 45%;
                max-height: 45%;
            }

            .popup-content {
                background-color: #fff;
                /* White background */
                border-radius: 5px;
                padding: 5px;
                font-family: 'Arial', sans-serif;
                /* Use a font similar to Waze.com */
                font-size: 14px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                /* Light shadow */
                
             }

            #map-container {
                width: 100%;
                height: 70vh;
            }

            #map {
                width: 100%;
                height: 80%;
            }

            .hydrant-suggestion {
                background-color: #fff;
                padding: 7px;
                margin-bottom: 7px;
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
                margin-top: 5px;
            }

            .navigate-btn:hover {
                background-color: #0056b3;
            }
            .fileProofContainer {
                width: 150px;
                height: 100px;
                
                overflow: hidden;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .fileProofContainer img,
            .fileProofContainer video {
                width: 150px;
                height: 100px;
                object-fit:cover;
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
            .fileProofContainer {
                width: 270px;
                height: 170px;
                
                overflow: hidden;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .fileProofContainer img,
            .fileProofContainer video {
                width: 250px;
                height: 150px;
                object-fit:cover;
            }

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
            <label for="user-suggestions">Suggested Nearby Users in Need: </label>
            <div id="user-suggestions" name="user-suggestions" class="user-suggestions"></div>
            <div id="directions" style="display:none;"></div>
    <button id="show-steps" onclick="toggleDirections()">Show Steps</button>
        </div>

        <div class="bfp-header">
            <label for="hydrant-suggestions">Suggested Nearby Fire Hydrants: </label>
            <div id="hydrant-suggestions" name="hydrant-suggestions" class="hydrant-suggestions"></div>
            <button id="directions" onclick="toggleDirections()">Show Steps</button>
            <!-- <div id="directions" style="display:none;"> Click on the map to create a start and end for the route.</div> -->
        </div>
    </div>

    <script>
        // Function to reload the site
        function reloadSite() {
            location.reload();
        }

        // Set interval to reload the site every 30 minutes (30 * 60 * 1000 milliseconds)
        setInterval(reloadSite, 60 * 60 * 1000);

        // Function to update time display every second 
        function updateTime() {
            document.getElementById("philippineTime").textContent = "Philippine Standard Time: " + getCurrentTimeInPhilippines();
            setTimeout(updateTime, 1000); // Update time every second
        }

        function getCurrentTimeInPhilippines() {
            const currentTime = new Date();
            const utcOffset = 0; // GMT+8:00 for Philippines
            const philippinesTime = new Date(currentTime.getTime() + (utcOffset * 3600000)); // Adding offset in milliseconds

            // Format date components
            const day = philippinesTime.getDate();
            const month = philippinesTime.toLocaleString('default', { month: 'long' });
            const year = philippinesTime.getFullYear();
            const time = philippinesTime.toLocaleTimeString();

            return `${month} ${day}, ${year} ${time}`;
        }

        updateTime();

        const apiKey = "AAPKb07ff7b9da8148cd89a46acc88c3c668OJ1KYSZifeA8-33Ign-Rw9GTSTMh1yjCUysmmuS7xd1_ydOreuns29W-y8JC5gBs";
        const basemapEnum = "arcgis/navigation";
        const map = L.map("map", { zoom: 14 });

        map.setView([13.3839, 121.1860], 14); // Calapan City location

        L.esri.Vector.vectorBasemapLayer(basemapEnum, { apiKey: apiKey }, 'Streets').addTo(map);

        // Directions container
        const directions = document.createElement("div");
        directions.id = "directions";
        directions.innerHTML = "Click on the map to create a start and end for the route.";
        document.body.appendChild(directions);

        const startLayerGroup = L.layerGroup().addTo(map);
        const endLayerGroup = L.layerGroup().addTo(map);
        const routeLines = L.layerGroup().addTo(map);

        // Fire hydrant marker
        const fireHydrants = [
            {
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
            },
            {
                name: "JP Rizal St., Barangay Camilmil, Calapan City, Oriental Mindoro",
                lat: 13.406119,
                lng: 121.176005,
                color: "lightgreen"
            },
            {
                name: "Barangay Camilmil, Calapan City, Oriental Mindoro ( in front of Oriental Mindoro National Highschool)",
                lat: 13.409127,
                lng: 121.178673,
                color: "lightgreen"
            },
            {
                name: "Roxas drive, Cor. Gumamela St. Barangay Lumangbayan, Calapan City, Oriental Mindoro",
                lat: 13.401820739889,
                lng: 121.182757083021,
                color: "lightgreen"
            },
            {
                name: "Guiho St. Barangay, Sto. Nino, Calapan City, oriental Mindoro",
                lat: 13.404072878721,
                lng: 121.184182160977,
                color: "lightgreen"
            },
            {
                name: "Corner Bonifacio St, Barangay Ilaya, Calapan City, Oriental Mindoro",
                lat: 13.412771258480,
                lng: 121.183841835900,
                color: "lightgreen"
            },
            {
                name: "Mabini St. Barangay Ilaya, Calapan City, Oriental Mindoro",
                lat: 13.411691645848,
                lng: 121.183589742959,
                color: "lightgreen"
            },
            {
                name: "Barangay Ibaba East, Calapan City, Oriental Mindoro ( near old city hall/city plaza )",
                lat: 13.419379075623,
                lng: 121.179612690830,
                color: "lightgreen"
            },
            {
                name: "Malvar St., Barangay Ibaba East, Calapan City, Oriental Mindoro",
                lat: 13.414783206943,
                lng: 121.176872350370,
                color: "lightgreen"
            },
            {
                name: "Barangay Ibaba West, Calapan City, Oriental Mindoro",
                lat: 13.414783206943,
                lng: 121.176872350370,
                color: "lightgreen"
            },
            {
                name: "Roxas Drive Corner Marasigan St., Barangay Libis, Calapan City, oriental Mindoro",
                lat: 13.415158152476,
                lng: 121.184801921272,
                color: "lightgreen"
            },
            {
                name: "Barangay Calero, Calapan City, Oriental Mindoro ( near atty. Manzo office)",
                lat: 13.415597264627,
                lng: 121.181560275534,
                color: "lightgreen"
            },
            {
                name: "Barangay San Rafael, Calapan City, Oriental Mindoro ( near children hospital)",
                lat: 13.418591183674,
                lng: 121.186988682784,
                color: "lightgreen"
            },
            {
                name: "Barangay San Antonio, Calapan City, Oriental Mindoro (calapan pier)",
                lat: 13.429675064813,
                lng: 121.195830847473,
                color: "lightgreen"
            },
            {
                name: "Barangay Tibag, Calapan City, Oriental Mindoro",
                lat: 13.412136593584,
                lng: 121.175821489887,
                color: "lightgreen"
            },
            {
                name: "Barangay Sta. Maria Village (Blk. 4), Calapan City, Oriental Mindoro",
                lat: 13.408596881704,
                lng: 121.175793602378,
                color: "lightgreen"
            },
            {
                name: "Infantado St., Barangay Sta. Vicente South, Calapan City, Oriental Mindoro ( near bagong pook)",
                lat: 13.408596881704,
                lng: 121.175793602378,
                color: "lightgreen"
            },
            // {
            //     name: "J.Luna St. Corner Aurora, Barangay San VicenteNorth, Calapan City, Oriental Mindoro",
            //     lat: 13.410813537771,
            //     lng: 121.179406846349,
            //     color: "lightgreen"
            // },
            // {
            //     name: "Ramirez St., Barangay San Vicente Central, Calapan City, Oriental Mindoro",
            //     lat: 13.411144781257,
            //     lng: 121.178600148414,
            //     color: "lightgreen"
            // },
            {
                name: "J.P Rizal St., Barangay San Vicente Central, Calapan City, Oriental Mindoro ( front Palawan Express)",
                lat: 13.410975,
                lng: 121.179344,
                color: "lightgreen"
            },
            {
                name: "Aboboto St. Barangay San Vicente East, Calapan City, Oriental Mindoro",
                lat: 13.410624, 
                lng: 121.179887,
                color: "lightgreen"
            },
            {
                name: "Del Pilar St., Barangay San Vicente East, Calapan City, Oriental Mindoro",
                lat: 13.411200, 
                lng: 121.180478,
                color: "lightgreen"
            },
            // {
            //     name: "Barangay Lalud, Calapan City, Oriental Mindoro (near phoenix gasoline station)",
            //     lat: 13.402465071142,
            //     lng: 121.172008932260,
            //     color: "lightgreen"
            // },
            {name: "Barangay San Vicente North, Calapan City, Oriental Mindoro (new public market 1)",lat: 13.412717, lng: 121.179210, color: "lightgreen"},
            {name: "Barangay San Vicente North, Calapan City, Oriental Mindoro (new public market 2)", lat: 13.412954, lng: 121.178348, color: "lightgreen"},
            {name: "Brgy. Guinobatan (Infront of New City Hall)", lat: 13.379384, lng: 121.182383, color: "lightgreen"}
        ];

        const hydrantIcon = L.icon({
            iconUrl: 'https://img.icons8.com/cotton/64/000000/fire-hydrant--v1.png',
            iconSize: [60, 60],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        fireHydrants.forEach(hydrant => {
            const marker = L.marker([hydrant.lat, hydrant.lng], { icon: hydrantIcon }).addTo(map);

            marker.bindPopup(`
                <div>
                    <p><strong>${hydrant.name}</strong></p>
                    <button onclick="navigateToHydrant(${hydrant.lat}, ${hydrant.lng})">Go now</button>
                    <button onclick="cancelRoute()">Cancel Route</button>
                </div>
            `);
        });

        function cancelRoute() {
            startCoords = null;
            endCoords = null;
            routeLines.clearLayers();
            toggleDirections();
            document.getElementById("directions").innerHTML = "Route canceled. Click on the map to create a new route.";
        }

        function navigateToHydrant(lat, lng) {
            endCoords = [lng, lat];
            updateRoute();
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
            suggestNearestHydrants({ lat: rescuerLatitude, lng: rescuerLongitude });
        }

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

        let startCoords = null;
        let endCoords = null;

        function updateRoute() {
            if (!startCoords || !endCoords) {
                alert("Please reload the page.");
                return;
            }

            const authentication = arcgisRest.ApiKeyManager.fromKey(apiKey);

            arcgisRest.solveRoute({
                stops: [startCoords, endCoords],
                endpoint: "https://route-api.arcgis.com/arcgis/rest/services/World/Route/NAServer/Route_World/solve",
                authentication
            }).then((response) => {
                routeLines.clearLayers();
                L.geoJSON(response.routes.geoJson).addTo(routeLines);
                const directionsHTML = response.directions[0].features.map((f) => {
                    const { text, length, time } = f.attributes;
                    return `<p>${text} (${length.toFixed(2)} km, ${time.toFixed(2)} minutes)</p>`;
                }).join("");
                document.getElementById("directions").innerHTML = directionsHTML;
                startCoords = null;
                endCoords = null;
            }).catch((error) => {
                console.error(error);
                alert("There was a problem using the route service. See the console for details.");
            });
        }

        function getRescuerLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showRescuerLocation);
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
        }

        getRescuerLocation();

        function suggestNearestHydrants(location) {
            const nearestHydrants = fireHydrants.filter(hydrant => {
                const distance = getDistance(location.lat, location.lng, hydrant.lat, hydrant.lng);
                return distance <= 2000; // 2000 meters (2 kilometers)
            });

            const suggestionsContainer = document.getElementById("hydrant-suggestions");
            suggestionsContainer.innerHTML = ""; // Clear previous suggestions

            nearestHydrants.forEach(hydrant => {
                const suggestionDiv = document.createElement("div");
                suggestionDiv.classList.add("hydrant-suggestion");

                suggestionDiv.innerHTML = `
                    <h4>${hydrant.name}</h4>
                    <p>Distance: ${getDistance(location.lat, location.lng, hydrant.lat, hydrant.lng).toFixed(2)} meters</p>
                    <p>Estimated Time: (Unavailable)</p>
                    <button class="navigate-btn" onclick="navigateToHydrant(${hydrant.lat}, ${hydrant.lng})">Go now</button>
                    <button class="show-steps" onclick="toggleDirections()">Show Steps</button>
                `;

                suggestionsContainer.appendChild(suggestionDiv);
            });
        }

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

        function toRadians(degrees) {
            return degrees * (Math.PI / 180);
        }

        const userMarker = L.icon({
            iconUrl: 'https://img.icons8.com/flat-round/64/000000/fire-element.png',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        function showNotification(message) {
            if (!("Notification" in window)) {
                console.warn("This browser does not support desktop notifications.");
                return;
            }

            if (Notification.permission === "granted") {
                new Notification(message);
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(function (permission) {
                    if (permission === "granted") {
                        new Notification(message);
                    }
                });
            }
        }

        async function getRecentReports() {
            try {
                const response = await fetch('https://bfpcalapancity.online/reports-recent/');
                const data = await response.json();

                if (response.ok && Array.isArray(data)) {
                    if (data.length > 0) {
                        showNotification("New reports have been added.");
                    }

                    data.forEach(report => {
                        if (report.latitude && report.longitude) {
                            const { latitude, longitude, fullName, fileproof, timestamp } = report;
                            const marker = L.marker([latitude, longitude], { icon: userMarker }).addTo(map);
                            const popupContent = `
                                <div class="popup-content">
                                    <h4>User in Need: ${fullName}</h4>
                                    <p><strong>Timestamp:</strong> ${timestamp}</p>
                                    <p><strong>File Proof:</strong></p>
                                    <div id="fileProofContainer_${fileproof}" class="fileProofContainer"></div>
                                    <button onclick="showRouteToRescuer(${latitude}, ${longitude})">Show Route</button>
                                </div>
                            `;
                            marker.bindPopup(popupContent);

                            marker.on('popupopen', () => {
                                if (fileproof) {
                                    displayFileProof(fileproof, `fileProofContainer_${fileproof}`);
                                }
                            });
                        } else {
                            console.warn('Invalid report location:', report);
                        }
                    });
                } else {
                    console.error('Failed to fetch recent reports:', response.statusText);
                }
            } catch (error) {
                console.error('Error fetching recent reports:', error);
            }
        }

        function displayFileProof(fileProofURL, containerId) {
            const baseURL = '/bfpcalapancity/public/community_report/';
            const fullURL = baseURL + fileProofURL;

            const fileProofContainer = document.getElementById(containerId);

            if (!fileProofContainer) {
                console.error(`Container with ID ${containerId} not found.`);
                return;
            }

            if (fullURL.endsWith(".mp4") || fullURL.endsWith(".mov") || fullURL.endsWith(".avi")) {
                const video = document.createElement("video");
                video.src = fullURL;
                video.controls = true;
                fileProofContainer.appendChild(video);
            } else if (fullURL.endsWith(".jpg") || fullURL.endsWith(".jpeg") || fullURL.endsWith(".png")) {
                const img = document.createElement("img");
                img.src = fullURL;
                fileProofContainer.appendChild(img);
            } else {
                fileProofContainer.innerHTML = "Unsupported file type";
            }
        }

        function showRouteToRescuer(lat, lng) {
            endCoords = [lng, lat];
            updateRoute();
        }

        getRecentReports();
    </script>
</body>

</html>