<!DOCTYPE html>
<html>

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

    <style>
        body {
            margin: 0;
            padding: 0;
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
        }

        #directions {
            position: absolute;
            z-index: 1000;
            width: 30%;
            max-height: 50%;
            right: 20px;
            top: 20px;
            overflow-y: auto;
            /* Show a scrollbar if needed */
            background: white;
            font-family: Arial, Helvetica, Verdana;
            line-height: 1.5;
            font-size: 20px;
            padding: 10px;
        }

        .map-card {
            width: 90%;
            /* Adjust width as needed */
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
            padding: 20px 0;
            background-color: #f8f9fa;
            margin: 0;
        }

        #map-container {
            width: 100%;
            height: 800px;
            /* Adjust height as needed for smaller screens */
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
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #0056b3;
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
    </style>

</head>

<body>
    <button onclick="history.go(-1);" class="btn-back">Back</button>
    <div class="bfp-header">
        Bureau of Fire Protection (BFP)
        <div id="philippineTime" class="philippine-time"></div>
    </div>
    <div class="map-card">
        <!-- Map container -->
        <div id="map-container">
            <div id="map"></div>
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
            zoom: 14
        })

        map.setView([13.3839, 121.1860], 14); // Default location

        L.esri.Vector.vectorBasemapLayer(basemapEnum, {
            apiKey: apiKey
        }).addTo(map);

        const directions = document.createElement("div");
        directions.id = "directions";
        directions.innerHTML = "Click on the map to create a start and end for the route.";
        document.body.appendChild(directions);

        const startLayerGroup = L.layerGroup().addTo(map);
        const endLayerGroup = L.layerGroup().addTo(map);

        const routeLines = L.layerGroup().addTo(map);

        // Define fire hydrant data
        const fireHydrants = [{
                name: "Barangay Bayanan 1, Calapan City, Oriental Mindoro (beside Calapan Waterworks Corp. Compound)",
                lat: 13.355547541837,
                lng: 121.170303614926,
                color: "lightgreen"
            },
            {
                name: "Cor. JP Rizal, Barangay Lalud, Calapan City, Oriental Mindoro (Near LGC)",
                lat: 13.399026784522,
                lng: 121.174347236556,
                color: "lightgreen"
            },
            {
                name: "Ubas St., Barangay Lalud, Calapan City, Oriental Mindoro (near Barangay Hall)",
                lat: 13.398536024051,
                lng: 121.175305189208,
                color: "lightgreen"
            },
            {
                name: "Barangay Camilmil, Calapan City, Oriental Mindoro ( near elementary school)",
                lat: 13.406225165762,
                lng: 121.176445091041,
                color: "lightgreen"
            },
            {
                name: "JP Rizal St., Barangay Camilmil, Calapan City, Oriental Mindoro",
                lat: 13.407441929551,
                lng: 121.777849362988,
                color: "lightgreen"
            },
            {
                name: "Barangay Camilmil, Calapan City, Oriental Mindoro ( in front of Oriental Mindoro National Highschool)",
                lat: 13.408893063481,
                lng: 121.178695787075,
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
                name: "Roxas Drice Corner Marasigan St., Barangay Libis, Calapan City, oriental Mindoro",
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
            {
                name: "J.Luna St. Cornern Aurora, Barangay San VicenteNorth, Calapan City, Oriental Mindoro",
                lat: 13.410813537771,
                lng: 121.179406846349,
                color: "lightgreen"
            },
            {
                name: "Ramirez St., Barangay San Vicente Central, Calapan City, Oriental Mindoro",
                lat: 13.411144781257,
                lng: 121.178600148414,
                color: "lightgreen"
            },
            {
                name: "J.P Rizal St., Barangay San Vicente Central, Calapan City, Oriental Mindoro ( front Palawan Express)",
                lat: 13.413255329056,
                lng: 121.177596791058,
                color: "lightgreen"
            },
            {
                name: "Aboboto St. Barangay San Vicente East, Calapan City, Oriental Mindoro",
                lat: 13.410649523119,
                lng: 121.179892455593,
                color: "lightgreen"
            },
            {
                name: "Del Pilar St., Barangay San Vicente East, Calapan City, Oriental Mindoro",
                lat: 13.411227364177,
                lng: 121.180309201690,
                color: "lightgreen"
            },
            {
                name: "Barangay Lalud, Calapan City, Oriental Mindoro (near phoenix gasoline station)",
                lat: 13.402465071142,
                lng: 121.172008932260,
                color: "lightgreen"
            },
            {
                name: "Barangay San Vicente North, Calapan City, Oriental Mindoro (new public market 1)",
                lat: 13.413478281857,
                lng: 121.178518318201,
                color: "lightgreen"
            },
            {
                name: "Barangay San Vicente North, Calapan City, Oriental Mindoro (new public market 2)",
                lat: 13.413478281857,
                lng: 121.178518318201,
                color: "lightgreen"
            },
            {
                name: "Brgy. Guinobatan (Infront of New City Hall)",
                lat: 13.3787930,
                lng: 121.1825635,
                color: "lightgreen"
            }
        ];

        // Define fire hydrant icon
        const hydrantIcon = L.icon({
            iconUrl: 'https://img.icons8.com/cotton/64/000000/fire-hydrant--v1.png', // Corrected path to the custom icon
            iconSize: [60, 60],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Add fire hydrant markers to the map
        fireHydrants.forEach(hydrant => {
            const marker = L.marker([hydrant.lat, hydrant.lng], {
                icon: hydrantIcon
            }).addTo(map);

            marker.bindPopup(`
    <div>
      <p><strong>${hydrant.name}</strong></p>
      <button onclick="navigateToHydrant(${hydrant.lat}, ${hydrant.lng})">Navigate</button>
    </div>
  `);
        });


        function navigateToHydrant(lat, lng) {
            endCoords = [lng, lat];
            updateRoute();
        }

        let currentStep = "start";
        let startCoords = null;
        let endCoords = null;

        function updateRoute() {
            if (!startCoords || !endCoords) {
                alert("Please select both start and end points.");
                return;
            }

            // Create the arcgis-rest-js authentication object to use later.
            const authentication = arcgisRest.ApiKeyManager.fromKey(apiKey);

            // make the API request
            arcgisRest
                .solveRoute({
                    stops: [startCoords, endCoords],
                    endpoint: "https://route-api.arcgis.com/arcgis/rest/services/World/Route/NAServer/Route_World/solve",
                    authentication
                })

                .then((response) => {

                    routeLines.clearLayers();
                    L.geoJSON(response.routes.geoJson).addTo(routeLines);

                    const directionsHTML = response.directions[0].features.map((f) => f.attributes.text).join("<br/>");
                    directions.innerHTML = directionsHTML;
                    startCoords = null;
                    endCoords = null;

                })

                .catch((error) => {
                    console.error(error);
                    alert("There was a problem using the route service. See the console for details.");
                });

        }

        // Function to get user's geolocation
        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showUserLocation);
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
        }

        // Function to show user's geolocation
        function showUserLocation(position) {
            const userLatitude = position.coords.latitude;
            const userLongitude = position.coords.longitude;

            map.setView([userLatitude, userLongitude], 14); // Set map view to user's location

            const userMarker = L.marker([userLatitude, userLongitude]).addTo(map);
            userMarker.bindPopup("'You are here.' -Rescuer").openPopup();

            // Set user's geolocation as starting point
            startCoords = [userLongitude, userLatitude];
        }

        // Call function to get user's geolocation
        getUserLocation();

        map.on("click", (e) => {
            const coordinates = [e.latlng.lng, e.latlng.lat];

            if (currentStep === "start") {

                startLayerGroup.clearLayers();
                endLayerGroup.clearLayers();
                routeLines.clearLayers();

                L.marker(e.latlng).addTo(startLayerGroup);
                startCoords = coordinates;

                currentStep = "end";

                // Directly calculate the route when the user clicks on the map
                if (startCoords && endCoords) {
                    updateRoute();
                }

            } else {

                L.marker(e.latlng).addTo(endLayerGroup);
                endCoords = coordinates;

                currentStep = "start";

                // Directly calculate the route when the user clicks on the map
                if (startCoords && endCoords) {
                    updateRoute();
                }
            }

        });
    </script>
</body>

</html>