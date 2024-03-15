<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Receiver</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .notification {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        .notification h2 {
            font-size: 20px;
            margin-top: 0;
            color: #333;
        }

        .notification p {
            color: #666;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notification Receiver</h1>

        <div id="notificationContainer" class="message"></div>
        <div>Device Token: <span id="deviceToken"></span></div>
    </div>


    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>

    <script type="module">

        const firebaseConfig = {
            apiKey: "AIzaSyAiXnOQoNLOxLWEAw5h5JOTJ5Ad8Pcl6R8",
            authDomain: "pushnotifbfp.firebaseapp.com",
            projectId: "pushnotifbfp",
            storageBucket: "pushnotifbfp.appspot.com",
            messagingSenderId: "214092622073",
            appId: "1:214092622073:web:fbcbcb035161f7110c1a28",
            measurementId: "G-XMBH6JJ3M6"
        };

firebase.initializeApp(firebaseConfig);
const fcm = firebase.messaging()
let mToken;

fcm.getToken({ vapidKey: 'BNEXDb7w8VzvQt3rD2pMcO4vnJ4Q5pBRILpb3WMtZ3PSfoFpb6CmI5p05Gar3Lq1tDQt5jC99tLo9Qo3Qz7_aLc' 
    }).then((currentToken) => {
        console.log('Token retrieved:', currentToken);
        mToken = currentToken;
    });

   
    

    fcm.onMessage((data) => {
    console.log('onMessage: ', data)

 Notification.requestPermission((status) => {
            console.log('requestPermission:', status);
            if (status === 'granted') {
                let title = data['data']['title'];
                let body = data['data']['body'];
                new Notification(title, {
                    body: body
                });
            }
        });
    });

    $(document).ready(function() {
        $('#btnLogin').on('click', function() {
            $('#btnLogin').attr('disabled', 'disabled');

            let email = $('#email').val();
            let password = $('#password').val();

            $.ajax({
                url: '<?= base_url('dologin') ?>',
                type: 'POST',
                data: {
                    email: email,
                    password: password,
                    token: mToken,
                },
                success: function(res) {
                    console.log(res);
                    
                },
                error: function(err) {
                    console.error('Login error:', res);
                }
            });
        });
    });

        
    </script>
</body>
</html>