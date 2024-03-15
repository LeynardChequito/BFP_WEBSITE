<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP Security Page</title>
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@0.22.0/dist/face-api.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        #header {
            background-color: #c62828;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        h1 {
            margin: 0;
            font-size: 32px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #facialRecognitionSection,
        #fireIncidentSection,
        #userInfoSection {
            margin-bottom: 20px;
        }

        button {
            background-color: #c62828;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        button:hover {
            background-color: #b71c1c;
        }

        #video,
        #overlay,
        #fireImage {
            width: 100%;
            max-width: 640px;
            height: auto;
            display: block;
            margin: 10px auto;
            border-radius: 5px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div id="header">
        <h1>BFP Security Page</h1>
    </div>
    <a href="javascript:history.go(-1);" class="btn btn-danger btn-back">Back</a>
    <div class="container">
        <!-- Facial Recognition Section -->
        <div id="facialRecognitionSection">
            <video id="video" width="640" height="480" autoplay></video>
            <canvas id="overlay" width="640" height="480"></canvas>
            <button onclick="startFacialRecognition()">Start Facial Recognition</button>
        </div>

        <!-- Fire Incident Capture Section -->
        <div id="fireIncidentSection" style="display: none;">
            <img id="fireImage" alt="Fire Incident Image">
            <button onclick="captureFireIncident()">Capture Fire Incident</button>
        </div>

        <!-- User Information Section -->
        <div id="userInfoSection" style="display: none;">
            <h2>User Information</h2>
            <p>Name: <span id="userName"></span></p>
            <p>Location: <span id="userLocation"></span></p>
            <textarea id="additionalInfo" placeholder="Optional Message"></textarea>
            <button onclick="sendToAdminDashboard()">Send to Admin Dashboard</button>
        </div>
    </div>

    <script>
        async function startFacialRecognition() {
            const video = document.getElementById('video');
            const overlay = document.getElementById('overlay');

            // Access the user's camera
            const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
            video.srcObject = stream;

            // Load face-api.js
            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');

            // Start face recognition
            video.addEventListener('play', () => {
                const canvas = faceapi.createCanvasFromMedia(video);
                document.body.append(canvas);
                const displaySize = { width: video.width, height: video.height };
                faceapi.matchDimensions(canvas, displaySize);

                setInterval(async () => {
                    const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptors();

                    const resizedDetections = faceapi.resizeResults(detections, displaySize);
                    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                    faceapi.draw.drawDetections(canvas, resizedDetections);
                    faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
                }, 100);
            });
        }

        function captureFireIncident() {
            // Implement logic to capture fire incident using the device camera
            // Update the UI with the captured image, e.g., show fireImage
        }

        function sendToAdminDashboard() {
            // Dummy data for testing
            const userName = 'John Doe';
            const userLocation = 'Dummy Location';
            const additionalInfo = document.getElementById('additionalInfo').value;

            // You can now send this data to the admin dashboard using AJAX or another method
            console.log('User Information:', { userName, userLocation, additionalInfo });
        }
    </script>
</body>

</html>
