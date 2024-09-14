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

    $messages = [
        'fullName' => [
            'required' => 'Full Name is required.',
            'max_length' => 'Full Name should not exceed 255 characters.',
        ],
        'latitude' => [
            'required' => 'Latitude is required.',
            'decimal' => 'Latitude must be a valid decimal number.',
        ],
        'longitude' => [
            'required' => 'Longitude is required.',
            'decimal' => 'Longitude must be a valid decimal number.',
        ],
        'fileproof' => [
            'uploaded' => 'File proof is required.',
            'max_size' => 'File proof must not exceed 50MB.',
            'ext_in' => 'File proof must be an image (jpg, jpeg, png) or video (mp4, mov, avi).',
        ],
    ];

    if ($this->validate($rules, $messages)) {
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
            ];

            $communityReportModel->insert($data);

            // Send notifications to admins
            $this->notifyAllAdmins('New Emergency Call', 'A new emergency call has been submitted.', base_url('public/community_report/' . $fileproofName));

            return $this->response->setJSON(['success' => true, 'message' => 'Emergency call successfully submitted!']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to upload file proof.']);
        }
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Validation errors', 'errors' => $this->validator->getErrors()]);
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
