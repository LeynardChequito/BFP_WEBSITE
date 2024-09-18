<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommunityReportModel;
use App\Models\AdminModel; // Ensure you have this model
use Config\Services;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class CommunityReportController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = Services::session();
        date_default_timezone_set('Asia/Manila');
    }

    public function submitcall()
    {
        return view('WEBSITE/site');
    }

    public function submitCommunityReport()
    {
        helper(['form', 'url', 'session']);

        $rules = [
            'fullName' => 'required|max_length[255]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'fileproof' => 'uploaded[fileproof]|max_size[fileproof,50000]|ext_in[fileproof,jpg,jpeg,png,mp4,mov,avi]',
        ];

        if ($this->validate($rules)) {
            $communityReportModel = new CommunityReportModel();
            $fileproof = $this->request->getFile('fileproof');

            if ($fileproof->isValid() && !$fileproof->hasMoved()) {
                $fileproofName = $fileproof->getRandomName();
                $fileproof->move(ROOTPATH . 'public/community_report', $fileproofName);

                $data = [
                    'fullName' => $this->request->getVar('fullName'),
                    'latitude' => $this->request->getVar('latitude'),
                    'longitude' => $this->request->getVar('longitude'),
                    'fileproof' => $fileproofName,
                    'timestamp' => date('Y-m-d H:i:s')
                ];

                $communityReportModel->insert($data);

                // Notify admins of the new report
                $this->notifyAllAdmins($data);

                return $this->response->setJSON(['success' => true, 'message' => 'Emergency call successfully submitted!']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to upload file proof.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Validation errors', 'errors' => $this->validator->getErrors()]);
        }
    }

private function notifyAllAdmins($reportData)
{
    $adminModel = new AdminModel();
    $admins = $adminModel->where('token IS NOT NULL')->findAll();

    foreach ($admins as $admin) {
        $this->sendPushNotificationToUser($admin['token'], $reportData);
    }
}

public function sendPushNotificationToUser($token, $reportData)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = [
            'Authorization: key='aaaamdjqkpk:APA91bH4dQbOlZJbcnrviv8Cak23oGKjVbzs3O0V9s1jEo_SLynqGa-XqxLa4rXtXAWn7eSeeyuqjf9fexjsxzJJVPXmU3GzY8sjddKyRqiFoZdr14ryMhvpGD2I-KmfRjL2rVWVVPnV';,
            'Content-Type: application/json'
        ];

        $notification = [
            'title' => 'New Community Report',
            'body' => 'Full Name: ' . $reportData['fullName'] . '\nFile Proof: ' . $reportData['fileproof'] . '\nSubmitted: just now',
            'image' => '/community_report/' . $reportData['fileproof'],
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        $fields = [
            'to' => $token,
            'notification' => $notification
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }


    public function getEmergencyCallCoordinates()
    {
        $communityReportModel = new CommunityReportModel();
        $emergencyCalls = $communityReportModel->findAll();

        if (!empty($emergencyCalls)) {
            $coordinates = [];
            foreach ($emergencyCalls as $call) {
                $coordinates[] = [
                    'fullName' => $call['fullName'],
                    'latitude' => $call['latitude'],
                    'longitude' => $call['longitude']
                ];
            }
            return view('EMERGENCYCALL/Rescuemap', ['emergencyCalls' => $coordinates]);
        } else {
            return view('EMERGENCYCALL/Rescuemap', ['emergencyCalls' => []]);
        }
    }

    public function getLatestReports()
    {
        $model = new CommunityReportModel();
        $reports = $model->orderBy('timestamp', 'DESC')->findAll();

        // Convert timestamps to 'Asia/Manila' timezone
        foreach ($reports as &$report) {
            $report['timestamp'] = (new \DateTime($report['timestamp'], new \DateTimeZone('UTC')))
                ->setTimezone(new \DateTimeZone('Asia/Manila'))
                ->format('Y-m-d H:i:s');
        }

        return $this->response->setJSON($reports);
    }
    
    public function getRecentReports()
    {
        $model = new CommunityReportModel();
    
        // Get the current timestamp in Manila timezone
        $manilaTime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
        $threeHoursAgo = $manilaTime->modify('-3 hours')->format('Y-m-d H:i:s');
    
        // Adjust the query to get reports newer than 3 hours ago (converted to UTC)
        $threeHoursAgoUTC = (new \DateTime($threeHoursAgo, new \DateTimeZone('Asia/Manila')))
            ->setTimezone(new \DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s');
    
        // Fetch reports newer than 3 hours ago
        $reports = $model->where('timestamp >=', $threeHoursAgoUTC)
            ->orderBy('timestamp', 'DESC')
            ->findAll();
    
        // Convert timestamps back to 'Asia/Manila' timezone before returning the JSON response
        foreach ($reports as &$report) {
            $report['timestamp'] = (new \DateTime($report['timestamp'], new \DateTimeZone('UTC')))
                ->setTimezone(new \DateTimeZone('Asia/Manila'))
                ->format('Y-m-d H:i:s');
        }
    
        return $this->response->setJSON($reports);
    }
    public function saveToken()
    {
        $token = $this->request->getVar('token');
    
        // Assuming you have a model to store tokens in the database
        $adminModel = new AdminModel();
        
        // Store or update the token for the current user
        $adminModel->updateTokenForUser($token); // You need to implement this function in your AdminModel
    
        return $this->response->setJSON(['success' => true, 'message' => 'Token saved successfully']);
    }

}
