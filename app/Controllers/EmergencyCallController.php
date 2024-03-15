<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmergencyCallModel;
use App\Models\AccountModel;
use App\Models\AdminModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class EmergencyCallController extends BaseController
{
    private $emergencyCall;
    private $accountModel;
    private $adminModel;

    public function __construct()
    {
        $this->emergencyCall = new EmergencyCallModel();
        $this->accountModel = new AccountModel();
        $this->adminModel = new AdminModel();
        helper('firebasenotifications');
    }

    public function emergencycall()
    {
        $emergencyCallModel = new EmergencyCallModel();
        $latestEmergencyCalls = $emergencyCallModel->orderBy('created_at', 'DESC')->findAll(5); // Fetch latest 5 emergency calls

        $data = [
            'latestEmergencyCalls' => $latestEmergencyCalls,
        ];

        return view('ADMIN/adminnotif', $data);
    }

    public function emergency()
    {
        return view('ADMIN/adminnotif');
    }

    public function sitecall()
    {
        return view('WEBSITE/site');
    }

    public function submitEmergencyCall()
    {
        try {
            // Retrieve user_id from session
            $user_id = session()->get('user_id');
    
            // Retrieve form data
            $fireType = $this->request->getPost('fire_type');
            $fireSize = $this->request->getPost('fire_size');
            $roadType = $this->request->getPost('road_type');
            $additionalInfo = $this->request->getPost('additional_info');
            $photoUpload = $this->request->getFile('photo_upload');
    
            // Prepare emergency call data including user_id
            $emergencyCallData = [
                'user_id' => $user_id,
                'fire_type' => $fireType,
                'fire_size' => $fireSize,
                'road_type' => $roadType,
                'additional_info' => $additionalInfo,
                'photo_upload' => $photoUpload->getName()
            ];
    
            // Save emergency call data to database
            $this->emergencyCall->insert($emergencyCallData);
    
            // Fetch admin's token and other necessary info from the database based on the logged-in admin's id
            $admin_id = session()->get('admin_id'); // Assuming 'admin_id' is stored in session upon admin login
            $admin = $this->adminModel->find($admin_id);
            $adminToken = $admin['token'];
    
            $title = $fireType; // Using fire_type field as the title
            $body = "Fire Size: $fireSize, Road Type: $roadType, Additional Info: $additionalInfo"; // Constructing the body using other fields
    
            // Send push notification to the admin
            $notifRes = sendNotification($title, $body, [$adminToken]);
    
            // Redirect or return response as needed
            return redirect()->to('/home')->with('success', 'Emergency call submitted successfully.');
        } catch (\Throwable $th) {
            // Handle any errors
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }    
}