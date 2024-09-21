<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP Community Registration Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.7)), url('/bfpcalapancity/public/images/bglog.jpg');
            background-size: cover;
            font-family: 'Arial', sans-serif;
            color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
            border-radius: 15px;
        }

        .card-header {
            color: #ffffff;
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px 15px 50px 50px;
            font-weight: bold;
            letter-spacing: 1px;
            font-size: 1.8em;
        }

        .card-body {
            padding: 30px;
            color: #fff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            height: 5%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            box-shadow: inset 5px 5px 10px rgba(0, 0, 0, 0.1), inset -5px -5px 10px rgba(255, 255, 255, 0.1);
        }

        .form-control::placeholder {
            color: #b5b5b5;
        }

        .btn-primary {
            background-color: #d9534f;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #c9302c;
            transform: translateY(-3px);
        }

        .form-check-label {
            font-size: 0.9em;
            color: #fff;
        }

        .text-body-1 {
            margin-bottom: 0;
            color: #f0f0f0;
        }

        .link-login {
            color: #00b0ff;
            text-decoration: underline;
            cursor: pointer;
        }

        .text-danger {
            color: #ff4d4d;
            font-size: 80%;
        }

        .input-group-text {
            background-color: transparent;
            border: none;
            color: #ff4d4d;
        }

        .alert {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .alert-success {
            color: #28a745;
        }

        .alert-danger {
            color: #dc3545;
        }

        .form-control-file {
            border: none;
            color: #fff;
        }

        /* Custom checkbox design */
        .form-check-input {
            width: 20px;
            height: 20px;
            background-color: transparent;
            border: 2px solid #d9534f;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .form-check-input:checked {
            background-color: #d9534f;
            border-color: #c9302c;
        }

        /* Add animations for interactions */
        .form-control:focus,
        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        /* Media Queries for Mobile Responsiveness */
        @media screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .card-body {
                padding: 20px;
                border-radius: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h2 class="font-weight-bold">BFP Community Registration Form</h2>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('registration/processForm') ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="fullName" class="font-weight-bold">Full Name:</label>
                                <input type="text" name="fullName" class="form-control" placeholder="Enter your full name" value="<?= set_value('fullName') ?>" required>
                                <?php if (isset($validation) && $validation->getError('fullName')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('fullName')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="dob" class="font-weight-bold">Date of Birth:</label>
                                <input type="date" name="dob" class="form-control" placeholder="Select your date of birth" value="<?= set_value('dob') ?>" required>
                                <?php if (isset($validation) && $validation->getError('dob')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('dob')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="address" class="font-weight-bold">Home Address:</label>
                                <textarea name="address" class="form-control" placeholder="Enter your home address" required><?= set_value('address') ?></textarea>
                                <?php if (isset($validation) && $validation->getError('address')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('address')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="phoneNumber" class="font-weight-bold">Phone Number:</label>
                                <input type="tel" name="phoneNumber" class="form-control" placeholder="Enter your phone number" value="<?= set_value('phoneNumber') ?>" required>
                                <?php if (isset($validation) && $validation->getError('phoneNumber')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('phoneNumber')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="email" class="font-weight-bold">Email Address:</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email address" value="<?= set_value('email') ?>" required>
                                <?php if (isset($validation) && $validation->getError('email')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('email')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="password" class="font-weight-bold">Password:</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <input type="checkbox" id="showPassword"> Show
                                        </span>
                                    </div>
                                </div>
                                <?php if (isset($validation) && $validation->getError('password')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('password')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="sex" class="font-weight-medium">Sex:</label>
                                <select name="sex" class="form-control" required>
                                    <option value="" disabled selected>Select your gender</option>
                                    <option value="male" <?= (set_value('sex') === 'male') ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= (set_value('sex') === 'female') ? 'selected' : '' ?>>Female</option>
                                </select>
                                <?php if (isset($validation) && $validation->getError('sex')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('sex')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                               <label for="photoIdPath" class="font-weight-bold">Upload Any Valid ID:</label>
                               <input type="file" name="photoIdPath" class="form-control-file" placeholder="Upload a valid ID" accept="image/*" required>
                               <?php if (isset($validation) && $validation->getError('photoIdPath')) { ?>
                                   <div class="text-danger"><?= esc($validation->getError('photoIdPath')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                               <label for="profilePhotoPath" class="font-weight-bold">Upload Your Formal Photo:</label>
                               <input type="file" id="profilePhotoPath" name="profilePhotoPath" class="form-control-file" placeholder="Upload your profile photo" accept="image/*" required>
                               <?php if (isset($validation) && $validation->getError('profilePhotoPath')) { ?>
                                   <div class="text-danger"><?= esc($validation->getError('profilePhotoPath')) ?></div>
                               <?php } ?>
                            </div>

                            <div class="form-group">
                                <label class="form-check-label">
                                    <input type="checkbox" name="permission" class="form-check-input" required>
                                    I hereby grant permission for real-time location tracking and giving real image/video via my smartphone during emergencies, acknowledging its use as a witness to a fire incident.
                                </label>
                                <?php if (isset($validation) && $validation->getError('permission')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('permission')) ?></div>
                                <?php } ?>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>

                            <div class="form-group">
                                <p class="text-body-1">Already have an account? <a class="link-login" href="<?= site_url('login') ?>">Login</a></p>
                            </div>
                        </form>

                        <?php if (session()->has('success')) : ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <?= session('success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($validation) && $validation->getErrors()) : ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                Please check the form for errors.
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('showPassword').addEventListener('change', function () {
            var passwordInput = document.getElementById('password');
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>

</body>

</html>
