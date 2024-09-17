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

<!-- Modal for notifications -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="newReportModalLabel" aria-hidden="true">
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



<audio id="sirenSound" src="bfpcalapancity/public/45secs_alarm.mp3" preload=""></audio>


<?= view('EMERGENCYCALL/MapScript'); ?>
    
</body>

</html>
