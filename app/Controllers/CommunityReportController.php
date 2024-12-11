<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommunityReportModel;
use App\Models\NDRRMCModel;
use Config\Services;
use CodeIgniter\API\ResponseTrait;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class CommunityReportController extends BaseController
{
    use ResponseTrait;

    protected $session;
    protected $subscriptionModel;

    public function __construct()
    {
        $this->session = Services::session();
        date_default_timezone_set('Asia/Manila');
        $this->subscriptionModel = new NDRRMCModel();
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
                $fileproof->move(ROOTPATH . 'public/community_report/', $fileproofName);

                $data = [
                    'fullName' => $this->request->getVar('fullName'),
                    'latitude' => $this->request->getVar('latitude'),
                    'longitude' => $this->request->getVar('longitude'),
                    'fileproof' => $fileproofName,
                ];

                // Save the emergency report
                $communityReportModel->insert($data);

                // Trigger notification sending
                $this->sendNotificationToAdmins($data);

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
            $errors = $this->validator->getErrors();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors,
            ]);
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
    
        // Get the reports, ordered by newest first
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
                'fileproof' => base_url('bfpcalapancity/public/community_report/' . $report['fileproof']), // Full URL path
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
public function saveSubscription()
{
    header('Access-Control-Allow-Origin: https://bfpcalapancity.online');
    header('Access-Control-Allow-Methods: GET, POST');
    header('Access-Control-Allow-Headers: Content-Type');
    try {
        // Get JSON data from the request
        $data = $this->request->getJSON(true);

        // Validate required fields
        if (
            !$data ||
            !isset($data['endpoint'], $data['keys']['p256dh'], $data['keys']['auth'])
        ) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid subscription data.',
            ])->setStatusCode(400);
        }

        // Prepare subscription data for insertion
        $subscriptionData = [
            'endpoint' => $data['endpoint'],
            'public_key' => $data['keys']['p256dh'],
            'auth_key' => $data['keys']['auth'],
        ];

        // Insert subscription into the database
        if ($this->subscriptionModel->insert($subscriptionData)) {
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to save subscription.',
        ])->setStatusCode(500);
    } catch (\Exception $e) {
        log_message('error', 'Error saving subscription: ' . $e->getMessage());
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Server error.',
        ])->setStatusCode(500);
    }
}

    public function sendNotification()
    {
        $subscriptions = $this->subscriptionModel->findAll();

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:bfpcalapancity@gmail.com',
                'publicKey' => getenv('VAPID_PUBLIC_KEY'),
                'privateKey' => getenv('VAPID_PRIVATE_KEY'),
            ],
        ];

        $webPush = new WebPush($auth);

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub['endpoint'],
                'publicKey' => $sub['public_key'],
                'authToken' => $sub['auth_key'],
            ]);

            // Queue the notification
            $webPush->queueNotification(
                $subscription,
                json_encode([
                    'title' => 'Emergency Alert!',
                    'body' => 'An emergency has been reported! Please check the system.',
                ])
            );
        }

        // Send all notifications
        $webPush->flush();

        return $this->response->setJSON(['status' => 'notifications_sent']);
    }

    private function sendNotificationToAdmins($emergencyData)
    {
        $subscriptions = $this->subscriptionModel->findAll();

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:bfpcalapancity@gmail.com',
                'publicKey' => getenv('VAPID_PUBLIC_KEY'),
                'privateKey' => getenv('VAPID_PRIVATE_KEY'),
            ],
        ];

        $webPush = new WebPush($auth);

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub['endpoint'],
                'publicKey' => $sub['public_key'],
                'authToken' => $sub['auth_key'],
            ]);

            $webPush->queueNotification(
                $subscription,
                json_encode([
                    'title' => 'Emergency Alert!',
                    'body' => 'An emergency has been reported! Please check the system.',
                ])
            );
        }

        // Flush notifications to ensure they are sent
        $webPush->flush();
    }
}
