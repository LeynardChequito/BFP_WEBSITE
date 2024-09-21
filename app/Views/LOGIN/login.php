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
            background-image: url('/bfpcalapancity/public/images/bglog.jpg');
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
                background-image: url('/bfpcalapancity/public/images/bglog.jpg');
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
        <!-- Updated forgot password redirection -->
        <a href="<?= site_url('/forgot-password') ?>" class="bfp-link">Forgot Password?</a>
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
    </script>

</body>

</html>
