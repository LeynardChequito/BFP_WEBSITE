<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration Form</title>
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
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d9534f;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #d9534f;
            border: 1px solid #d9534f;
            border-radius: 5px;
            color: #fff;
            padding: 12px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
                        <h2 class="font-weight-bold">Admin Registration Form</h2>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('admin-registration/processForm') ?>" method="post" enctype="multipart/form-data">
                            <!-- Full Name -->
                            <div class="form-group">
                                <label for="first_name" class="font-weight-bold">First Name:</label>
                                <input type="text" name="first_name" class="form-control" value="<?= set_value('first_name') ?>" required>
                                <?php if (isset($validation) && $validation->getError('first_name')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('first_name')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="middle_name" class="font-weight-bold">Middle Name:</label>
                                <input type="text" name="middle_name" class="form-control" value="<?= set_value('middle_name') ?>" required>
                                <?php if (isset($validation) && $validation->getError('middle_name')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('middle_name')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="font-weight-bold">Last Name:</label>
                                <input type="text" name="last_name" class="form-control" value="<?= set_value('last_name') ?>" required>
                                <?php if (isset($validation) && $validation->getError('last_name')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('last_name')) ?></div>
                                <?php } ?>
                            </div>

                            <!-- Contact Information -->
                            <div class="form-group">
                                <label for="email_address" class="font-weight-bold">Email Address:</label>
                                <input type="email" name="email_address" class="form-control" value="<?= set_value('email_address') ?>" required>
                                <?php if (isset($validation) && $validation->getError('email_address')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('email_address')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="contact_number" class="font-weight-bold">Contact Number:</label>
                                <input type="tel" name="contact_number" class="form-control" value="<?= set_value('contact_number') ?>" required>
                                <?php if (isset($validation) && $validation->getError('contact_number')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('contact_number')) ?></div>
                                <?php } ?>
                            </div>

                            <!-- Organization Details -->
                            <div class="form-group">
                                <label for="organization_name" class="font-weight-bold">Organization/Department Name:</label>
                                <input type="text" name="organization_name" class="form-control" value="<?= set_value('organization_name') ?>" required>
                                <?php if (isset($validation) && $validation->getError('organization_name')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('organization_name')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="position_role" class="font-weight-bold">Position/Role:</label>
                                <input type="text" name="position_role" class="form-control" value="<?= set_value('position_role') ?>" required>
                                <?php if (isset($validation) && $validation->getError('position_role')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('position_role')) ?></div>
                                <?php } ?>
                            </div>

                            <!-- Security Information -->
                            <div class="form-group">
                                <label for="username" class="font-weight-bold">Username:</label>
                                <input type="text" name="username" class="form-control" value="<?= set_value('username') ?>" required>
                                <?php if (isset($validation) && $validation->getError('username')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('username')) ?></div>
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
                                <label for="confirm_password" class="font-weight-bold">Confirm Password:</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                                <?php if (isset($validation) && $validation->getError('confirm_password')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('confirm_password')) ?></div>
                                <?php } ?>
                            </div>

                            <!-- Additional Information -->
                            <div class="form-group">
                                <label for="address" class="font-weight-bold">Address:</label>
                                <textarea name="address" class="form-control" required><?= set_value('address') ?></textarea>
                                <?php if (isset($validation) && $validation->getError('address')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('address')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="date_of_birth" class="font-weight-bold">Date of Birth:</label>
                                <input type="date" name="date_of_birth" class="form-control" value="<?= set_value('date_of_birth') ?>" required>
                                <?php if (isset($validation) && $validation->getError('date_of_birth')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('date_of_birth')) ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="gender" class="font-weight-bold">Gender:</label>
                                <select name="gender" class="form-control" required>
                                    <option value="Male" <?= (set_value('gender') === 'Male') ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= (set_value('gender') === 'Female') ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= (set_value('gender') === 'Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                                <?php if (isset($validation) && $validation->getError('gender')) { ?>
                                    <div class="text-danger"><?= esc($validation->getError('gender')) ?></div>
                                <?php } ?>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>

                            <div class="form-group">
                                <p class="text-body-1">Already have an account? <a class="link-login" href="<?= site_url('admin-login') ?>">Login</a></p>
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
