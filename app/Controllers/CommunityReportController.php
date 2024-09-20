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
    protected $firebaseServerKey = 'AAAAMdjqKPk:APA91bH4dQbOlZJbcnrviv8Cak23oGKjVbzs3O0V9s1jEo_SLynqGa-XqxLa4rXtXAWn7eSeeyuqjf9fexjsxzJJVPXmU3GzY8sjddKyRqiFoZdr14ryMhvpGD2I-KmfRjL2rVWVVPnV';

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
                $fileproof->move(ROOTPATH . 'bfpcalapancity/public/community_report', $fileproofName);
    
                $data = [
                    'fullName' => $this->request->getVar('fullName'),
                    'latitude' => $this->request->getVar('latitude'),
                    'longitude' => $this->request->getVar('longitude'),
                    'fileproof' => $fileproofName,
                ];
    
                $communityReportModel->insert($data);
    
                // Prepare notification details
                $title = 'New Emergency Call';
                $body = "A new emergency call has been submitted by {$data['fullName']} at coordinates ({$data['latitude']}, {$data['longitude']}).";
                $imageUrl = base_url('bfpcalapancity/public/community_report/' . $fileproofName);
    
                // Notify all admins
                $this->notifyAllAdmins($title, $body, $imageUrl);
    
                // Return success response as JSON
                return $this->response->setStatusCode(200)->setJSON([
                    'success' => true,
                    'message' => 'Emergency Call successfully submitted!',
                ]);
                
            } else {
                // Fileproof upload failed
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to upload file proof.',
                ]);
            }
        } else {
            // Validation failed
            return $this->response->setJSON([
                'success' => false,
                'message' => $this->validator->getErrors(),
            ]);
        }
    }
    
    public function sendPushNotificationToUser($token, $title, $body, $imageUrl = null)
    {
        // Prepare the payload for the push notification
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = [
            'Authorization: key=' . $this->firebaseServerKey,
            'Content-Type: application/json'
        ];

        $notification = [
            'title' => $title,
            'body' => $body,
        ];

        if ($imageUrl) {
            $notification['image'] = $imageUrl;
        }

        $fields = [
            'to' => $token,
            'notification' => $notification,
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'title' => $title,
                'body' => $body,
                'image' => $imageUrl
            ]
        ];

        // Initialize cURL and send the notification
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

    private function notifyAllAdmins($title, $body, $imageUrl = null)
    {
        $adminModel = new AdminModel();
        $admins = $adminModel->where('token IS NOT NULL')->findAll();

        foreach ($admins as $admin) {
            $this->sendPushNotificationToUser($admin['token'], $title, $body, $imageUrl);
        }
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

    public function getRecentReports()
    {
        $model = new CommunityReportModel();

        // Get the current timestamp in Manila timezone
        $manilaTime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
        $eightHoursAgo = $manilaTime->modify('-8 hours')->format('Y-m-d H:i');

        // Adjust the query to get reports newer than 8 hours ago in MySQL-compatible timezone format
        $eightHoursAgoUTC = (new \DateTime($eightHoursAgo, new \DateTimeZone('Asia/Manila')))
            ->setTimezone(new \DateTimeZone('UTC'))
            ->format('Y-m-d H:i');

        $reports = $model->where('timestamp >=', $eightHoursAgoUTC)
            ->orderBy('timestamp', 'DESC')
            ->findAll();

        // Convert timestamps to 'Asia/Manila' timezone before returning the JSON response
        foreach ($reports as &$report) {
            $report['timestamp'] = (new \DateTime($report['timestamp'], new \DateTimeZone('UTC')))
                ->setTimezone(new \DateTimeZone('Asia/Manila'))
                ->format('Y-m-d (h:i a)');
        }

        return $this->response->setJSON($reports);
    }
}
