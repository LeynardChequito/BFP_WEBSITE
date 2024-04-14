<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class ARegistrationController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
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

            
            $adminModel->insert($data);

            $this->session->setFlashdata('success', 'Admin registration successful!');

            return redirect()->to('admin-login');
        } else {
            $data['validation'] = $this->validator;
            return view('AREGISTER/aregister', $data);
        }
    }
}
