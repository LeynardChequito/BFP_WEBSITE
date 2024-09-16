<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Traits\EmailTrait;
use DateTime;

class ARegistrationController extends BaseController
{
    use EmailTrait;
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }
    public function verify()
    {
        $token = $this->request->getGet('token');
        if (!$token) {
            $this->session->setFlashdata('error', 'Invalid token.');
            return redirect()->to('admin-login');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('admins');
        $builder->where('verification_token', $token);
        $user = $builder->get()->getRow();

        if (!$user) {
            $this->session->setFlashdata('error', 'Invalid token.');
            return redirect()->to('admin-login');
        }

        $expiration = new DateTime($user->verification_expiration);
        $now = new DateTime();

        if ($now > $expiration) {
            $this->session->setFlashdata('error', 'Token has expired.');
            return redirect()->to('admin-login');
        }

        // Update the user's verified status
        $builder->where('admin_id', $user->admin_id);
        $builder->update(['verified' => 1, 'verification_token' => null, 'verification_expiration' => null]);

        $this->session->setFlashdata('success', 'Email successfully verified. You can now log in.');
        return redirect()->to('admin-login');
    }


    public function adminregister()
    {
        return view('AREGISTER/aregister');
    }

    public function adminprocessForm()
    {
        helper(['form', 'url', 'session']);

        $rules = [
            'first_name' => 'required|max_length[50]',
            'middle_name' => 'max_length[50]',
            'last_name' => 'required|max_length[50]',
            'email_address' => 'required|valid_email|max_length[100]',
            'contact_number' => 'required|max_length[20]',
            'organization_name' => 'required|max_length[100]',
            'position_role' => 'required|max_length[50]',
            'username' => 'required|max_length[50]',
            'password' => 'required|min_length[8]|max_length[255]',
            'address' => 'required|max_length[255]',
            'date_of_birth' => 'required',
            'gender' => 'required|max_length[10]',
        ];

        $messages = [
            'first_name' => [
                'required' => 'First Name is required.',
                'max_length' => 'First Name should not exceed 50 characters.',
            ],
            'middle_name' => [
                'max_length' => 'Middle Name should not exceed 50 characters.',
            ],
            'last_name' => [
                'required' => 'Last Name is required.',
                'max_length' => 'Last Name should not exceed 50 characters.',
            ],
            'email_address' => [
                'required' => 'Email Address is required.',
                'valid_email' => 'Invalid Email Address format.',
                'max_length' => 'Email Address should not exceed 100 characters.',
            ],
            'contact_number' => [
                'required' => 'Contact Number is required.',
                'max_length' => 'Contact Number should not exceed 20 characters.',
            ],
            'organization_name' => [
                'required' => 'Organization/Department Name is required.',
                'max_length' => 'Organization/Department Name should not exceed 100 characters.',
            ],
            'position_role' => [
                'required' => 'Position/Role is required.',
                'max_length' => 'Position/Role should not exceed 50 characters.',
            ],
            'username' => [
                'required' => 'Username is required.',
                'max_length' => 'Username should not exceed 50 characters.',
            ],
            'password' => [
                'required' => 'Password is required.',
                'min_length' => 'Password should be at least 8 characters.',
                'max_length' => 'Password should not exceed 255 characters.',
            ],
            'address' => [
                'required' => 'Address is required.',
                'max_length' => 'Address should not exceed 255 characters.',
            ],
            'date_of_birth' => [
                'required' => 'Date of Birth is required.',
            ],
            'gender' => [
                'required' => 'Gender is required.',
                'max_length' => 'Invalid value for Gender.',
            ],
        ];

        if ($this->validate($rules, $messages)) {
            $adminModel = new AdminModel();

            // Build data array
            $data = [
                'first_name' => $this->request->getVar('first_name'),
                'middle_name' => $this->request->getVar('middle_name'),
                'last_name' => $this->request->getVar('last_name'),
                'email_address' => $this->request->getVar('email_address'),
                'contact_number' => $this->request->getVar('contact_number'),
                'organization_name' => $this->request->getVar('organization_name'),
                'position_role' => $this->request->getVar('position_role'),
                'username' => $this->request->getVar('username'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'address' => $this->request->getVar('address'),
                'date_of_birth' => $this->request->getVar('date_of_birth'),
                'gender' => $this->request->getVar('gender'),
            ];

            $adminId = $adminModel->insert($data);

            // Generate and send verification token
            $token = $adminModel->generateVerificationToken($adminId);
            $verificationLink = base_url("admin/verify?token={$token}");
            $this->sendEmail($data['email_address'], 'Email Verification', "
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f4f4f4;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #fff;
                        border-radius: 8px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    }
                    .email-header {
                        background-color: #ef3340;
                        padding: 20px;
                        color: #ffffff;
                        text-align: center;
                        font-size: 24px;
                        font-weight: bold;
                    }
                    .email-body {
                        padding: 20px;
                        color: #333;
                        line-height: 1.6;
                    }
                    .email-body p {
                        margin-bottom: 20px;
                    }
                    .email-footer {
                        background-color: #f4f4f4;
                        padding: 10px;
                        text-align: center;
                        font-size: 12px;
                        color: #666;
                    }
                    .email-button {
                        background-color: #1a73e8;
                        color: white;
                        padding: 10px 20px;
                        text-decoration: none;
                        border-radius: 5px;
                        font-size: 16px;
                    }
                    .email-button:hover {
                        background-color: #165dbb;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='email-header'>
                        Bureau of Fire Protection
                    </div>
                    <div class='email-body'>
                        <p>Dear {$data['first_name']},</p>
                        <p>Thank you for registering as an administrator on the Bureau of Fire Protection website. To complete your registration, please verify your email address by clicking the button below:</p>
                        <p style='text-align: center;'>
                            <a href='{$verificationLink}' class='email-button'>Verify Email Address</a>
                        </p>
                        <p>If you did not register for an account, you can safely ignore this email.</p>
                        <p>Best regards,<br>The BFP Team</p>
                    </div>
                    <div class='email-footer'>
                        This email was sent from an unmonitored email address. Please do not reply directly to this email.
                    </div>
                </div>
            </body>
            </html>");

            $this->session->setFlashdata('success', 'Admin registration successful! They need to check their email for the verification link.');

            return redirect()->to('admin-home');
        } else {
            $data['validation'] = $this->validator;
            return view('AREGISTER/aregister', $data);
        }
    }
}
