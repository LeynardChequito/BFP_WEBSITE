<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
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
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #2c2f33; /* Dark background */
            color: #ffffff; /* White text */
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            height: calc(100% - 70px);
            /* Adjust height for direction panel */
            z-index: 1;
        }

        #directions {
            position: absolute;
            z-index: 1000;
            width: 300px;
            max-height: 50%;
            right: 20px;
            top: 20px;
            overflow-y: auto;
            background: #23272a; /* Darker card background */
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            display: none;
            font-size: 14px;
        }

        .btn-back {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #7289da; /* Waze-like button color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px; /* Rounded buttons */
            cursor: pointer;
            transition: background-color 0.3s ease;
            z-index: 1100;
        }

        .btn-back:hover {
            background-color: #5b6eae; /* Darker shade for hover effect */
        }

        .hydrant-suggestion {
            background-color: #99aab5; /* Light grey background */
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .hydrant-suggestion h6 {
            margin: 0;
            color: #ffffff;
        }

        .navigate-btn,
        .show-steps {
            background-color: #7289da; /* Button color */
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px; /* Rounded buttons */
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .navigate-btn:hover,
        .show-steps:hover {
            background-color: #5b6eae; /* Darker shade for hover effect */
        }

        .popup-content {
            background-color: #2c2f33; /* Dark background */
            color: #ffffff; /* White text */
            border-radius: 5px;
            padding: 10px;
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #directions {
                width: 80%;
                max-height: 40%;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
            }

            .hydrant-suggestion {
                padding: 8px;
            }

            .navigate-btn,
            .show-steps {
                width: 100%;
            }
        }

    </style>
</head>

<body>

    <!-- Button to trigger back to admin-home -->
    <button class="btn-back" onclick="window.location.href='/admin-home';">Back</button>

    <div class="map-card">
        <div id="map-container">
            <div id="map"></div>
        </div>

        <div class="bfp-header">
            <label for="hydrant-suggestions">Suggested Nearby Fire Hydrants: </label>
            <div id="hydrant-suggestions" name="hydrant-suggestions" class="hydrant-suggestions"></div>
            <div id="directions" style="display: none;">Click on the map to create a start and end for the route.</div>
            <button id="directions" onclick="toggleDirections()">Show Steps</button>
        </div>

        <!-- Push Notification Button to open the New Reports modal -->
        <button class="push-notif-btn" data-bs-toggle="modal" data-bs-target="#newReportModal" onclick="getRecentReports()">
            &#128276;
        </button>

        <!-- Modal for new reports -->
        <div class="modal fade" id="newReportModal" tabindex="-1" aria-labelledby="newReportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newReportModalLabel">New Community Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul id="newReportsList" class="list-group">
                            <!-- Report details will be dynamically inserted here -->
                        </ul>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Audio for alerting -->
    <audio id="sirenSound" src="bfpcalapancity/public/45secs_alarm.mp3" preload=""></audio>


    <script>

const baseUrl = "<?= base_url() ?>";
    // Function to reload the site
    function reloadSite() {
        location.reload();
    }

    // Set interval to reload the site every 10 minutes (10minutes * 60 * 1000 milliseconds)
    setInterval(reloadSite, 5 * 60 * 1000);

    // Function to update time display every second 
    function updateTime() {
        document.getElementById("philippineTime").textContent = "Philippine Standard Time: " + getCurrentTimeInPhilippines();
        setTimeout(updateTime, 1000); // Update time every second
    }
    // Extract the latitude and longitude from the URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const lat = urlParams.get('lat');
    const lng = urlParams.get('lng');
    const communityreport_id = urlParams.get('communityreport_id'); // Get communityreport_id from URL

    // const apiKey = "AAPKb07ff7b9da8148cd89a46acc88c3c668OJ1KYSZifeA8-33Ign-Rw9GTSTMh1yjCUysmmuS7xd1_ydOreuns29W-y8JC5gBs"; //old api-key
    const apiKey = "AAPKac6c1269609841b2a00dd16b90f0ccb8iFjQh8pTb7aadJWaETJip3ISvXcpq_5cB296OQurtGW79gpbXuMKZPe9kx-6mGWl";
    const basemapEnum = "arcgis/navigation";
    const map = L.map("map", {
        zoom: 19
    });
    // Use OpenStreetMap tiles (no CORS issues)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 16,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    map.setView([13.3839, 121.1860], 15); // Calapan City location

    L.esri.Vector.vectorBasemapLayer(basemapEnum, {
        apiKey: apiKey
    }, 'Streets').addTo(map);

    const userMarkerIcon = L.icon({
    iconUrl: 'https://img.icons8.com/papercut/40/user-location.png',
    iconSize: [40, 40],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
});

    // Function to populate the report in the UI
    function populateReportList(reports) {
    const newReportsList = document.getElementById('newReportsList');
    newReportsList.innerHTML = ''; // Clear existing reports

    // Ensure reports is an array
    if (!Array.isArray(reports)) {
        reports = [reports]; // Wrap single report in an array if necessary
    }

    if (reports.length === 0) {
        newReportsList.innerHTML = '<p>No recent reports available</p>';
        return; // Exit if no valid data
    }

    reports.forEach(report => {
        console.log('Processing report:', report); // Log each report for debugging

        const {
            communityreport_id,
            latitude,
            longitude,
            fullName,
            fileproof,
            timestamp
        } = report;

        // Convert latitude and longitude to numbers
        const lat = parseFloat(latitude);
        const lng = parseFloat(longitude);

        const listItem = document.createElement('li');
        listItem.className = 'list-group-item report-item';
        listItem.id = `report-${communityreport_id}`;

        listItem.innerHTML = `
            <h4>User in Need: ${fullName}</h4>
            <p><strong>Timestamp:</strong> ${timestamp}</p>
            <p><strong>File Proof:</strong></p>
            <img src="${baseUrl}bfpcalapancity/public/community_report/${fileproof}" alt="File Proof" style="max-width: 100px; height: auto;">
            <button class="btn btn-primary" onclick="showRouteToRescuer(${latitude}, ${longitude})">Show Route</button>
            <button class="btn btn-secondary" onclick="submitReportForm(${latitude}, ${longitude}, ${communityreport_id})">Submit Fire Report</button>
        `;

        newReportsList.appendChild(listItem);

        // Add the user marker to the map
        const userMarker = L.marker([lat, lng], { icon: userMarkerIcon }).addTo(map);
        userMarker.bindPopup(`User in Need: ${fullName}`).openPopup(); // Popup with user's name
    });
}

if (communityreport_id) {
    fetchReportByCommunityReportId(communityreport_id);
}
function gotoRescueMap() {
    window.location.href = '/rescuemap?openModal=true';
}

    // Directions container
    const directions = document.createElement("div");
directions.id = "directions";
directions.innerHTML = "Click on the map to create a start and end for the route.";
document.body.appendChild(directions);

const startLayerGroup = L.layerGroup().addTo(map);
const endLayerGroup = L.layerGroup().addTo(map);
const routeLines = L.layerGroup().addTo(map);

    // Fire hydrant marker
    const fireHydrants = [{
            name: "Barangay Bayanan 1, Calapan City, Oriental Mindoro",
            lat: 13.370076,
            lng: 121.167853,
            color: "lightgreen"
        },
        {
            name: "Cor. JP Rizal, Barangay Lalud, Calapan City, Oriental Mindoro (Beside Kuyang Laundry Shop)",
            lat: 13.400788,
            lng: 121.171269,
            color: "lightgreen"
        },
        {
            name: "Ubas St., Barangay Lalud, Calapan City, Oriental Mindoro (near Barangay Hall, fornt of Sour Pluz Wingz Resto)",
            lat: 13.399337,
            lng: 121.173764,
            color: "lightgreen"
        },
        {
            name: "Barangay Camilmil, Calapan City, Oriental Mindoro ( near elementary school, front of rotary club building)",
            lat: 13.404487,
            lng: 121.178001,
            color: "lightgreen"
        },
        {
            name: "JP Rizal St., Barangay Camilmil, Calapan City, Oriental Mindoro (near the intersection, Autogear Marketing)",
            lat: 13.406119,
            lng: 121.176005,
            color: "lightgreen"
        },
        {
            name: "Barangay Camilmil, Calapan City, Oriental Mindoro (in front of Oriental Mindoro National Highschool)",
            lat: 13.409127,
            lng: 121.178673,
            color: "lightgreen"
        },
        {
            name: "Roxas drive, Cor. Gumamela St. Barangay Lumangbayan, Calapan City, Oriental Mindoro",
            lat: 13.4020172,
            lng: 121.1825127,
            color: "lightgreen"
        },
        {
            name: "Guiho St. Barangay, Sto. Nino, Calapan City, oriental Mindoro",
            lat: 13.4081789,
            lng: 121.1839165,
            color: "lightgreen"
        },
        {
            name: "Corner Bonifacio St, Barangay Ilaya, Calapan City, Oriental Mindoro",
            lat: 13.4128942,
            lng: 121.1839927,
            color: "lightgreen"
        },
        {
            name: "Mabini St. Barangay Ilaya, Calapan City, Oriental Mindoro",
            lat: 13.4141455,
            lng: 121.1845213,
            color: "lightgreen"
        },
        {
            name: "Barangay Ibaba East, Calapan City, Oriental Mindoro ( near old city hall/city plaza )",
            lat: 13.414796,
            lng: 121.1770664,
            color: "lightgreen"
        },
        // {
        //     name: "Malvar St., Barangay Ibaba East, Calapan City, Oriental Mindoro",
        //     lat: 13.414783206943,
        //     lng: 121.176872350370,
        //     color: "lightgreen"
        // },
        // {
        //     name: "Barangay Ibaba West, Calapan City, Oriental Mindoro",
        //     lat: 13.414783206943,
        //     lng: 121.176872350370,
        //     color: "lightgreen"
        // },
        // {
        //     name: "Roxas Drive Corner Marasigan St., Barangay Libis, Calapan City, oriental Mindoro",
        //     lat: 13.415158152476,
        //     lng: 121.184801921272,
        //     color: "lightgreen"
        // },
        {
            name: "Barangay Calero, Calapan City, Oriental Mindoro (near funenaria naujan)",
            lat: 13.4177283,
            lng: 121.1854651,
            color: "lightgreen"
        },
        {
            name: "Barangay Salong, Calapan City, Oriental Mindoro ( near children hospital)",
            lat: 13.4192085,
            lng: 121.1876884,
            color: "lightgreen"
        },
        {
            name: "Barangay San Antonio, Calapan City, Oriental Mindoro ( intersection going to calapan pier)",
            lat: 13.4232514,
            lng: 121.1937544,
            color: "lightgreen"
        },
        // {
        //     name: "Barangay San Antonio, Calapan City, Oriental Mindoro (calapan pier)",
        //     lat: 13.429675064813,
        //     lng: 121.195830847473,
        //     color: "lightgreen"
        // },
        // {
        //     name: "Barangay Tibag, Calapan City, Oriental Mindoro",
        //     lat: 13.412136593584,
        //     lng: 121.175821489887,
        //     color: "lightgreen"
        // },
        // {
        //     name: "Barangay Sta. Maria Village (Blk. 4), Calapan City, Oriental Mindoro",
        //     lat: 13.408596881704,
        //     lng: 121.175793602378,
        //     color: "lightgreen"
        // },
        // {
        //     name: "Infantado St., Barangay Sta. Vicente South, Calapan City, Oriental Mindoro ( near bagong pook)",
        //     lat: 13.408596881704,
        //     lng: 121.175793602378,
        //     color: "lightgreen"
        // }
        {
            name: "J.Luna St., Barangay San Vicente West, Calapan City, Oriental Mindoro",
            lat: 13.4129687,
            lng: 121.1782896,
            color: "lightgreen"
        },
        {
            name: "J.P Rizal St., Barangay San Vicente Central, Calapan City, Oriental Mindoro (front Palawan Express)",
            lat: 13.4091777,
            lng: 121.1786927
        },
        {
            name: "Del Pilar St., Barangay San Vicente East, Calapan City, Oriental Mindoro",
            lat: 13.4111748,
            lng: 121.1804908
        },

        {
            name: "Barangay San Vicente North, Calapan City, Oriental Mindoro (new public market 1)",
            lat: 13.412717,
            lng: 121.179210,
            color: "lightgreen"
        },
        {
            name: "Barangay San Vicente North, Calapan City, Oriental Mindoro (new public market 2)",
            lat: 13.412954,
            lng: 121.178348,
            color: "lightgreen"
        },
        {
            name: "Brgy. Guinobatan (Infront of New City Hall)",
            lat: 13.379384,
            lng: 121.182383,
            color: "lightgreen"
        }
    ];

    const hydrantIcon = L.icon({
        iconUrl: 'https://img.icons8.com/ultraviolet/40/fire-hydrant.png',
        iconSize: [40, 40],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    fireHydrants.forEach(hydrant => {
        const marker = L.marker([hydrant.lat, hydrant.lng], {
            icon: hydrantIcon
        }).addTo(map);
    });

    function cancelRoute() {
        startCoords = null;
        endCoords = null;
        routeLines.clearLayers();
        toggleDirections();
        document.getElementById("directions").innerHTML = "Route canceled. Click on the map to create a new route.";
    }

    async function navigateToHydrant(lat, lng) {
        endCoords = [lng, lat]; // Set hydrant as end point
        updateRoute(); // Update the route to the selected hydrant
    }
    const rescuerIcon = L.icon({
        iconUrl: 'https://img.icons8.com/3d-fluency/40/marker.png',
        iconSize: [40, 40],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    // Function to show rescuer's geolocation
    let rescuerMarker; // Declare rescuerMarker globally so it can be updated later

    function showRescuerLocation(position) {
    const rescuerLatitude = position.coords.latitude;
    const rescuerLongitude = position.coords.longitude;
    startCoords = [rescuerLongitude, rescuerLatitude];

    // Update rescuer's marker position
    if (!rescuerMarker) {
        rescuerMarker = L.marker([rescuerLatitude, rescuerLongitude], {
            icon: rescuerIcon
        }).addTo(map);
    } else {
        rescuerMarker.setLatLng([rescuerLatitude, rescuerLongitude]);
    }

    let distance = 0;
    let estimatedTime = 0;

    // Update route to adjust to the rescuer's current position
    if (endCoords) {
        distance = getDistance(rescuerLatitude, rescuerLongitude, endCoords[1], endCoords[0]); // Use endCoords for calculation
        estimatedTime = distance / 666.67; // Assuming speed is 40 km/h (666.67 m/min)
        updateRoute(); // Continuously update route to destination
    }

    // Create the content for the popup
    const popupContent = `
        <div>
            <p>'You are here.' - Rescuer</p>
            <p>Estimated Distance: ${distance.toFixed(2)} meters</p>
            <p>Estimated Time: ${estimatedTime.toFixed(2)} minutes</p>
        </div>
    `;

    rescuerMarker.bindPopup(popupContent).openPopup();

    // Save current position
    saveRescuerPositionToLocalStorage(position);

    suggestNearestHydrants({
        lat: rescuerLatitude,
        lng: rescuerLongitude
    });
}


    function saveCurrentRouteToLocalStorage() {
        if (startCoords && endCoords) {
            localStorage.setItem('startCoords', JSON.stringify(startCoords));
            localStorage.setItem('endCoords', JSON.stringify(endCoords));
        }
    }

    function saveRescuerPositionToLocalStorage(position) {
        localStorage.setItem('rescuerCoords', JSON.stringify({
            lat: position.coords.latitude,
            lng: position.coords.longitude
        }));
    }

    // Call this function whenever the route is updated or rescuer moves

    function restoreRouteAndPosition() {
        const savedStartCoords = JSON.parse(localStorage.getItem('startCoords'));
        const savedEndCoords = JSON.parse(localStorage.getItem('endCoords'));
        const savedRescuerCoords = JSON.parse(localStorage.getItem('rescuerCoords'));

        if (savedStartCoords && savedEndCoords) {
            startCoords = savedStartCoords;
            endCoords = savedEndCoords;
            updateRoute(); // Redraw the route on the map
        }

        if (savedRescuerCoords) {
            showRescuerLocation({
                coords: {
                    latitude: savedRescuerCoords.lat,
                    longitude: savedRescuerCoords.lng
                }
            });
        }
    }

    // Call this function on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', restoreRouteAndPosition);


    function trackRescuerLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            position => {
                showRescuerLocation(position);
            },
            error => {
                console.error('Error tracking rescuer location:', error);
                alert('Could not get your location. Please check your location settings.');
            }, {
                enableHighAccuracy: true,
                maximumAge: 500,
                timeout: 10000 // Increase timeout for better reliability
            }
        );
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}


    // Ensure rescuer's location is tracked on page load
    document.addEventListener('DOMContentLoaded', function() {
        trackRescuerLocation(); // Start tracking rescuer's current location when the page loads
    });


    function restoreRouteAndPosition() {
        const savedStartCoords = JSON.parse(localStorage.getItem('startCoords'));
        const savedEndCoords = JSON.parse(localStorage.getItem('endCoords'));
        const savedRescuerCoords = JSON.parse(localStorage.getItem('rescuerCoords'));

        if (savedStartCoords && savedEndCoords) {
            startCoords = savedStartCoords;
            endCoords = savedEndCoords;
            updateRoute(); // Redraw the route on the map
        }

        if (savedRescuerCoords) {
            showRescuerLocation({
                coords: {
                    latitude: savedRescuerCoords.lat,
                    longitude: savedRescuerCoords.lng
                }
            });
        }
    }

    // Call this function on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', restoreRouteAndPosition);


    // Ensure rescuer's location is tracked on page load
    document.addEventListener('DOMContentLoaded', function() {
        trackRescuerLocation(); // Start tracking rescuer's current location when the page loads
    });


    let startCoords = null;
    let endCoords = null;

    async function updateRoute() {
        if (!startCoords || !endCoords) {
            alert("Rescuer location or report location is missing. Please try again.");
            return;
        }

        const authentication = arcgisRest.ApiKeyManager.fromKey(apiKey);

        try {
            const response = await arcgisRest.solveRoute({
                stops: [startCoords, endCoords],
                endpoint: "https://route-api.arcgis.com/arcgis/rest/services/World/Route/NAServer/Route_World/solve",
                authentication
            });

            routeLines.clearLayers(); // Clear previous route
            L.geoJSON(response.routes.geoJson).addTo(routeLines); // Add new route to map

            // Display directions
            const directionsHTML = response.directions[0].features.map(f => {
                const {
                    text,
                    length,
                    time
                } = f.attributes;
                return `<p>${text} (${length.toFixed(2)} km, ${time.toFixed(2)} minutes)</p>`;
            }).join("");

            // Show directions panel and add the route details
            const directionsDiv = document.getElementById("directions");
            directionsDiv.innerHTML = directionsHTML;
            directionsDiv.style.display = "block"; // Show directions panel

        } catch (error) {
            console.error("Error calculating route:", error);
            alert("There was a problem calculating the route. Please try again.");
        }
    }

    function toggleDirections() {
        const directionsDiv = document.getElementById("directions");
        directionsDiv.style.display = directionsDiv.style.display === "none" ? "block" : "none";
    }
    // Ensure rescuer's location is captured on page load
    document.addEventListener('DOMContentLoaded', function() {
        getRescuerLocation(); // Get the rescuer's current location when the page loads
    });

    // Function to get and set the rescuer's location
    function getRescuerLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showRescuerLocation);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    document.addEventListener('DOMContentLoaded', getRescuerLocation);

    function suggestNearestHydrants(rescuerLocation) {
        const nearestHydrants = fireHydrants
            .map(hydrant => {
                const distance = getDistance(rescuerLocation.lat, rescuerLocation.lng, hydrant.lat, hydrant.lng);
                const timeInMinutes = distance / 666.67; // Assumed speed: 40 km/h
                return {
                    ...hydrant,
                    distance,
                    timeInMinutes
                };
            })
            .filter(hydrant => hydrant.distance <= 2500) // 2.5km radius
            .sort((a, b) => a.distance - b.distance); // Sort by distance

        // Update the suggestions list
        const suggestionsContainer = document.getElementById("hydrant-suggestions");
        suggestionsContainer.innerHTML = "";

        nearestHydrants.forEach(hydrant => {
            const suggestionDiv = document.createElement("div");
            suggestionDiv.classList.add("hydrant-suggestion");
            suggestionDiv.innerHTML = `
                <h6>${hydrant.name}</h6>
                <p>Distance: ${hydrant.distance.toFixed(2)} meters</p>
                <p>Estimated Time: ${hydrant.timeInMinutes.toFixed(2)} minutes</p>
                <button class="navigate-btn" onclick="navigateToHydrant(${hydrant.lat}, ${hydrant.lng})">Go now</button>
                <button class="show-steps" onclick="toggleDirections()">Show Steps</button>
            `;
            suggestionsContainer.appendChild(suggestionDiv);
        });
    }

    // Function to calculate the distance between two geographic points using the Haversine formula
    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // Earth’s radius in meters
        const φ1 = toRadians(lat1);
        const φ2 = toRadians(lat2);
        const Δφ = toRadians(lat2 - lat1);
        const Δλ = toRadians(lon2 - lon1);
        const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
            Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // In meters
    }

    function toRadians(degrees) {
        return degrees * (Math.PI / 180);
    }

    // Function to track and remove submitted reports
    // Function to add task to the DOM (For Task List)
    function addTaskToDOM(taskText, checked = false) {
        const taskList = document.getElementById('task-list');
        const newTask = document.createElement('li');
        newTask.className = 'list-group-item d-flex justify-content-between align-items-center';
        newTask.innerHTML = `
        <span>${taskText}</span>
        <div>
            <label class="me-2" style="margin-left: 30px;">
                <input type="checkbox" class="form-check-input me-2" ${checked ? 'checked' : ''}> On Process
            </label>
            <button class="btn btn-danger btn-sm" style="margin-left: 10px;" onclick="removeTask(this)">Done</button>
        </div>
    `;
        taskList.appendChild(newTask);
    }

    // Function to add a new task
    function addTask() {
        const taskText = document.getElementById('new-task').value;
        if (taskText === '') {
            alert('Please enter a task');
            return;
        }
        addTaskToDOM(taskText);
        saveTasks(); // Save tasks to localStorage
        document.getElementById('new-task').value = ''; // Clear input field
    }

    // Function to remove a task with confirmation
    function removeTask(button) {
        const confirmation = confirm("Are you sure you want to mark this task as done?");
        if (confirmation) {
            const taskItem = button.closest('li');
            taskItem.remove();
            saveTasks(); // Save tasks to localStorage after removal
        }
    }

    // Function to save tasks to localStorage
    function saveTasks() {
        const taskList = document.querySelectorAll('#task-list li');
        const tasks = [];
        taskList.forEach(task => {
            tasks.push({
                text: task.querySelector('span').textContent,
                checked: task.querySelector('input').checked
            });
        });
        localStorage.setItem('tasks', JSON.stringify(tasks));
    }

    // Function to load tasks from localStorage
    function loadTasks() {
        const savedTasks = localStorage.getItem('tasks');
        if (savedTasks) {
            const tasks = JSON.parse(savedTasks);
            tasks.forEach(task => {
                addTaskToDOM(task.text, task.checked);
            });
        }
    }

    // Listen for changes to checkboxes and save state
    document.addEventListener('change', function(e) {
        if (e.target && e.target.type === 'checkbox') {
            saveTasks(); // Save tasks to localStorage on checkbox toggle
        }
    });

    // Load tasks when the page is loaded
    document.addEventListener('DOMContentLoaded', function() {
        loadTasks();
    });

    // Function to remove a report notification with confirmation
    function removeNotification(reportId) {
        const confirmation = confirm("Are you sure you want to remove this notification?");
        if (confirmation) {
            // Mark the report as removed (store the ID in localStorage)
            markReportAsRemoved(reportId);

            // Remove the report from the UI
            const reportItem = document.getElementById(`report-${reportId}`);
            if (reportItem) {
                reportItem.remove();
            }
        }
    }

    // Function to check if a report has been submitted (already marked as handled)
    function isReportSubmitted(communityreport_id) {
        const submittedReports = JSON.parse(localStorage.getItem('submittedReports')) || [];
        return submittedReports.includes(communityreport_id);
    }
    // Function to check if a report has been removed
    function isReportRemoved(reportId) {
        const removedReports = JSON.parse(localStorage.getItem('removedReports')) || [];
        return removedReports.includes(reportId);
    }

    // Function to store removed reports in localStorage to avoid fetching them again
    function markReportAsRemoved(reportId) {
        let removedReports = JSON.parse(localStorage.getItem('removedReports')) || [];

        if (!removedReports.includes(reportId)) {
            removedReports.push(reportId);
            localStorage.setItem('removedReports', JSON.stringify(removedReports));
        }
    }
  // Async function to fetch recent reports
  async function getRecentReports() {
    try {
        const response = await fetch('https://bfpcalapancity.online/reports-recent');

        // Check if the response is OK (status in the range 200-299)
        if (!response.ok) {
            console.error('Error fetching recent reports:', response.statusText);
            throw new Error('Failed to fetch recent reports');
        }

        // Try to parse JSON response
        const data = await response.json();

        // If the data is not an array, handle it accordingly
        if (Array.isArray(data)) {
            populateReportList(data); // Pass data to populateReportList
        } else {
            console.error('No recent reports available');
            document.getElementById('newReportsList').innerHTML = '<p>No recent reports available</p>'; // Inform the user
        }
    } catch (error) {
        console.error('Error fetching recent reports:', error);
        document.getElementById('newReportsList').innerHTML = '<p>Error fetching reports. Please try again later.</p>'; // Inform the user
    }


    // If communityreport_id exists, fetch specific report
    if (communityreport_id) {
        fetchReportByCommunityReportId(communityreport_id);
    }
}

// Function to open the modal when the URL contains #newReportModal
function openModalOnHash() {
    if (window.location.hash === '#newReportModal') {
        const newReportModal = new bootstrap.Modal(document.getElementById('newReportModal'));
        newReportModal.show();
        getRecentReports(); // Load the recent reports when the modal opens
    }
}

// Function to mark a report as submitted and trigger form submission
function submitReportForm(lat, lng, communityreport_id) {
    const form = document.getElementById('fireReportForm');
    if (!form) {
        console.error("Form with ID 'fireReportForm' does not exist.");
        alert("The report form is currently unavailable. Please try again later.");
        return;
    }

    form.communityreport_id.value = communityreport_id; // Set the community report ID

    // Use FormData to handle form submission
    const formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (response.ok) {
            return response.json(); // Assuming server returns JSON
        }
        throw new Error('Network response was not ok.');
    })
    .then(data => {
        console.log(data); // Log the server response
        alert('Fire report submitted successfully!');
        // Redirect or update the UI as needed
        window.location.href = `fire-report/create?lat=${lat}&lng=${lng}&communityreport_id=${communityreport_id}`;
    })
    .catch(error => {
        console.error('Error submitting form:', error);
        alert('An error occurred while submitting the form. Please try again.');
    });
}
// Fetch recent reports if communityreport_id is present in the URL
if (communityreport_id) {
    fetch(`https://bfpcalapancity.online/getReportByCommunityReportId/${communityreport_id}`)
    .then(response => response.json())
    .then(report => populateReportList([report]))
    .catch(error => console.error('Error fetching report:', error));
}

// On DOMContentLoaded, initialize the necessary functions
document.addEventListener('DOMContentLoaded', function() {
    updateTime(); // Start the clock
});

  function toggleDirections() {
        const directionsDiv = document.getElementById("directions");
        directionsDiv.style.display = directionsDiv.style.display === "none" ? "block" : "none";
    }

    function accessFireReportForm() {
        window.location.href = 'fire-report/create';
    }

    // Function to display the file proof
function displayFileProof(fileProofURL, containerId) {
    const fullURL = `${baseUrl}bfpcalapancity/public/community_report/${fileProofURL}`; // Construct full URL

    const fileProofContainer = document.getElementById(containerId);

    if (!fileProofContainer) {
        console.error(`Container with ID ${containerId} not found.`);
        return;
    }

    // Check for file type and display accordingly
    if (fullURL.endsWith(".mp4") || fullURL.endsWith(".mov") || fullURL.endsWith(".avi")) {
        const video = document.createElement("video");
        video.src = fullURL;
        video.controls = true;
        fileProofContainer.appendChild(video);
    } else if (fullURL.endsWith(".jpg") || fullURL.endsWith(".jpeg") || fullURL.endsWith(".png")) {
        const img = document.createElement("img");
        img.src = fullURL; // Use the constructed full URL
        img.alt = "File Proof";
        img.style.maxWidth = "100%"; // Optionally set style
        fileProofContainer.appendChild(img);
    } else {
        fileProofContainer.innerHTML = "Unsupported file type";
    }
}


    function showRouteToRescuer(lat, lng) {
        endCoords = [lng, lat]; // Set the endCoords to the location of the report
        updateRoute(); // Call the function to update and display the route

        // Close the modal after clicking the "Show Route" button
var modalElement = document.getElementById('newReportModal');
var modalInstance = bootstrap.Modal.getInstance(modalElement); // Get the instance of the modal
if (modalInstance) {
    modalInstance.hide(); // Close the modal
}

    }

    // Function to update and display the route
    async function updateRoute() {
        if (!startCoords || !endCoords) {
            alert("Rescuer location or report location is missing. Please try again.");
            return;
        }

        const authentication = arcgisRest.ApiKeyManager.fromKey(apiKey);

        try {
            const response = await arcgisRest.solveRoute({
                stops: [startCoords, endCoords],
                endpoint: "https://route-api.arcgis.com/arcgis/rest/services/World/Route/NAServer/Route_World/solve",
                authentication
            });

            routeLines.clearLayers(); // Clear previous route
            L.geoJSON(response.routes.geoJson).addTo(routeLines); // Add new route to map

            // Display directions
            const directionsHTML = response.directions[0].features.map(f => {
                const {
                    text,
                    length,
                    time
                } = f.attributes;
                return `<p>${text} (${length.toFixed(2)} km, ${time.toFixed(2)} minutes)</p>`;
            }).join("");

            // Show directions panel and add the route details
            const directionsDiv = document.getElementById("directions");
            directionsDiv.innerHTML = directionsHTML;
            directionsDiv.style.display = "block"; // Show directions panel

        } catch (error) {
            console.error("Error calculating route:", error);
            alert("There was a problem calculating the route. Please try again.");
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
    // Fetch recent reports when the page loads
    getRecentReports();

    // Check if the page loads with #newReportModal in the URL hash
    openModalOnHash();
});

// Function to extract the 'communityreport_id' from the URL
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    const results = regex.exec(window.location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}
function fetchReportByCommunityReportId(communityreport_id) {
    fetch(`https://bfpcalapancity.online/getReportByCommunityReportId/${communityreport_id}`)
        .then(response => {
            if (!response.ok) {
                console.error('Failed to fetch report data:', response.statusText);
                throw new Error('No valid report data available');
            }
            return response.json();
        })
        .then(report => {
            console.log('Fetched report:', report); // Log the entire report object

            if (report && typeof report === 'object' && Object.keys(report).length > 0) {
                populateReportList([report]); // Populate the modal with the specific report
            } else {
                console.error('Invalid report structure or empty report received', report);
            }
        })
        .catch(error => {
            console.error('Error fetching report:', error);
            if (error.message.includes('404')) {
                alert('Report not found. Please check the report ID.');
            }
        });
}

// Call this function on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    updateTime(); // Start the clock
    getRecentReports(); // Fetch recent reports when the page loads
    openModalOnHash(); // Check if the page loads with #newReportModal in the URL hash
});
</script>

</body>

</html>