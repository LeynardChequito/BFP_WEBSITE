<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use App\Traits\EmailTrait;
use DateTime;

class RegistrationController extends BaseController
{
    use EmailTrait;
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function register()
    {
        return view('REGISTER/registration_form');
    }

    public function processForm()
    {
        helper(['form', 'url', 'session']);

        $rules = [
            'fullName' => 'required|max_length[255]',
            'dob' => 'required|valid_date',
            'address' => 'required',
            'phoneNumber' => 'required|max_length[20]',
            'email' => 'required|valid_email|max_length[255]',
            'password' => 'required|min_length[8]|max_length[30]',
            'sex' => 'required|in_list[male,female]',
            'photoIdPath' => 'uploaded[photoIdPath]|max_size[photoIdPath,5000]|mime_in[photoIdPath,image/jpeg,image/png,image/heic,image/jpg]|ext_in[photoIdPath,png,jpg,jpeg,heic]',
            'profilePhotoPath' => 'uploaded[profilePhotoPath]|max_size[profilePhotoPath,5000]|mime_in[profilePhotoPath,image/jpeg,image/png,image/heic,image/jpg]|ext_in[profilePhotoPath,png,jpg,jpeg,heic]',
            'permission' => 'required',
        ];

        $messages = [
            'fullName' => [
                'required' => 'Full Name is required.',
                'max_length' => 'Full Name should not exceed 255 characters.',
            ],
            'dob' => [
                'required' => 'Date of Birth is required.',
                'valid_date' => 'Invalid Date of Birth format.',
            ],
            'address' => [
                'required' => 'Home Address is required.',
            ],
            'phoneNumber' => [
                'required' => 'Phone Number is required.',
                'max_length' => 'Phone Number should not exceed 20 characters.',
            ],
            'email' => [
                'required' => 'Email Address is required.',
                'valid_email' => 'Invalid Email Address format.',
                'max_length' => 'Email Address should not exceed 255 characters.',
            ],
            'password' => [
                'required' => 'Password is required.',
                'min_length' => 'Password should be at least 8 characters.',
                'max_length' => 'Password should not exceed 30 characters.',
            ],
            'sex' => [
                'required' => 'Sex is required.',
                'in_list' => 'Invalid value for Sex.',
            ],
            'photoIdPath' => [
                'uploaded' => 'Photo ID is required.',
                'max_size' => 'Photo ID size should not exceed 5MB.',
                'mime_in' => 'Invalid file type for Photo ID. Please upload a valid image file.',
            ],
            'profilePhotoPath' => [
                'uploaded' => 'Profile Photo is required.',
                'max_size' => 'Profile Photo size should not exceed 5MB.',
                'mime_in' => 'Invalid file type for Profile Photo. Please upload a valid image file.',
            ],
            'permission' => [
                'required' => 'Permission agreement is required.',
            ],
        ];

        if ($this->validate($rules, $messages)) {
            $accountModel = new AccountModel();

            // Handle file uploads
            $photoIdFile = $this->request->getFile('photoIdPath');
            $profilePhotoFile = $this->request->getFile('profilePhotoPath');

            // Generate random names
            $photoIdFileName = $photoIdFile->getRandomName();
            $profilePhotoFileName = $profilePhotoFile->getRandomName();

            // Move files to the specified directory
            $photoIdFile->move(ROOTPATH . 'public/uploads', $photoIdFileName);
            $profilePhotoFile->move(ROOTPATH . 'public/uploads', $profilePhotoFileName);

            // Generate verification token and expiration
            $verificationToken = bin2hex(random_bytes(16));
            $expiration = (new DateTime())->modify('+1 day')->format('Y-m-d H:i:s');

            // Build data array
            $data = [
                'fullName' => $this->request->getVar('fullName'),
                'dob' => $this->request->getVar('dob'),
                'address' => $this->request->getVar('address'),
                'phoneNumber' => $this->request->getVar('phoneNumber'),
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'sex' => $this->request->getVar('sex'),
                'photoIdPath' => $photoIdFileName,
                'profilePhotoPath' => $profilePhotoFileName,
                'permission' => $this->request->getVar('permission'),
                'verified' => 0, // Set initial verified status to 0 (not verified)
                'verification_token' => $verificationToken,
                'verification_expiration' => $expiration,
            ];

            // Insert data into the database
            $accountModel->insert($data);

            // Send verification email
            $verificationLink = base_url("verify?token=$verificationToken");
            $subject = 'Email Verification';
            $message = "<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        p {
            color: #333;
            font-size: 16px;
            line-height: 1.5;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class='email-container'>
        <h1>Email Verification</h1>
        <p>Hello,</p>
        <p>Thank you for registering with us. To complete your registration, please verify your email address by clicking the verification button below:</p>
        <a href='$verificationLink'>Verify Email</a>
        <p>(Note: Once you click the verification button, you will be directed to the login page where you can log in with your email and password to continue.)</p>
        <p>The verification link will expire after 10 minutes.</p>
        <p>Best regards,<br>BFP_Mailer</p>
        <div class='footer'>
            <p>If you need assistance, please message us on <a href='https://www.facebook.com/calapancityfirestation.orientalmindoro?mibextid=ZbWKwL' target='_blank'>Facebook</a>.</p>
            <p>BFP Calapan City Fire Station, New City Hall Complex, Brgy. Guinobatan, Calapan City, Oriental Mindoro</p>
        </div>
    </div>
</body>
</html>";

            $this->sendEmail($this->request->getVar('email'), $subject, $message);

            // Set success message
            $this->session->setFlashdata('success', 'Registration successful! Please check your email to verify your account.');

            // Redirect to login page after successful registration
            return redirect()->to('login');
        } else {
            $data['validation'] = $this->validator;
            return view('REGISTER/registration_form', $data);
        }
    }
}
