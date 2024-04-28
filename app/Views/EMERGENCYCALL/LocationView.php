To incorporate traffic conditions and display traffic incidents on the map, you can follow these steps:

1. Add the necessary JavaScript library for traffic incidents.
2. Enable traffic layer on the map.
3. Subscribe to traffic events to display traffic incidents.

Here's the corrected code with these modifications:

```html
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>BFP Geolocation</title>
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
    <style>
        /* Reset default margin and padding */
        body {
            margin: 0;
            padding: 0;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
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

        #map-container {
            width: 100%;
            height: 300px;
            /* Adjust height as needed for smaller screens */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        #map {
            width: 100%;
            height: 100%;
        }


        @media only screen and (min-width: 768px) {
            #map-container {
                height: 1500px;
                /* Adjust height as needed for larger screens */
            }
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

        /* Style for the legend */
        .legend {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
        }

        /* Style for legend items */
        .legend-item {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        /* Style for legend item icon */
        .legend-item span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border-radius: 50%;
        }

        /* Style for marker icons */
        .marker-icon {
            width: 24px;
            height: 24px;
            margin-top: -12px;
            margin-left: -12px;
        }

        /* Map attribution */
        .map-attribution {
            position: absolute;
            bottom: 10px;
            right: 20px;
            font-size: 12px;
            color: #666;
        }

        /* Button styles */
        .navigate-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .navigate-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <button onclick="history.go(-1);" class="btn-back">Back</button>
    <div class="bfp-header">
        Bureau of Fire Protection (BFP)
        <div id="philippineTime" class="philippine-time"></div>
    </div>
    <!-- Add elements to display distance and travel time -->
    <div id="distance">Distnace: </div>
    <div id="travelTime">Travel Time: </div>
    <div class="map-card">
        <!-- Map container -->
        <div id="map-container">
            <div id="map"></div>
        </div>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-item rescuer">
                <span style="background-color: yellow;"></span>Rescuer
            </div>
            <div class="legend-item hydrant">
                <span style="background-color: lightgreen;"></span>Fire Hydrant
            </div>
            <div class="legend-item hydrant">
                <span style="background-color: red;"></span>User in Need
            </div>
        </div>

        <!-- Map attribution -->
        <div class="map-attribution">
            Map data &copy; 2024 HERE
        </div>
    </div>

    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-clustering.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize communication with the platform
            const platform = new H.service.Platform({
                apikey: 'Vitn_s6WLkw5GbaUtITnXneLg20TrdDPZP_-qCDb1ok' // Replace with your actual API key
            });

            // Default options for the base layers that are used to render a map
            var defaultLayers = platform.createDefaultLayers();

            // Initialize the map
            var map = new H.Map(document.getElementById('map'),
                defaultLayers.vector.normal.map, {
                    zoom: 14, // Adjusted zoom level
                    center: {


                        lat: 13.3839, // Adjusted center latitude for Calapan City
                        lng: 121.1860 // Adjusted center longitude for Calapan City
                    }
                }
            );

            // Add traffic layer
            var trafficLayer = platform.createDefaultLayers().vector.normal.traffic;
            map.addLayer(trafficLayer);

            // Enable traffic incidents
            var incidentsLayer = platform.getTrafficIncidents({
                zoom: 14,
                viewport: map.getViewModel().getLookAtData().bounds
            });
            map.addLayer(incidentsLayer);

            // add a resize listener to make sure that the map occupies the whole container
            window.addEventListener('resize', () => map.getViewPort().resize());

            // MapEvents enables the event system
            // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
            var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

            // Create the default UI components
            var ui = H.ui.UI.createDefault(map, defaultLayers);

            // Array to store route objects
            var routeObjects = [];

            // Function to create custom icon for markers
            function createIcon(color) {
                var svgMarkup = '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">' +
                    '<circle cx="12" cy="12" r="10" fill="' + color + '" stroke="black" stroke-width="2"/>' +
                    '</svg>'; // <-- Added closing SVG tag here

                return new H.map.Icon(svgMarkup);
            }

            // Add marker for the rescuer
            var rescuerMarker = new H.map.Marker({
                lat: 13.3798859,
                lng: 121.1832902
            }, {
                icon: createIcon('yellow')
            });
            rescuerMarker.setData("<div><p>Rescuer</p></div>");
            map.addObject(rescuerMarker); // Adding rescuerMarker to the map
            // Click event for rescuer marker
            rescuerMarker.addEventListener('tap', function(evt) {
                var bubble = new H.ui.InfoBubble(evt.target.getGeometry(), {
                    content: evt.target.getData()
                });
                ui.addBubble(bubble);
            });

            // Function to get user's geolocation
            function getUserGeolocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    console.log("Geolocation is not supported by this browser.");
                }
            }

            // Function to handle user's geolocation
            function showPosition(position) {
                // Add marker for user in need
                var userMarker = new H.map.Marker({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                }, {
                    icon: createIcon('red') // Red color for user in need
                });
                userMarker.setData("<div><p>Please, Help Me!</p><button class='navigate-button'>Navigate</button></div>");
                map.addObject(userMarker); // Adding userMarker to the map
                // Click event for user marker
                userMarker.addEventListener('tap', function(evt) {
                    var userData = evt.target.getData();
                    var userInfo = new H.ui.InfoBubble(evt.target.getGeometry(), {
                        content: userData
                    });
                    ui.addBubble(userInfo);

                    // Add click event listener to the navigate button dynamically
                    userInfo.getElement().querySelector('.navigate-button').addEventListener('click', function() {
                        navigateToMarker(position.coords.latitude, position.coords.longitude);
                    });
                });
            }

            // Call function to get user's geolocation
            getUserGeolocation();

            // Add markers for fire hydrants
            var hydrantLocations = [{
                    name: "Barangay Bayanan 1, Calapan City, Oriental Mindoro (beside Calapan Waterworks Corp. Compound)",
                    lat: 13.355547541837,
                    lng: 121.170303614926,
                    color: "lightgreen" // Changed color to light green
                },
                // Add more hydrant locations here
            ];

            hydrantLocations.forEach(function(hydrant) {
                var hydrantMarker = new H.map.Marker({
                    lat: hydrant.lat,
                    lng: hydrant.lng
                }, {
                    icon: createIcon(hydrant.color)
                });
                hydrantMarker.setData("<div><p>" + hydrant.name + "</p><button class='navigate-button'>Navigate</button></div>");
                map.addObject(hydrantMarker); // Adding hydrantMarker to the map
                // Click event for hydrant marker
                hydrantMarker.addEventListener('tap', function(evt) {
                    var hydrantData = evt.target.getData();
                    var hydrantInfo = new H.ui.InfoBubble(evt.target.getGeometry(), {
                        content: hydrantData
                    });
                    ui.addBubble(hydrantInfo);

                    // Add click event listener to the navigate button dynamically
                    hydrantInfo.getElement().querySelector('.navigate-button').addEventListener('click', function() {
                        navigate

ToMarker(hydrant.lat, hydrant.lng);
                    });
                });
            });

            // Function to handle navigation to marker
            function navigateToMarker(markerLat, markerLng) {
                // Marker for Rescuer
                var rescuerLocation = {
                    lat: 13.3798859,
                    lng: 121.1832902
                };
                // Calculate route from rescuer to the selected marker
                calculateRoute(map, rescuerLocation, {
                    lat: markerLat,
                    lng: markerLng
                });
            }

            // Function to calculate and display route
            function calculateRoute(map, start, end) {
                // Clear existing routes
                clearRoutes();

                // Get an instance of the routing service version 8:
                var router = platform.getRoutingService(null, 8);

                // Create the parameters for the routing request:
                var routingParameters = {
                    'routingMode': 'fast',
                    'transportMode': 'car',
                    'origin': start.lat + ',' + start.lng,
                    'destination': end.lat + ',' + end.lng,
                    'return': 'polyline'
                };

                // Define the onResult function
                var onResult = function(result) {
                    // Check if result object exists and has routes
                    if (result && result.routes && result.routes.length > 0) {
                        // Check if the first route has sections
                        if (result.routes[0].sections && result.routes[0].sections.length > 0) {
                            // Clear existing routes
                            clearRoutes();

                            // Display the route on the map
                            result.routes[0].sections.forEach((section) => {
                                // Create a linestring to use as a point source for the route line
                                let linestring = H.geo.LineString.fromFlexiblePolyline(section.polyline);

                                // Create a polyline to display the route:
                                let routeLine = new H.map.Polyline(linestring, {
                                    style: {
                                        strokeColor: 'blue',
                                        lineWidth: 5
                                    }
                                });

                                // Add the route polyline to the map and to the routeObjects array:
                                map.addObject(routeLine);
                                routeObjects.push(routeLine);
                            });

                            // Check if route summary is available and complete
                            if (result.routes[0].sections[0].summary && result.routes[0].sections[0].summary.length && result.routes[0].sections[0].summary.duration) {
                                // Display estimated distance and travel time
                                var distance = result.routes[0].sections[0].summary.length / 1000; // in kilometers
                                var travelTime = result.routes[0].sections[0].summary.duration / 60; // in minutes
                                document.getElementById('distance').textContent = 'Estimated Distance: ' + distance.toFixed(2) + ' km';
                                document.getElementById('travelTime').textContent = 'Estimated Travel Time: ' + travelTime.toFixed(2) + ' minutes';
                            } else {
                                console.error('Route summary is undefined or incomplete:', result);
                                alert('Route information is unavailable or incomplete. Please try again.');
                            }
                        } else {
                            console.error('Route sections are missing or empty:', result);
                            alert('Route sections are missing or empty. Please try again.');
                        }
                    } else {
                        console.error('No routes found in the result object:', result);
                        alert('No routes found in the result object. Please try again.');
                    }
                };
                // Define an error handler for the routing request:
                var onError = function(error) {
                    console.error('Error calculating route:', error);
                    alert('Error calculating route. Please try again.');
                };

                // Perform routing calculation
                router.calculateRoute(routingParameters, onResult, onError);
            }

            // Function to clear existing routes
            function clearRoutes() {
                // Remove each route object from the map and empty the routeObjects array
                routeObjects.forEach(function(object) {
                    map.removeObject(object);
                });
                routeObjects = [];
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Function to get current time in Philippine Standard Time
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

            // Start updating time
            updateTime();
        });
    </script>
</body>

</html>