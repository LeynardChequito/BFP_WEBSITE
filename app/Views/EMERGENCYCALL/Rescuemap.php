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
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-image: linear-gradient(to bottom right, black, red);
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
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
            font-size: 14px;
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
            position: fixed;
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
            z-index: 1100;
        }

        .btn-back:hover {
            background-color: #0056b3;
            /* Darker shade for hover effect */
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
                object-fit: cover;
            }
        }

        @media (min-width: 768px) {
            #directions {
                font-size: 15px;
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
                object-fit: cover;
            }

        }

        .push-notif-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50%;
            font-size: 18px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 1000;
            transition: background-color 0.3s ease;
        }

        .push-notif-btn:hover {
            background-color: #0056b3;
        }

        .push-notif-btn:focus {
            outline: none;
        }
    </style>
</head>

<body>
    
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


<button class="push-notif-btn" data-bs-toggle="modal" data-bs-target="#newReportModal" onclick="getRecentReports()">
    &#128276;
</button>

<!-- Modal for new reports -->
<div class="modal fade" id="newReportModal" tabindex="-1" aria-labelledby="newReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newReportModalLabel">New Community Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="newReportsList" class="list-group">
                    <!-- New reports will be listed here -->
                </ul>
            </div>
        </div>
    </div>
</div>

<audio id="sirenSound" src="bfpcalapancity/public/alarm.mp3" preload="auto"></audio>

    
<script>
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
// Use OpenStreetMap tiles (no CORS issues)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
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
   function showRescuerLocation(position) {
        const rescuerLatitude = position.coords.latitude;
        const rescuerLongitude = position.coords.longitude;
        startCoords = [rescuerLongitude, rescuerLatitude]; // Set rescuer's location

        map.setView([rescuerLatitude, rescuerLongitude], 16); // Center map on rescuer's location

        const rescuerMarker = L.marker([rescuerLatitude, rescuerLongitude], { icon: rescuerIcon }).addTo(map);
        rescuerMarker.bindPopup("'You are here.' - Rescuer").openPopup();

        suggestNearestHydrants({ lat: rescuerLatitude, lng: rescuerLongitude });
    }


    

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
                const { text, length, time } = f.attributes;
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
                return { ...hydrant, distance, timeInMinutes };
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
    const userMarker = L.icon({
        iconUrl: 'https://img.icons8.com/papercut/40/user-location.png',
        iconSize: [40, 40],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });
    
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
document.addEventListener('change', function (e) {
    if (e.target && e.target.type === 'checkbox') {
        saveTasks(); // Save tasks to localStorage on checkbox toggle
    }
});

// Load tasks when the page is loaded
document.addEventListener('DOMContentLoaded', function () {
    loadTasks();
});

// Function to mark a report as read and remove it from newReportsList (locally)
function removeNotification(reportId) {
    const confirmation = confirm("Are you sure you want to remove this notification?");
    if (confirmation) {
        markReportAsSubmitted(reportId);
        const reportItem = document.getElementById(`report-${reportId}`);
        if (reportItem) {
            reportItem.remove();
        }
    }
}

// Function to store submitted reports in localStorage to avoid re-rendering them
function markReportAsSubmitted(reportId) {
    let submittedReports = JSON.parse(localStorage.getItem('submittedReports')) || [];

    if (!submittedReports.includes(reportId)) {
        submittedReports.push(reportId);
        localStorage.setItem('submittedReports', JSON.stringify(submittedReports));
    }
}

// Function to check if a report has been marked as submitted
function isReportSubmitted(reportId) {
    const submittedReports = JSON.parse(localStorage.getItem('submittedReports')) || [];
    return submittedReports.includes(reportId);
}

// Function to get recent reports from the database and display them
async function getRecentReports() {
    try {
        const newReportsList = document.getElementById('newReportsList');
        const sirenSound = document.getElementById('sirenSound');
        const response = await fetch('https://bfpcalapancity.online/reports-recent');
        const data = await response.json();

        if (response.ok && Array.isArray(data)) {
            let newReportsReceived = false;

            newReportsList.innerHTML = ''; // Clear previous reports

            data.forEach(report => {
                const reportId = report.id;

                // Skip rendering if the report is already marked as submitted
                if (!isReportSubmitted(reportId)) {
                    newReportsReceived = true;
                    const { latitude, longitude, fullName, fileproof, timestamp } = report;
                    const listItem = document.createElement('li');
                    listItem.classList.add('list-group-item');
                    listItem.id = `report-${reportId}`;
                    listItem.innerHTML = `
                        <div style="padding: 10px; border-radius: 5px;">
                            <h4>User in Need: ${fullName}</h4>
                            <p><strong>Timestamp:</strong> ${timestamp}</p>
                            <p><strong>File Proof:</strong></p>
                            <div class="fileProofContainer" style="margin-bottom: 10px;">
                                <img src="bfpcalapancity/public/community_report/${fileproof}" alt="File Proof" class="file-proof-image">
                            </div>
                            <button class="btn btn-primary" onclick="removeNotification(${reportId})">Remove Notification</button>
                        </div>
                    `;
                    newReportsList.appendChild(listItem);
                }
            });

            if (newReportsReceived) {
                document.addEventListener('click', () => sirenSound.play(), { once: true });
            }
        } else {
            console.error('Failed to fetch recent reports:', response.statusText);
        }
    } catch (error) {
        console.error('Error fetching recent reports:', error);
    }
}


// Function to simulate submitting the report and mark it as submitted
function submitReportForm(lat, lng, reportId) {
    // Simulate the form submission (you can change the URL if needed)
    console.log("Form submission for report:", reportId);
    window.location.href = `fire-report/create?lat=${lat}&lng=${lng}&reportId=${reportId}`;

    // Mark this report as submitted
    markReportAsSubmitted(reportId);
}


function toggleDirections() {
        const directionsDiv = document.getElementById("directions");
        directionsDiv.style.display = directionsDiv.style.display === "none" ? "block" : "none";
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
                const { text, length, time } = f.attributes;
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

   document.addEventListener('DOMContentLoaded', function () {
    getRecentReports(); // Fetch new reports when the page loads
});
    
</script>
    
</body>

</html>
