<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP Community Login Form</title>
    <style>
        /* Default styles */

        body {
            margin: 0;
            background-image: url('bfpcalapancity/public/images/bglog.jpg');
            background-size: cover;
            font-family: 'Arial', sans-serif;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            text-align: center;
            position: relative;
            background: linear-gradient(to bottom, #ffffff, #f0f0f0);
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            z-index: 1;
        }

        .bfp-title {
            color: #d9534f;
            font-size: 1.8em;
            margin-top: 10px;
        }

        .v-text-field {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: 1px solid #d9534f;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .bfp-btn {
            background-color: #d9534f;
            color: #fff;
            margin-top: 20px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .bfp-btn:hover {
            background-color: #c9302c;
        }

        .bfp-link {
            color: #d9534f;
            cursor: pointer;
            margin-top: 15px;
            display: inline-block;
            text-decoration: underline;
            font-size: 1em;
        }

        .create-account-btn {
            text-decoration: none;
            display: block;
            width: 90%;
            background-color: #d9534f;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .create-account-btn:hover {
            background-color: #c9302c;
        }

        .show-password {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }

        .show-password input {
            margin-right: 5px;
        }

        .alert {
            background-color: #dff0d8;
            border-color: #d6e9c6;
            color: #3c763d;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .alert-danger {
            background-color: #f2dede;
            border-color: #ebccd1;
            color: #a94442;
        }

        /* Media query for background image */
        @media screen and (max-width: 420px) {
            body {
                background-image: none;
                /* Remove background image */
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h2 class="bfp-title">Bureau of Fire Protection</h2>

        <?php if (session()->has('success')) : ?>
            <div class="alert">
                <?= session('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <form>
            <label for="email">Email:</label>
            <input id="email" type="text" name="email" class="v-text-field" required>
            <br>
            <label for="password">Password:</label>
            <input id="password" type="password" name="password" class="v-text-field" required>
            <div class="show-password">
                <input type="checkbox" id="showPassword"> Show Password
            </div>
            <br>
            <button id="btnLogin" type="submit" class="bfp-btn">Login</button>
        </form>
        <p class="bfp-link" onclick="goToForgotPassword()">Forgot Password?</p>
        <a href="<?= site_url('/registration') ?>" class="create-account-btn">Create an Account</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        // Script to toggle password visibility
        document.getElementById('showPassword').addEventListener('change', function() {
            var passwordInput = document.querySelector('input[name="password"]');
            passwordInput.type = this.checked ? 'text' : 'password';
        });

        // Function to handle forgot password link
        function goToForgotPassword() {
            // Add your logic to redirect or handle the forgot password action
            console.log('Redirect to forgot password page');
        }
    </script>

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

        fcm.getToken({
            vapidKey: 'BNEXDb7w8VzvQt3rD2pMcO4vnJ4Q5pBRILpb3WMtZ3PSfoFpb6CmI5p05Gar3Lq1tDQt5jC99tLo9Qo3Qz7_aLc'
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

        document.getElementById('btnLogin').addEventListener('click', function(event) {
            event.preventDefault();

            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;

            fetch('dologin', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        token: mToken
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.status === '1') {
                        window.location.href = 'home';
                    } else {
                        alert(data.message);
                        // Optionally, you can reload the page to show flash data set in the session
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Login error:', error);
                });
        });

        function goToForgotPassword() {
            console.log('Redirect to forgot password page');
        }
    </script>
</body>

</html>