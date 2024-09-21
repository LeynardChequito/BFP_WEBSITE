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
    public function loadingpage()
    {
        return view('LOGIN/loading_page');
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

                // Correctly verify the password using password_verify
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
        $message = "Click the following link to reset your password: " . $resetLink;

        // Assuming `sendEmail` is a method in your `EmailTrait`
        $this->sendEmail($email, 'Password Reset', $message);

        session()->setFlashdata('success', 'Password reset link has been sent to your email.');
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

        // Hash the new password before saving it in the database
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $accountModel->update($user['user_id'], [
            'password' => $hashedPassword,
            'verification_token' => null,             // Reset the verification token
            'verification_expiration' => null,        // Reset the expiration time
        ]);

        session()->setFlashdata('success', 'Password successfully reset. You can now log in.');
        return redirect()->to('/login');
    }
}
