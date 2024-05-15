<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        @media only screen and (max-width: 768px) {
            #map-container {
                height: 400px; /* Adjust height for smaller screens */
            }
            .map-card {
                width: 95%; /* Adjust width for smaller screens */
            }
            .legend {
                top: 10px;
                left: 10px;
            }
        }
        /* Adjustments for smartphones */
        @media only screen and (max-width: 480px) {
            #map-container {
                height: 300px; /* Adjust height for smartphones */
            }
            .map-card {
                width: 100%; /* Adjust width for smartphones */
            }
            .legend {
                top: 5px;
                left: 5px;
            }
            .btn-back {
                top: 10px;
                left: 10px;
            }
            .bfp-header {
                font-size: 20px; /* Decrease font size for smartphones */
            }
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
        <div id="route-info">
            <div id="total-distance">Total Distance: </div>
            <div id="travel-time">Travel Time: </div>
            <div id="directions">Directions: </div>
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

    <script>
        // Initialize HERE Map
        var platform = new H.service.Platform({
            'apikey': 'Vitn_s6WLkw5GbaUtITnXneLg20TrdDPZP_-qCDb1ok' // Replace with your own API key
        });

        var defaultLayers = platform.createDefaultLayers();

        // Create a map object
        var map = new H.Map(
            document.getElementById('map'),
            defaultLayers.vector.normal.map, // Use vector map tiles
            {
                zoom: 14,
                center: { lat: 13.3839, lng: 121.1860 } // Calapan City coordinates
            });

        // Enable map interaction (pan, zoom, etc.) with the map events
        var mapEvents = new H.mapevents.MapEvents(map);
        var behavior = new H.mapevents.Behavior(mapEvents);

        // Enable the map UI components
        var ui = H.ui.UI.createDefault(map, defaultLayers);

        // Add a marker for the BFP location
        var bfpMarker = new H.map.Marker({ lat: 14.5995, lng: 120.9842 });
        map.addObject(bfpMarker);

        // Define the coordinates for the rescuer
        var rescuerCoords = { lat: 14.6091, lng: 120.9796 };

        // Add a marker for the rescuer
        var rescuerMarker = new H.map.Marker(rescuerCoords, {
            icon: new H.map.Icon('https://via.placeholder.com/24/ffff00/000000')
        });
        map.addObject(rescuerMarker);

        // Define the coordinates for the fire hydrant
        var hydrantCoords = [{
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

        // Add a marker for the fire hydrant
        var hydrantMarker = new H.map.Marker(hydrantCoords, {
            icon: new H.map.Icon('https://via.placeholder.com/24/00ff00/000000')
        });
        map.addObject(hydrantMarker);

        // Add markers for multiple fire hydrants
hydrantCoords.forEach(function(coord) {
    var marker = new H.map.Marker({ lat: coord.lat, lng: coord.lng }, {
        icon: new H.map.Icon('https://via.placeholder.com/24/' + coord.color + '/000000')
    });
    map.addObject(marker);

    // Add info bubble for each marker
    var bubble = new H.ui.InfoBubble({ lat: coord.lat, lng: coord.lng }, {
        content: '<div>' + coord.name + '</div>'
    });
    ui.addBubble(bubble);
});


        // Calculate the distance and travel time between the BFP and the rescuer
        var router = platform.getRoutingService(null, 8),
            routeRequestParams = {
                mode: 'fastest;car',
                waypoint0: 'geo!' + bfpMarker.getGeometry().lat + ',' + bfpMarker.getGeometry().lng, // BFP coordinates
                waypoint1: 'geo!' + rescuerMarker.getGeometry().lat + ',' + rescuerMarker.getGeometry().lng, // Rescuer coordinates
                representation: 'display'
            };

        router.calculateRoute(
            routeRequestParams,
            onSuccess,
            onError
        );

        // Define a callback function for the route calculation success
        function onSuccess(result) {
            var route = result.response.route[0];
            var distance = route.summary.distance;
            var travelTime = route.summary.travelTime;
            document.getElementById('distance').innerText = 'Distance: ' + distance / 1000 + ' km';
            document.getElementById('travelTime').innerText = 'Travel Time: ' + travelTime / 60 + ' mins';
        }

        // Define a callback function for the route calculation error
        function onError(error) {
            console.error('Route calculation error:', error);
        }
    </script>
</body>
</html>
