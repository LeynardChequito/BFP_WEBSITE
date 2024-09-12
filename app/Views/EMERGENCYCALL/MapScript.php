<script>
    document.addEventListener('DOMContentLoaded', function () {
    let isUserInteracted = false;
    const sirenSound = document.getElementById('sirenSound');

    // Listen for any user interaction
    document.addEventListener('click', function () {
        isUserInteracted = true;
    });
    
    // Function to reload the site
    function reloadSite() {
        location.reload();
    }

    // Set interval to reload the site every 10 minutes (10minutes * 60 * 1000 milliseconds)
    setInterval(reloadSite, 10 * 60 * 1000);

    // Function to update time display every second 
    function updateTime() {
        document.getElementById("philippineTime").textContent = "Philippine Standard Time: " + getCurrentTimeInPhilippines();
        setTimeout(updateTime, 1000); // Update time every second
    }

    // const apiKey = "AAPKb07ff7b9da8148cd89a46acc88c3c668OJ1KYSZifeA8-33Ign-Rw9GTSTMh1yjCUysmmuS7xd1_ydOreuns29W-y8JC5gBs"; //old api-key
    const apiKey = "AAPKac6c1269609841b2a00dd16b90f0ccb8iFjQh8pTb7aadJWaETJip3ISvXcpq_5cB296OQurtGW79gpbXuMKZPe9kx-6mGWl";
    const basemapEnum = "arcgis/navigation";
    const map = L.map("map", {
        zoom: 14
    });

    map.setView([13.3839, 121.1860], 14); // Calapan City location

    L.esri.Vector.vectorBasemapLayer(basemapEnum, {
        apiKey: apiKey
    }, 'Streets').addTo(map);

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

    function navigateToHydrant(lat, lng) {
        endCoords = [lng, lat];
        updateRoute();
    }

    const rescuerIcon = L.icon({
        iconUrl: 'https://img.icons8.com/3d-fluency/40/marker.png',
        iconSize: [40, 40],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    // Function to show rescuer's geolocation
    function showRescuerLocation(position) {
        const rescuerLatitude = position.coords.latitude;
        const rescuerLongitude = position.coords.longitude;

        map.setView([rescuerLatitude, rescuerLongitude], 16); // Set map view to rescuer's location

        const rescuerMarker = L.marker([rescuerLatitude, rescuerLongitude], {
            icon: rescuerIcon
        }).addTo(map);
        rescuerMarker.bindPopup("'You are here.' -Rescuer").openPopup();

        // Set rescuer's geolocation as starting point
        startCoords = [rescuerLongitude, rescuerLatitude];

        // Suggest nearest fire hydrants
        suggestNearestHydrants({
            lat: rescuerLatitude,
            lng: rescuerLongitude
        });
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
                const {
                    text,
                    length,
                    time
                } = f.attributes;
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
        const distance = getDistance(location.lat, location.lng, hydrant.lat, hydrant.lng);
        const averageSpeedMetersPerSecond = 11.11; // Approximate average speed in meters per second (40 km/h)
        const timeInSeconds = distance / averageSpeedMetersPerSecond;
        const timeInMinutes = timeInSeconds / 60; // Convert time from seconds to minutes

        const suggestionDiv = document.createElement("div");
        suggestionDiv.classList.add("hydrant-suggestion");

        suggestionDiv.innerHTML = `
            <h6>${hydrant.name}</h6>
            <p>Distance: ${distance.toFixed(2)} meters</p>
            <p>Estimated Time: ${timeInMinutes.toFixed(2)} minutes</p>
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
        iconUrl: 'https://img.icons8.com/papercut/40/user-location.png',
        iconSize: [40, 40],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });
    async function getRecentReports() {
        try {
            const response = await fetch('https://bfpcalapancity.online/reports-recent');
            const data = await response.json();

            if (response.ok && Array.isArray(data)) {
                const newReportsList = document.getElementById('newReportsList');
                const sirenSound = document.getElementById('sirenSound');
                let newReportsReceived = false;

                newReportsList.innerHTML = '';

                data.forEach(report => {
                    if (report.latitude && report.longitude) {
                        newReportsReceived = true;

                        const { latitude, longitude, fullName, fileproof, timestamp } = report;

                        const listItem = document.createElement('li');
                        listItem.classList.add('list-group-item');

                        // Create the file proof content based on file type
                        let fileProofContent = '';
                        const fullURL = `bfpcalapancity/public/community_report/${fileproof}`;
                        if (fullURL.endsWith(".mp4") || fullURL.endsWith(".mov") || fullURL.endsWith(".avi")) {
                            fileProofContent = `<video src="${fullURL}" controls class="file-proof-video"></video>`;
                        } else if (fullURL.endsWith(".jpg") || fullURL.endsWith(".jpeg") || fullURL.endsWith(".png")) {
                            fileProofContent = `<img src="${fullURL}" alt="File Proof" class="file-proof-image">`;
                        } else {
                            fileProofContent = "Unsupported file type";
                        }

                        listItem.innerHTML = `
                            <div style="border: 1px solid #ccc; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                                <h4 style="margin-bottom: 5px;">User in Need: ${fullName}</h4>
                                <p style="margin-bottom: 5px;"><strong>Timestamp:</strong> ${timestamp}</p>
                                <p style="margin-bottom: 5px;"><strong>File Proof:</strong></p>
                                <div class="fileProofContainer" style="margin-bottom: 10px;">${fileProofContent}</div>
                                <button style="background-color: #007bff; color: #fff; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; margin-right: 10px;" onclick="showRouteToRescuer(${latitude}, ${longitude})">Show Route</button>
                                <button style="background-color: #007bff; color: #fff; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;" onclick="accessFireReportForm()">File Report</button>
                                <div id="directions" style="display: none;"></div>
                            </div>
                        `;

                        newReportsList.appendChild(listItem);

                        const marker = L.marker([latitude, longitude], {
                            icon: userMarker
                        }).addTo(map);
                        const popupContent = `
                            <div class="popup-content">
                                <h4>User in Need: ${fullName}</h4>
                                <p><strong>Timestamp:</strong> ${timestamp}</p>
                                <p><strong>File Proof:</strong></p>
                                <div class="fileProofContainer">${fileProofContent}</div>
                                <button onclick="showRouteToRescuer(${latitude}, ${longitude})">Show Route</button>
                                <button onclick="accessFireReportForm()">File Report</button>
                                <button onclick="toggleDirections()">Show Steps</button>
                                <div id="directions" style="display: none;"></div>
                            </div>
                        `;
                        marker.bindPopup(popupContent);
                    } else {
                        console.warn('Invalid report location:', report);
                    }
                });

                // Play siren sound if new reports are received
                if (newReportsReceived) {
                    sirenSound.play();
                }

                const newReportModal = new bootstrap.Modal(document.getElementById('newReportModal'));
                newReportModal.show();
            } else {
                console.error('Failed to fetch recent reports:', response.statusText);
            }
        } catch (error) {
            console.error('Error fetching recent reports:', error);
        }
    }

    function accessFireReportForm() {
        window.location.href = 'fire-report/create';
    }

    function displayFileProof(fileProofURL, containerId) {
        const baseURL = 'bfpcalapancity/public/community_report/';
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
            img.alt = "File Proof";
            fileProofContainer.appendChild(img);
        } else {
            fileProofContainer.innerHTML = "Unsupported file type";
        }
    }

    function showRouteToRescuer(lat, lng) {
    endCoords = [lng, lat];
    updateRouteAndDisplayInfo();
}

async function updateRouteAndDisplayInfo() {
    if (!startCoords || !endCoords) {
        alert("Please reload the page.");
        return;
    }

    const authentication = arcgisRest.ApiKeyManager.fromKey(apiKey);

    try {
        const response = await arcgisRest.solveRoute({
            stops: [startCoords, endCoords],
            endpoint: "https://route-api.arcgis.com/arcgis/rest/services/World/Route/NAServer/Route_World/solve",
            authentication
        });

        routeLines.clearLayers();
        L.geoJSON(response.routes.geoJson).addTo(routeLines);

        const directionsHTML = response.directions[0].features.map((f) => {
            const { text, length, time } = f.attributes;
            return `<p>${text} (${length.toFixed(2)} meters, ${time.toFixed(2)} minutes)</p>`;
        }).join("");

        // Display route directions and info
        const directionsDiv = document.getElementById("directions");
        directionsDiv.style.display = "block";
        directionsDiv.innerHTML = directionsHTML;

        startCoords = null;
        endCoords = null;
    } catch (error) {
        console.error("Error calculating route:", error);
        alert("There was a problem calculating the route. Please try again.");
    }
}


    document.addEventListener('DOMContentLoaded', function() {
        getRecentReports(); // Fetch new reports on mount
    });
</script>