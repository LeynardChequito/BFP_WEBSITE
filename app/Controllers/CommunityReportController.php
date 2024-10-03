<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommunityReportModel;
use App\Models\AdminModel;
use Config\Services;
use CodeIgniter\API\ResponseTrait;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class CommunityReportController extends BaseController
{
    use ResponseTrait;

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
            'communityreport_id' => 'required|integer' // Add validation rule for communityreport_id
        ];
        
        if ($this->validate($rules)) {
            $communityReportModel = new CommunityReportModel();
        
            $fileproof = $this->request->getFile('fileproof');
            if ($fileproof->isValid() && !$fileproof->hasMoved()) {
                $fileproofName = $fileproof->getRandomName();
                $fileproof->move(ROOTPATH . 'public/community_report/', $fileproofName);
        
                $data = [
                    'communityreport_id' => $this->request->getVar('communityreport_id'), // Use the communityreport_id
                    'fullName' => $this->request->getVar('fullName'),
                    'latitude' => $this->request->getVar('latitude'),
                    'longitude' => $this->request->getVar('longitude'),
                    'fileproof' => $fileproofName, // Just store the filename
                ];
        
                $communityReportModel->insert($data);
        
                // Additional logic like sending notifications...
    
                return $this->response->setStatusCode(200)->setJSON([
                    'success' => true,
                    'message' => 'Emergency Call successfully submitted!',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to upload file proof.',
                ]);
            }
        } else {
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

    public function getRecentReports() {
        $model = new CommunityReportModel();
        $eightHoursAgo = (new \DateTime('now', new \DateTimeZone('Asia/Manila')))
            ->modify('-8 hours')
            ->format('Y-m-d H:i:s');
    
        $reports = $model->where('timestamp >=', $eightHoursAgo)
            ->orderBy('timestamp', 'DESC')
            ->findAll();
    
        // Process reports to include formatted timestamps, etc.
        return $this->response->setJSON($reports);
    }
    
    

    public function getLatestReports()
    {
        $communityReportModel = new CommunityReportModel();
    
        // Get the current timestamp in Manila timezone
        $manilaTime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
    
        // Get the reports, order by newest first
        $reports = $communityReportModel->orderBy('timestamp', 'DESC')->findAll();
    
        // Prepare an array to store formatted reports
        $formattedReports = [];
    
        // Process each report
        foreach ($reports as $report) {
            $timestamp = new \DateTime($report['timestamp'], new \DateTimeZone('UTC'));
            $timestamp->setTimezone(new \DateTimeZone('Asia/Manila')); // Convert to Manila time
    
            // Calculate the time difference between now and the report's timestamp
            $interval = $manilaTime->diff($timestamp);
            $timeAgo = $this->formatTimeAgo($interval);
    
            // Add formatted data to the array, including the communityreport_id
            $formattedReports[] = [
                'communityreport_id' => $report['communityreport_id'],  // Include this in the response
                'fullName' => $report['fullName'],
                'fileproof' => base_url('bfpcalapancity/public/community_report/' . $report['fileproof']),
                'timestamp' => $timestamp->format('Y-m-d h:i A'),
                'timeAgo' => $timeAgo,
            ];
        }
    
        // Return the formatted data as a JSON response
        return $this->response->setJSON($formattedReports);
    }
    

/**
 * Helper function to format the time ago indicator.
 */
private function formatTimeAgo($interval)
{
    if ($interval->y > 0) {
        return $interval->y . ' year(s) ago';
    } elseif ($interval->m > 0) {
        return $interval->m . ' month(s) ago';
    } elseif ($interval->d > 0) {
        return $interval->d . ' day(s) ago';
    } elseif ($interval->h > 0) {
        return $interval->h . ' hour(s) ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minute(s) ago';
    } else {
        return 'just now';
    }
}
public function getReportByCommunityReportId($communityreport_id)
{
    $communityReportModel = new CommunityReportModel();

    // Fetch the report by communityreport_id
    $report = $communityReportModel->where('communityreport_id', $communityreport_id)->first();

    if ($report) {
        // Return the report as JSON with a success status code
        return $this->respond($report, 200);
    } else {
        // Return a 404 response if the report is not found
        return $this->failNotFound('Report not found');
    }
}

}
