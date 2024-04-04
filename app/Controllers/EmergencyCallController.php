<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmergencyCallModel;
use App\Models\AccountModel;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class EmergencyCallController extends BaseController
{
    private $emergencyCall;

    public function __construct()
    {
        $this->emergencyCall = new EmergencyCallModel();
    }

    public function submitEmergencyCall()
{
    try {
        // Retrieve form data
        $fireType = $this->request->getPost('fire_type');
        $barangay = $this->request->getPost('barangay');
        $fireSize = $this->request->getPost('fire_size');
        $roadType = $this->request->getPost('road_type');
        $photoUpload = $this->request->getFile('photo_upload');
        $additionalInfo = $this->request->getPost('additional_info');

        // Retrieve user's full name based on user_id
        $userId = session()->get('user_id'); // Assuming you store the user_id in session
        $accountModel = new AccountModel();
        $user = $accountModel->find($userId);
        $fullName = $user['fullName'];

        // Prepare emergency call data including user's full name
        $emergencyCallData = [
            'user_id' => $userId,
            'full_name' => $fullName,
            'fire_type' => $fireType,
            'barangay' => $barangay,
            'fire_size' => $fireSize,
            'road_type' => $roadType,
            'additional_info' => $additionalInfo,
            'photo_upload' => $photoUpload->getName() // Store the filename instead of the uploaded file object
        ];

        // Save emergency call data to database
        $this->emergencyCall->insert($emergencyCallData);

        // Handle file upload
        if ($photoUpload && $photoUpload->isValid()) {
            // Move the uploaded file to the public/accident_report directory
            $photoUpload->move(ROOTPATH . 'public/accident_report', $photoUpload->getName());
        }

        // Initialize Firebase Admin SDK
        $factory = (new Factory)->withServiceAccount(ROOTPATH . 'C:\laragon\www\BFP_WEBSITE\pushnotifbfp-c11343df49d3.json ');
        $messaging = $factory->createMessaging();

        // Construct the notification message
        $message = CloudMessage::fromArray([
            'notification' => [
                'title' => 'Emergency Call',
                'body' => 'A new emergency call has been submitted.',
                'data' => [
                    'fireType' => $fireType,
                    'barangay' => $barangay,
                    'fireSize' => $fireSize,
                    'roadType' => $roadType,
                    'additionalInfo' => $additionalInfo,
                    'photo_upload' => $photoUpload->getName() // Store the filename in the notification data
                ],
            ],
            'topic' => 'admin_notifications'
        ]);

        // Send the message
        $messaging->send($message);

        // Redirect or return response as needed
        return redirect()->to('/home')->with('success', 'Emergency call submitted successfully.');
    } catch (\Throwable $th) {
        // Handle any errors
        return redirect()->back()->withInput()->with('error', $th->getMessage());
    }
}

}
