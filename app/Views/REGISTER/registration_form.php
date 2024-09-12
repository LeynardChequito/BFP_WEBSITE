<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP Community Registration Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: 1px solid #d9534f;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 5px;
        }

        .card-header {
            background-color: #d9534f;
            color: #fff;
            text-align: center;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            margin-left: 20px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d9534f;
            border-radius: 5px;
            height: 45px;
        }

        .btn-primary {
            background-color: #d9534f;
            border: 1px solid #d9534f;
            border-radius: 5px;
            color: #fff;
            padding: 12px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }

        .btn-primary:hover {
            background-color: #c9302c;
            border: 1px solid #ac2925;
        }

        .form-check-label {
            font-size: 0.9em;
        }

        .text-body-1 {
            margin-bottom: 0;
        }

        .link-login {
            color: #007bff;
            text-decoration: underline;
            cursor: pointer;
            margin-left: 15px;
        }

        .text-danger {
            color: #dc3545;
            font-size: 80%;
        }

        .show-password {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #dc3545;
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
                                <input type="text" name="fullName" class="form-control" value="<?= set_value('fullName') ?>" required>
                                <?php if (isset($validation) && $validation->getError('fullName')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('fullName')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="dob" class="font-weight-bold">Date of Birth:</label>
                                <input type="date" name="dob" class="form-control" value="<?= set_value('dob') ?>" required>
                                <?php if (isset($validation) && $validation->getError('dob')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('dob')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="address" class="font-weight-bold">Home Address:</label>
                                <textarea name="address" class="form-control" required><?= set_value('address') ?></textarea>
                                <?php if (isset($validation) && $validation->getError('address')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('address')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="phoneNumber" class="font-weight-bold">Phone Number:</label>
                                <input type="tel" name="phoneNumber" class="form-control" value="<?= set_value('phoneNumber') ?>" required>
                                <?php if (isset($validation) && $validation->getError('phoneNumber')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('phoneNumber')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="email" class="font-weight-bold">Email Address (Active):</label>
                                <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" required>
                                <?php if (isset($validation) && $validation->getError('email')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('email')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="password" class="font-weight-bold">Password:</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" required>
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
                                    <option value="male" <?= (set_value('sex') === 'male') ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= (set_value('sex') === 'female') ? 'selected' : '' ?>>Female</option>
                                </select>
                                <?php if (isset($validation) && $validation->getError('sex')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('sex')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                               <label for="photoIdPath" class="font-weight-bold">Upload Any Valid ID:</label>
                               <input type="file" name="photoIdPath" accept="image/*" class="form-control-file" required>
                               <?php if (isset($validation) && $validation->getError('photoIdPath')) { ?>
                                   <div class="text-danger"><?= esc($validation->getError('photoIdPath')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                               <label for="profilePhotoPath" class="font-weight-bold">Upload Your Formal Photo:</label>
                               <input type="file" id="profilePhotoPath" name="profilePhotoPath" accept="image/*" class="form-control-file" required>
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
