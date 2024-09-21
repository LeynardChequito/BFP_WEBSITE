<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        .alert,
        .alert-danger {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: #fff;
            padding: 10px;
            border-radius: 10px;
            margin-top: 15px;
            text-align: left;
        }

        .v-text-field:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(217, 83, 79, 0.5);
        }

        .form-label {
            font-weight: bold;
            font-size: 1.1em;
            text-align: left;
            color: #fff;
            margin-top: 10px;
        }

        @media screen and (max-width: 420px) {
            .login-card {
                margin-top: 50px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <h2 class="bfp-title">Forgot Password</h2>

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

        <form action="<?= site_url('/forgot-password') ?>" method="post">
            <label for="email" class="form-label">Email:</label>
            <input id="email" type="email" name="email" class="v-text-field" required placeholder="Enter your email address">
            <button type="submit" class="bfp-btn">Submit</button>
        </form>
    </div>
</body>

</html>
