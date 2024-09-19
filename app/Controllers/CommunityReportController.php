<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommunityReportModel;
use Config\Services;

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
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $communityReportModel = new CommunityReportModel();
        $fileproof = $this->request->getFile('fileproof');
        
        if (!$fileproof->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File upload error: ' . $fileproof->getErrorString()
            ]);
        }

        try {
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

            return $this->response->setJSON(['success' => true, 'message' => 'Emergency call successfully submitted!']);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
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
}
