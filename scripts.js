document.addEventListener("DOMContentLoaded", function () {
    // Initialize communication with the platform
    const platform = new H.service.Platform({
        apikey: 'Vitn_s6WLkw5GbaUtITnXneLg20TrdDPZP_-qCDb1ok'
    });

    // Function to detect slow network and handle font loading accordingly
    function handleSlowNetwork() {
        if (navigator.connection) {
            const connection = navigator.connection;
            if (connection.saveData || connection.effectiveType === '2g' || connection.downlink < 0.5) {
                console.log("Intervention: Slow network detected. Fallback font will be used while loading.");
                // Modify CSS to specify a fallback font-family or adjust font loading settings dynamically
                document.body.style.fontFamily = "Arial, sans-serif"; // Fallback font
            }
        }
    }

    // Call the function to handle slow network on page load
    handleSlowNetwork();

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

    // Function to create custom icon for markers
    function createIcon(color) {
        var svgMarkup = '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">' +
            '<circle cx="12" cy="12" r="10" fill="' + color + '" stroke="black" stroke-width="2"/>' +
            '</svg>'; // <-- Added closing SVG tag here

        return new H.map.Icon(svgMarkup);
    }

    // Add markers for fire hydrants
    var hydrantLocations = [
        {
            name: "Barangay Bayanan 1, Calapan City, Oriental Mindoro (beside Calapan Waterworks Corp. Compound)",
            lat: 13.355547541837,
            lng: 121.170303614926,
            color: "green" // Changed color to green
        },
        {
            name: "Cor. JP Rizal, Barangay Lalud, Calapan City, Oriental Mindoro (Near LGC)",
            lat: 13.399026784522,
            lng: 121.174347236556,
            color: "green"
        },
        {
            name: "Ubas St., Barangay Lalud, Calapan City, Oriental Mindoro (near Barangay Hall)",
            lat: 13.398536024051,
            lng: 121.175305189208,
            color: "green"
        }
        // Add more hydrant locations here
        // Example: { name: "Location Name", lat: 00.000000, lng: 00.000000, color: "green" },
    ];

    hydrantLocations.forEach(function (hydrant) {
        var hydrantMarker = new H.map.Marker(
            { lat: hydrant.lat, lng: hydrant.lng },
            { icon: createIcon(hydrant.color) }
        );
        hydrantMarker.setData("<div><p>" + hydrant.name + "</p><button onclick='navigateToMarker(" + hydrant.lat + "," + hydrant.lng + ")'>Navigate</button></div>");
        map.addObject(hydrantMarker); // Adding hydrantMarker to the map
        // Click event for hydrant marker
        hydrantMarker.addEventListener('tap', function (evt) {
            var bubble = new H.ui.InfoBubble(evt.target.getGeometry(), {
                content: evt.target.getData()
            });
            ui.addBubble(bubble);
        });
    });

    // Get user's real-time geolocation as the user in need
    function getUserDestination(successCallback, errorCallback) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                successCallback({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });
            }, function (error) {
                errorCallback(error);
            });
        } else {
            console.error("Geolocation is not supported by this browser.");
            // Provide fallback option or notify the user that geolocation is not available
        }
    }

    // Function to handle navigation to marker
    function navigateToMarker(markerLat, markerLng, goToUserInNeed) {
        if (goToUserInNeed) {
            getUserDestination(function (userDestination) {
                calculateRoute({ lat: markerLat, lng: markerLng }, userDestination);
            }, function (error) {
                console.error("Error getting user's location:", error.message);
                // Handle error when geolocation access is denied or not available
                // For example, display a message to the user or use a default location
            });
        } else {
            // Marker for Rescuer
            var rescuerLocation = {
                lat: 13.3798859,
                lng: 121.1832902
            };
            // Calculate route from rescuer to the selected marker
            calculateRoute(rescuerLocation, { lat: markerLat, lng: markerLng });
        }
    }

    // Get user's geolocation for the destination
    getUserDestination(function (userDestination) {
        var userInNeedMarker = new H.map.Marker(userDestination, {
            icon: createIcon("orange")
        });
        userInNeedMarker.setData("<div><p>I need help. Please Help Me!!</p><button onclick='navigateToMarker(" + userDestination.lat + "," + userDestination.lng + ", true)'>Navigate</button></div>");
        map.addObject(userInNeedMarker);

        // Marker for Rescuer
        var rescuerLocation = {
            lat: 13.3798859,
            lng: 121.1832902
        };
        var rescuerMarker = new H.map.Marker(rescuerLocation, {
            icon: createIcon("yellow")
        });
        rescuerMarker.setData("<div><p>Rescuer</p></div>");
        map.addObject(rescuerMarker);

        // Once both rescuer's and user's locations are obtained, calculate the route
        calculateRoute(rescuerLocation, userDestination);
    }, function(error) {
        console.error("Error getting user's location:", error.message);
        // Handle error when geolocation access is denied or not available
        // For example, display a message to the user or use a default location
    });

    // Function to calculate and display route
    function calculateRoute(start, end) {
        // Clear existing routes
        map.removeObjects(map.getObjects());

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

        // Define a callback function to process the routing response:
        var onResult = function (result) {
            // ensure that at least one route was found
            if (result.routes.length) {
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

                    // Add the route polyline to the map:
                    map.addObject(routeLine);
                });
            } else {
                console.error('No route found.');
            }
        };

        // Define an error handler for the routing request:
        var onError = function (error) {
            console.error('Error calculating route:', error);
        };

        // Perform routing calculation
        router.calculateRoute(routingParameters, onResult, onError);
    }
});
