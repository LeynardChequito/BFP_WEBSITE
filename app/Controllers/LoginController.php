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
    public function loadingpage()
    {
        return view('LOGIN/loading_page');
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


    public function dologin()
{
    $accountModel = new AccountModel();
    $email = $this->request->getVar('email');
    $password = $this->request->getVar('password');
    $token = $this->request->getVar('token');

    // Ensure that email is provided
    if (!$email || !$password) {
        $res['status'] = '0';
        $res['message'] = 'Email or password missing';
        return $this->response->setJSON($res);
    }

    // Fetch the user data from the account model using email
    $data = $accountModel->where('email', $email)->first();

    // Check if user exists
    if ($data) {
        // Check if the email is verified
        if (!$data['verified']) {
            $res['status'] = '0';
            $res['message'] = 'Your email is not verified. Please check your email for the verification link.';
            return $this->response->setJSON($res);
        }

        // Verify the provided password
        if (password_verify($password, $data['password'])) {
            log_message('debug', 'User data: ' . print_r($data, true));

            // Check if token is provided
            if ($token) {
                // Update token in the account model for the user
                $updateToken = $accountModel->update($data['user_id'], ['token' => $token]);
                if (!$updateToken) {
                    log_message('error', 'Failed to update token for user_id: ' . $data['user_id']);
                    $res['status'] = '0';
                    $res['message'] = 'Failed to update token.';
                    return $this->response->setJSON($res);
                }
            } else {
                log_message('error', 'No token provided for update.');
                $res['status'] = '0';
                $res['message'] = 'Token is missing.';
                return $this->response->setJSON($res);
            }

            // Set session data
            $ses_data = [
                'user_id' => $data['user_id'],
                'fullName' => $data['fullName'],
                'email' => $data['email'],
                'isLoggedIn' => TRUE // Corrected session key
            ];
            session()->set($ses_data);

            $res['status'] = '1';
            $res['message'] = 'Login successful';
        } else {
            log_message('error', 'Password is incorrect for email: ' . $email);
            $res['status'] = '0';
            $res['message'] = 'Password is incorrect';
        }
    } else {
        log_message('error', 'Email does not exist: ' . $email);
        $res['status'] = '0';
        $res['message'] = 'Email does not exist';
    }

    log_message('debug', 'Login response: ' . json_encode($res));
    return $this->response->setJSON($res);
}


//forgot password

public function forgotPassword()
    {
        return view('LOGIN/forgotpassword');
    }

    public function sendResetLink()
{
    $email = $this->request->getVar('email');
    $accountModel = new AccountModel();
    $user = $accountModel->where('email', $email)->first();

    if (!$user) {
        session()->setFlashdata('error', 'Email not found.');
        return redirect()->back();
    }

    // Generate reset token and save it with expiration
    $resetToken = bin2hex(random_bytes(16)); // Generate a random token
    $expiration = (new DateTime())->modify('+1 hour')->format('Y-m-d H:i:s');

    // Update user with reset token and expiration time
    $accountModel->update($user['user_id'], [
        'verification_token' => $resetToken,
        'verification_expiration' => $expiration,
    ]);

    // Create reset link
    $resetLink = base_url('/reset-password?token=' . $resetToken);

    // Define your HTML email template
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
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>
                <img src='https://yourdomain.com/logo.png' alt='BFP Logo'>
                <h2>Bureau of Fire Protection</h2>
            </div>
            <div class='email-body'>
                <h1>Password Reset Request</h1>
                <p>Dear {$user['fullName']},</p>
                <p>We received a request to reset your password for your BFP account. If you did not make this request, you can safely ignore this email.</p>
                <p>To reset your password, click the button below:</p>
                <p><a href='{$resetLink}' class='reset-link'>Reset Password</a></p>
                <p>This link will expire in 1 hour. If you need further assistance, please message us on <a href='https://www.facebook.com/calapancityfirestation.orientalmindoro?mibextid=ZbWKwL' target='_blank'>Facebook</a>.</p>
                <p>Thank you, <br>The Bureau of Fire Protection Admin Team</p>
            </div>
            <div class='email-footer'>
                <p>This is an automated message, please do not reply.</p>
                <p>Bureau of Fire Protection, Calapan City, Oriental Mindoro Philippines</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Send the email using the sendEmail method
    $this->sendEmail($user['email'], 'Reset Your Password', $message, true);
    

    session()->setFlashdata('success', 'A password reset link has been sent to your email.');
    return redirect()->to('/login');
}



public function resetPassword()
{
    $token = $this->request->getGet('token');
    $accountModel = new AccountModel();
    $user = $accountModel->where('verification_token', $token)->first();

    // Check if token is valid and not expired
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

    // Find user by token
    $user = $accountModel->where('verification_token', $token)->first();

    if (!$user || new DateTime() > new DateTime($user['verification_expiration'])) {
        session()->setFlashdata('error', 'Invalid or expired token.');
        return redirect()->to('/forgot-password');
    }

    // Update the password and clear the token
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $accountModel->update($user['user_id'], [
        'password' => $hashedPassword,
        'verification_token' => null,
        'verification_expiration' => null,
    ]);

    session()->setFlashdata('success', 'Your password has been successfully reset.');
    return redirect()->to('/login'); 
}


}
