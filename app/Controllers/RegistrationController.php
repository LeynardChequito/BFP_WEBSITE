<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;

class RegistrationController extends BaseController
{
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
            ];

            // Insert data into the database
            $accountModel->insert($data);

            // Set success message
            $this->session->setFlashdata('success', 'Registration successful!');

            // Redirect to login page after successful registration
            return redirect()->to('login');
        } else {
            $data['validation'] = $this->validator;
            return view('REGISTER/registration_form', $data);
        }
    }
}
