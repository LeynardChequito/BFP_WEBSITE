<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>BFP Geolocation</title>
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
    <style>
        /* Define the map container's size */
        #map {
            width: 100%;
            height: 600px;
        }

        /* Style for info panel */
        .info-panel {
            position: absolute;
            top: 1em;
            right: 1em;
            background-color: #f9f9f9;
            padding: 1em;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: 'Roboto', sans-serif;
            z-index: 1000;
        }

        /* Style for info panel content */
        .info-panel p {
            margin: 0.5em 0;
        }

        .info-panel strong {
            font-weight: bold;
        }

        /* Style for BFP header */
        .bfp-header {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-top: 1em;
            color: #333;
            font-family: 'Montserrat', sans-serif;
        }

        /* Legend style */
        .legend {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: 'Roboto', sans-serif;
            z-index: 1000;
        }

        .legend-item {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .legend-item span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border-radius: 50%;
        }

        .legend-item.rescuer span {
            background-color: red;
        }

        .legend-item.hydrant span {
            background-color: blue;
        }

        .legend-item.user span {
            background-color: grey;
        }

        /* Style for travel mode selector */
        #travel-mode-select {
            margin-bottom: 10px;
            text-align: center;
        }

        /* Style for select dropdown */
        select {
            padding: 8px 20px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 5px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #fff;
            color: #333;
            cursor: pointer;
        }

        /* Style for select dropdown arrow */
        select:after {
            content: '\25BC';
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            pointer-events: none;
        }

        /* Style for select dropdown on hover */
        select:hover {
            border-color: #aaa;
        }

        /* Style for select dropdown on focus */
        select:focus {
            outline: none;
            border-color: #555;
        }

        /* Style for select dropdown options */
        option {
            padding: 8px 20px;
        }

        /* Style for map attribution */
        .map-attribution {
            position: absolute;
            bottom: 5px;
            right: 5px;
            font-size: 12px;
            color: #555;
            font-family: 'Roboto', sans-serif;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <!-- BFP Header -->
    <div class="bfp-header">
        Bureau of Fire Protection (BFP)
    </div>

    <!-- Map container -->
    <div id="map"></div>

    <!-- Info panel -->
    <div class="info-panel">
        <p>Rescuer's Geolocation</p>
        <p><strong>Latitude:</strong> <span id="rescuer-lat">13.3798859</span></p>
        <p><strong>Longitude:</strong> <span id="rescuer-lng">121.1832902</span></p>
    </div>

    <!-- Travel mode selector -->
    <div id="travel-mode-select">
        <select id="mode">
            <option value="car">Driving</option>
            <option value="pedestrian">Walking</option>
        </select>
    </div>

    <!-- Legend -->
    <div class="legend">
        <div class="legend-item rescuer">
            <span></span>Rescuer
        </div>
        <div class="legend-item hydrant">
            <span></span>Fire Hydrant
        </div>
        <div class="legend-item user">
            <span></span>User in Need
        </div>
    </div>

    <!-- Map attribution -->
    <div class="map-attribution">
        Map data &copy; 2024 HERE
    </div>

    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-clustering.js"></script>
    <script>
        // Initialize communication with the platform
        const platform = new H.service.Platform({
            apikey: 'Vitn_s6WLkw5GbaUtITnXneLg20TrdDPZP_-qCDb1ok'
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

        // add a resize listener to make sure that the map occupies the whole container
        window.addEventListener('resize', () => map.getViewPort().resize());

        // MapEvents enables the event system
        // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
        var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

        // Create the default UI components
        var ui = H.ui.UI.createDefault(map, defaultLayers);

        var group = new H.map.Group();
        map.addObject(group);

        group.addEventListener('tap', function(evt) {

            var bubble = new H.ui.InfoBubble(evt.target.getGeometry(), {
                content: evt.target.getData()
            });

            ui.addBubble(bubble);
        }, false);

        // Get user's geolocation for the destination
        function getUserDestination(callback) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    callback({
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    });
                });
            } else {
                console.error("Geolocation is not supported by this browser.");
            }
        }

        // Temporary rescuer's geolocation coordinates
        var rescuerLocation;

        // Get user's geolocation for the destination
        getUserDestination(function(userDestination) {
            var userInNeedMarker = new H.map.Marker(userDestination, {
                icon: createUserIcon()
            });
            userInNeedMarker.setData("<div><p>User in Need</p></div>");
            group.addObject(userInNeedMarker);

            rescuerLocation = {
                lat: 13.3798859,
                lng: 121.1832902
            };

            // Marker for Rescuer
            var rescuerMarker = new H.map.Marker(rescuerLocation, {
                icon: createRescuerIcon()
            });
            rescuerMarker.setData("<div><p>Rescuer</p></div>");
            group.addObject(rescuerMarker);

            // Click event for rescuer marker
            rescuerMarker.addEventListener('tap', function(evt) {
                var bubble = new H.ui.InfoBubble(evt.target.getGeometry(), {
                    content: evt.target.getData()
                });
                ui.addBubble(bubble);
                rescuerMarker.setIcon(createRescuerClickedIcon());
            });

            // Click event for user in need marker
            userInNeedMarker.addEventListener('tap', function(evt) {
                var bubble = new H.ui.InfoBubble(evt.target.getGeometry(), {
                    content: evt.target.getData()
                });
                ui.addBubble(bubble);
                userInNeedMarker.setIcon(createUserClickedIcon());
            });

            // Get an instance of the routing service version 8:
            var router = platform.getRoutingService(null, 8);

            // Create the parameters for the routing request:
            var routingParameters = {
                'routingMode': 'fast',
                'transportMode': 'car',
                'origin': userDestination.lat + ',' + userDestination.lng, // Origin is the user in need
                'destination': rescuerLocation.lat + ',' + rescuerLocation.lng, // Destination is the rescuer
                'return': 'polyline'
            };

            // Define a callback function to process the routing response:
            var onResult = function(result) {
                // ensure that at least one route was found
                if (result.routes.length) {
                    result.routes[0].sections.forEach((section) => {
                        // Create a linestring to use as a point source for the route line
                        let linestring = H.geo.LineString.fromFlexiblePolyline(section.polyline);

                        // Create a polyline to display the route:
                        let routeLine = new H.map.Polyline(linestring, {
                            style: {
                                strokeColor: 'navy',
                                lineWidth: 3
                            }
                        });

                        // Create a marker for the start point:
                        let startMarker = new H.map.Marker(section.departure.place.location);

                        // Create a marker for the end point:
                        let endMarker = new H.map.Marker(section.arrival.place.location);

                        // Add the route polyline and the two markers to the map:
                        map.addObjects([routeLine, startMarker, endMarker]);
                    });
                } else {
                    console.error('No route found.');
                }
            };

            // Define an error handler for the routing request:
            var onError = function(error) {
                console.error('Error calculating route:', error);
            };

            // Perform routing calculation
            router.calculateRoute(routingParameters, onResult, onError);
        });

        // Fire hydrant locations
        var hydrantLocations = [{
                name: "Barangay Bayanan 1, Calapan City, Oriental Mindoro (beside Calapan Waterworks Corp. Compound)",
                lat: 13.355547541837,
                lng: 121.170303614926
            },
            {
                name: "Cor. JP Rizal, Barangay Lalud, Calapan City, Oriental Mindoro (Near LGC)",
                lat: 13.399026784522,
                lng: 121.174347236556
            },
            {
                name: "Ubas St., Barangay Lalud, Calapan City, Oriental Mindoro (near Barangay Hall)",
                lat: 13.398536024051,
                lng: 121.175305189208
            }
            // Add more hydrant locations here
            // Example: { name: "Location Name", lat: 00.000000, lng: 00.000000 },
        ];

        // Add markers for each fire hydrant
        hydrantLocations.forEach(function(location) {
            var hydrantMarker = new H.map.Marker({
                lat: location.lat,
                lng: location.lng
            }, {
                icon: createHydrantIcon()
            });
            hydrantMarker.setData("<div><p><strong>Fire Hydrant:</strong> " + location.name + "</p></div>");
            group.addObject(hydrantMarker);

            // Add event listener to the fire hydrant marker
            hydrantMarker.addEventListener('tap', function(evt) {
                var bubble = new H.ui.InfoBubble(evt.target.getGeometry(), {
                    content: evt.target.getData()
                });
                ui.addBubble(bubble);

                if (rescuerLocation) {
                    // Calculate route from rescuer to fire hydrant
                    var routingParams = {
                        'routingMode': 'fast',
                        'transportMode': 'car',
                        'origin': rescuerLocation.lat + ',' + rescuerLocation.lng,
                        'destination': location.lat + ',' + location.lng,
                        'return': 'polyline'
                    };

                    router.calculateRoute(routingParams, onResult, onError);
                } else {
                    console.error('Rescuer location not available.');
                }
            });
        });

        // Function to create custom icon for fire hydrant marker
        function createHydrantIcon() {
            var svgMarkup = '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">' +
                '<rect stroke="black" fill="blue" x="1" y="1" width="22" height="22" />' +
                '</svg>';

            return new H.map.Icon(svgMarkup);
        }

        // Function to create custom icon for rescuer marker
        function createRescuerIcon() {
            var svgMarkup = '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">' +
                '<circle cx="12" cy="12" r="10" fill="red" stroke="black" stroke-width="2"/>' +
                '</svg>';

            return new H.map.Icon(svgMarkup);
        }

        // Function to create custom icon for clicked rescuer marker
        function createRescuerClickedIcon() {
            var svgMarkup = '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">' +
                '<circle cx="12" cy="12" r="10" fill="orange" stroke="black" stroke-width="2"/>' +
                '</svg>';

            return new H.map.Icon(svgMarkup);
        }

        // Function to create custom icon for user in need marker
        function createUserIcon() {
            var svgMarkup = '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">' +
                '<circle cx="12" cy="12" r="10" fill="grey" stroke="black" stroke-width="2"/>' +
                '</svg>';

            return new H.map.Icon(svgMarkup);
        }

        // Function to create custom icon for clicked user in need marker
        function createUserClickedIcon() {
            var svgMarkup = '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">' +
                '<circle cx="12" cy="12" r="10" fill="orange" stroke="black" stroke-width="2"/>' +
                '</svg>';

            return new H.map.Icon(svgMarkup);
        }
    </script>
</body>

</html>
