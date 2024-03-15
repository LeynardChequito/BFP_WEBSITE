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
    
        if ($data && password_verify($password, $data['password'])) {
            log_message('debug', 'User data: ' . print_r($data, true));
            // Update the token in the database
            $adminModel->update($data['admin_id'], array('token' => $token));
    
            // Set session data
            $ses_data = [
                'admin_id' => $data['admin_id'],     
                'email_address' => $data['email_address'],
                'isLoggedln' => TRUE
            ];
            $this->session->set($ses_data);
    
            $res['status'] = '1';
            $res['message'] = 'Login successful';
        } else {
            $res['status'] = '0';
            $res['message'] = 'Login failed';
        }
    
        log_message('debug', 'Login response: ' . json_encode($res));
    
        return json_encode($res);
    }
    
}
