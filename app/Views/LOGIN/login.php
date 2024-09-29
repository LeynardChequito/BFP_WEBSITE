<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP Community Login Form</title>
    <style>
        /* General body styling with a frosted-glass effect */
        body {
            margin: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.7)), url('/bfpcalapancity/public/images/bglog.jpg');
            background-size: cover;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            backdrop-filter: blur(10px);
        }

        /* Glassmorphic container */
        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .bfp-title {
            color: #ffffff;
            font-size: 2em;
            margin-bottom: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .v-text-field {
            width: 90%;
            padding: 12px;
            margin-top: 15px;
            margin-bottom: 10px;
            border: none;
            border-radius: 12px;
            box-shadow: inset 5px 5px 10px rgba(0, 0, 0, 0.1), inset -5px -5px 10px rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.5);
            font-size: 1em;
            color: #000;
        }

        .bfp-btn {
            background: linear-gradient(135deg, #d9534f, #c9302c);
            color: #fff;
            margin-top: 20px;
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            font-size: 1em;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .bfp-btn:hover {
            background: linear-gradient(135deg, #c9302c, #d9534f);
            transform: translateY(-3px);
        }
        .form-label {
            margin-top: 15px;
            color: #fff;
        }
        .bfp-link {
            color: #fff;
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
            border-radius: 12px;
            text-align: center;
            margin-top: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .create-account-btn:hover {
            background-color: #c9302c;
        }

        .show-password {
            display: flex;
            align-items: center;
            margin-top: 15px;
            color: #fff;
        }

        .show-password input {
            margin-right: 5px;
        }

        .alert {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .alert-danger {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: #ff4d4d;
        }

        /* Media query for background image */
        @media screen and (max-width: 420px) {
            .login-card {
                margin-top: 30px;
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

        <form action="<?= site_url('/dologin') ?>" method="POST">
    <?= csrf_field() ?>
    <label for="email" class="form-label">Email:</label>
    <input id="email" type="text" name="email" class="v-text-field" required>
    <br>
    <label for="password" class="form-label">Password:</label>
    <input id="password" type="password" name="password" class="v-text-field" required>
    <div class="show-password">
        <input type="checkbox" id="showPassword"> Show Password
    </div>
    <br>
    <button id="btnLogin" type="submit" class="bfp-btn">Login</button>
</form>

        <a href="<?= site_url('/forgot-password') ?>" class="bfp-link">Forgot Password?</a>
        <a href="<?= site_url('/registration') ?>" class="create-account-btn">Create an Account</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        // Script to toggle password visibility
        document.getElementById('showPassword').addEventListener('change', function () {
            var passwordInput = document.querySelector('input[name="password"]');
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>

</body>

</html>
