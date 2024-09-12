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
        L.marker([hydrant.lat, hydrant.lng], {
            icon: hydrantIcon
        }).addTo(map);
    });

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
        startCoords = [rescuerLongitude, rescuerLatitude]; // Ensure startCoords is set here
    }

    getRescuerLocation();

    // Fetch and display recent reports
    async function getRecentReports() {
        try {
            const response = await fetch('https://bfpcalapancity.online/reports-recent');
            const data = await response.json();

            if (response.ok && Array.isArray(data)) {
                const newReportsList = document.getElementById('newReportsList');
                let newReportsReceived = false;

                newReportsList.innerHTML = '';

                data.forEach(report => {
                    if (report.latitude && report.longitude) {
                        newReportsReceived = true;
                        // Handle report details...
                    }
                });

                if (newReportsReceived) {
                    sirenSound.play().catch(error => {
                        console.error('Error playing sound:', error);
                    });
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

    getRecentReports(); // Fetch new reports on mount
});
</script>
