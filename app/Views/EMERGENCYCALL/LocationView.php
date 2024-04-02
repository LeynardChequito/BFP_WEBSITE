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

        /* Style for legend */
        .legend {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Style for legend items */
        .legend-item {
            margin-bottom: 5px;
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

        /* Fallback font */
        body {
            font-family: Arial, sans-serif;
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

    <!-- Legend -->
    <div class="legend">
        <div class="legend-item rescuer">
            <span style="background-color: yellow;"></span>Rescuer
        </div>
        <div class="legend-item hydrant">
            <span style="background-color: lightgreen;"></span>Fire Hydrant
        </div>
        <div class="legend-item user">
            <span style="background-color: orange;"></span>User in Need
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
        document.addEventListener("DOMContentLoaded", function () {
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
            var rescuerMarker = new H.map.Marker(
                { lat: 13.3798859, lng: 121.1832902 },
                { icon: createIcon('yellow') }
            );
            rescuerMarker.setData("<div><p>Rescuer</p></div>");
            map.addObject(rescuerMarker); // Adding rescuerMarker to the map
            // Click event for rescuer marker
            rescuerMarker.addEventListener('tap', function (evt) {
                var bubble = new H.ui.InfoBubble(evt.target.getGeometry(), {
                    content: evt.target.getData()
                });
                ui.addBubble(bubble);
            });

            // Add markers for fire hydrants
            var hydrantLocations = [
                {
                    name: "Barangay Bayanan 1, Calapan City, Oriental Mindoro (beside Calapan Waterworks Corp. Compound)",
                    lat: 13.355547541837,
                    lng: 121.170303614926,
                    color: "lightgreen" // Changed color to light green
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
                // Add more hydrant locations here
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


            hydrantLocations.forEach(function (hydrant) {
                var hydrantMarker = new H.map.Marker(
                    { lat: hydrant.lat, lng: hydrant.lng },
                    { icon: createIcon(hydrant.color) }
                );
                hydrantMarker.setData("<div><p>" + hydrant.name + "</p><button onclick='navigateToMarker(" + hydrant.lat + "," + hydrant.lng + ")'>Navigate</button></div>");
                map.addObject(hydrantMarker); // Adding hydrantMarker to the map
                // Click event for hydrant marker
                hydrantMarker.addEventListener('tap', function (evt) {
                    var hydrantData = evt.target.getData();
                    var hydrantInfo = new H.ui.InfoBubble(evt.target.getGeometry(), {
                        content: hydrantData
                    });
                    ui.addBubble(hydrantInfo);
                    // Calculate route from rescuer to the selected marker
                    calculateRoute(map, { lat: 13.3798859, lng: 121.1832902 }, { lat: hydrant.lat, lng: hydrant.lng });
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
                calculateRoute(map, rescuerLocation, { lat: markerLat, lng: markerLng });
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

                            // Add the route polyline to the map and to the routeObjects array:
                            map.addObject(routeLine);
                            routeObjects.push(routeLine);
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

            // Function to clear existing routes
            function clearRoutes() {
                // Remove each route object from the map and empty the routeObjects array
                routeObjects.forEach(function (object) {
                    map.removeObject(object);
                });
                routeObjects = [];
            }
        });
    </script>

</body>

</html>
