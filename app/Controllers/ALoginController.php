<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class ALoginController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }
    public function loadingpage()
    {
        return view('ALOGIN/aloading_page');
    }
    public function adminlogin()
    {
        return view('ALOGIN/adminlogin');
    }

    public function adminprocessLogin()
    {
        helper(['form', 'url', 'session']);

        $rules = [
            'email_address' => 'required|valid_email|max_length[100]',
            'password' => 'required|min_length[8]|max_length[255]',
        ];

        $messages = [
            'email_address' => [
                'required' => 'Email is required.',
                'valid_email' => 'Invalid Email Address format.',
                'max_length' => 'Email should not exceed 100 characters.',
            ],
            'password' => [
                'required' => 'Password is required.',
                'min_length' => 'Password should be at least 8 characters.',
                'max_length' => 'Password should not exceed 255 characters.',
            ],
        ];

        if ($this->validate($rules, $messages)) {
            $adminModel = new AdminModel();
            $email = $this->request->getVar('email_address');
            $password = $this->request->getVar('password');

            $admin = $adminModel->where('email_address', $email)->first();

            if ($admin) {
                // Check if the email is verified
                if (!$admin['verified']) {
                    $this->session->setFlashdata('error', 'Your email is not verified. Please check your email for the verification link.');
                    return redirect()->to('/admin-login');
                }

                $hashedPassword = $admin['password'];

                if (password_verify($password, $hashedPassword)) {
                    $sessionData = [
                        'admin_id' => $admin['admin_id'],
                        'email_address' => $admin['email_address'],
                        'isLoggedln' => true
                    ];

                    $this->session->set($sessionData);
                    $this->session->setFlashdata('success', 'Login successful!');
                    return redirect()->to('/admin-home');
                } else {
                    $this->session->setFlashdata('error', 'Password is incorrect.');
                    return redirect()->to('/admin-login');
                }
            } else {
                $this->session->setFlashdata('error', 'Email does not exist.');
                return redirect()->to('/admin-login');
            }
        } else {
            $data['validation'] = $this->validator;
            return view('ALOGIN/adminlogin', $data);
        }
    }

    public function adddologin()
    {
        $adminModel = new AdminModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $token = $this->request->getVar('token'); // Retrieve the token from the request

        $data = $adminModel->where('email_address', $email)->first();

        if ($data) {
            // Check if the email is verified
            if (!$data['verified']) {
                $res['status'] = '0';
                $res['message'] = 'Your email is not verified. Please check your email for the verification link.';
                return $this->response->setJSON($res);
            }

            if (password_verify($password, $data['password'])) {
                log_message('debug', 'User data: ' . print_r($data, true));
                // Update the token in the database
                $adminModel->update($data['admin_id'], ['token' => $token]);

                // Set session data
                $ses_data = [

                    'isAdminLoggedIn' => TRUE // Corrected session key
                ];
                session()->set($ses_data);

                $res['status'] = '1';
                $res['message'] = 'Login successful';
            } else {
                $res['status'] = '0';
                $res['message'] = 'Password is incorrect';
            }
        } else {
            $res['status'] = '0';
            $res['message'] = 'Email does not exist';
        }

        log_message('debug', 'Login response: ' . json_encode($res));

        return $this->response->setJSON($res);
    }
}
