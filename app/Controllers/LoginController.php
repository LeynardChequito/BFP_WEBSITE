<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use App\Traits\EmailTrait;
use DateTime;

class LoginController extends BaseController
{
    use EmailTrait;
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    // Verify the email token for account verification
    public function verify()
    {
        $token = $this->request->getGet('token');
        if (!$token) {
            $this->session->setFlashdata('error', 'Invalid token.');
            return redirect()->to('login');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('account');
        $builder->where('verification_token', $token);
        $user = $builder->get()->getRow();

        if (!$user) {
            $this->session->setFlashdata('error', 'Invalid token.');
            return redirect()->to('login');
        }

        $expiration = new DateTime($user->verification_expiration);
        $now = new DateTime();

        if ($now > $expiration) {
            $this->session->setFlashdata('error', 'Token has expired.');
            return redirect()->to('login');
        }

        // Update the user's verified status
        $builder->where('user_id', $user->user_id);
        $builder->update(['verified' => 1, 'verification_token' => null, 'verification_expiration' => null]);

        $this->session->setFlashdata('success', 'Email successfully verified. You can now log in.');
        return redirect()->to('login');
    }

    public function login()
    {
        return view('LOGIN/login');
    }

    public function processLogin()
    {
        helper(['form', 'url', 'session']);

        $rules = [
            'email' => 'required|valid_email|max_length[255]',
            'password' => 'required|min_length[8]|max_length[30]',
        ];

        $messages = [
            'email' => [
                'required' => 'Email is required.',
                'valid_email' => 'Invalid Email Address format.',
                'max_length' => 'Email should not exceed 255 characters.',
            ],
            'password' => [
                'required' => 'Password is required.',
                'min_length' => 'Password should be at least 8 characters.',
                'max_length' => 'Password should not exceed 30 characters.',
            ],
        ];

        if ($this->validate($rules, $messages)) {
            $accountModel = new AccountModel();
            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            $data = $accountModel->where('email', $email)->first();

            if ($data) {
                // Check if the email is verified
                if (!$data['verified']) {
                    session()->setFlashdata('error', 'Your email is not verified. Please check your email for the verification link.');
                    return redirect()->to('login');
                }

                $pass = $data['password'];
                $authenticatePassword = password_verify($password, $pass);

                if ($authenticatePassword) {
                    $ses_data = [
                        'user_id' => $data['user_id'],
                        'email' => $data['email'],
                        'isLoggedIn' => TRUE // Corrected session key
                    ];

                    session()->set($ses_data);
                    session()->setFlashdata('success', 'Login successful!');
                    return redirect()->to('home');
                } else {
                    session()->setFlashdata('error', 'Password is incorrect.');
                    return redirect()->to('login');
                }
            } else {
                session()->setFlashdata('error', 'Email does not exist.');
                return redirect()->to('login');
            }
        } else {
            $data['validation'] = $this->validator;
            return view('LOGIN/login', $data);
        }
    }

    public function forgotPassword()
    {
        return view('LOGIN/forgotpassword');
    }

    // Send Reset Link to Email
    public function sendResetLink()
    {
        helper(['url', 'session']);

        $email = $this->request->getVar('email');
        $accountModel = new AccountModel();

        // Check if the email exists
        $user = $accountModel->where('email', $email)->first();

        if (!$user) {
            session()->setFlashdata('error', 'Email does not exist.');
            return redirect()->back();
        }

        // Generate a reset token
        $resetToken = bin2hex(random_bytes(16));
        $expiration = (new DateTime())->modify('+1 hour')->format('Y-m-d H:i:s');

        // Save the reset token in the database
        $accountModel->update($user['user_id'], [
            'verification_token' => $resetToken,
            'verification_expiration' => $expiration,
        ]);

        // Send the reset link to the user's email
        $resetLink = base_url('/reset-password?token=' . $resetToken);
        $message = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    width: 100%;
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    border: 1px solid #dddddd;
                    border-radius: 8px;
                }
                .email-header {
                    background-color: #d9534f;
                    padding: 20px;
                    text-align: center;
                    color: #ffffff;
                    border-radius: 8px 8px 0 0;
                }
                .email-header img {
                    max-width: 150px;
                }
                .email-body {
                    padding: 20px;
                    text-align: left;
                }
                .email-body h1 {
                    color: #d9534f;
                    font-size: 24px;
                }
                .email-body p {
                    font-size: 16px;
                    line-height: 1.5;
                }
                .email-footer {
                    background-color: #f4f4f4;
                    padding: 10px;
                    text-align: center;
                    font-size: 12px;
                    color: #888888;
                    border-radius: 0 0 8px 8px;
                }
                .reset-link {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #d9534f;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-top: 10px;
                }
                .reset-link:hover {
                    background-color: #c9302c;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <img src='/bfpcalapancity/public/images/bglog.jpg' alt='BFP Logo'>
                    <h2>Bureau of Fire Protection</h2>
                </div>
                <div class='email-body'>
                    <h1>Password Reset Request</h1>
                    <p>Dear {$user['fullName']},</p>
                    <p>We received a request to reset your password for your BFP account. If you did not make this request, you can safely ignore this email.</p>
                    <p>To reset your password, click the button below:</p>
                    <p><a href='{$resetLink}' class='reset-link'>Reset Password</a></p>
                    <p>This link will expire in 1 hour. If you need further assistance, please contact BFP's IT department.</p>
                    <p>Thank you, <br>The Bureau of Fire Protection Admin Team</p>
                </div>
                <div class='email-footer'>
                    <p>This is an automated message, please do not reply.</p>
                    <p>Bureau of Fire Protection, Philippines</p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Send the email using the EmailTrait
        if ($this->sendEmail($email, 'Password Reset', $message)) {
            session()->setFlashdata('success', 'Password reset link has been sent to your email.');
        } else {
            session()->setFlashdata('error', 'Failed to send password reset link.');
        }

        return redirect()->to('/forgot-password');
    }

    // Reset password based on the token
    public function resetPassword()
    {
        $token = $this->request->getGet('token');
        $accountModel = new AccountModel();

        // Validate token
        $user = $accountModel->where('verification_token', $token)->first();

        if (!$user || new DateTime() > new DateTime($user['verification_expiration'])) {
            session()->setFlashdata('error', 'Invalid or expired token.');
            return redirect()->to('/forgot-password');
        }

        return view('LOGIN/resetpassword', ['token' => $token]);
    }

    public function processResetPassword()
    {
        $token = $this->request->getVar('token');
        $newPassword = $this->request->getVar('password');
        $accountModel = new AccountModel();

        // Validate token
        $user = $accountModel->where('verification_token', $token)->first();

        if (!$user || new DateTime() > new DateTime($user['verification_expiration'])) {
            session()->setFlashdata('error', 'Invalid or expired token.');
            return redirect()->to('/forgot-password');
        }

        // Update password and clear the token
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $accountModel->update($user['user_id'], [
            'password' => $hashedPassword,
            'verification_token' => null,
            'verification_expiration' => null,
        ]);

        session()->setFlashdata('success', 'Password successfully reset. You can now log in.');
        return redirect()->to('/login');
    }
}
