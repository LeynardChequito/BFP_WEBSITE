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
         // Set the timezone for the application
         date_default_timezone_set('Asia/Manila');

         // Set the timezone for the database connection
         $db = \Config\Database::connect();
         $db->query("SET time_zone = 'Asia/Manila'");
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
            $CommunityReportModel = new CommunityReportModel();

            $fileproof = $this->request->getFile('fileproof');
            if ($fileproof->isValid() && !$fileproof->hasMoved()) {
                $fileproofname = $fileproof->getRandomName();
                $fileproof->move(ROOTPATH . 'public/community_report', $fileproofname);

                $data = [
                    'fullName' => $this->request->getVar('fullName'),
                    'latitude' => $this->request->getVar('latitude'),
                    'longitude' => $this->request->getVar('longitude'),
                    'fileproof' => $fileproofname,
                ];

                $CommunityReportModel->insert($data);

                $this->session->setFlashdata('success', 'Emergency Call successfully submitted!');

                return redirect()->to('home');
            } else {
                $this->session->setFlashdata('failed', 'Failed to upload file proof.');
                return redirect()->back()->withInput();
            }
        } else {
            $data['validation'] = $this->validator;
            $this->session->setFlashdata('failed', 'Failed! Emergency Call unsent. Please Try Again.');
            return redirect()->back()->withInput()->with('validation', $this->validator);
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
    
    // Get the timestamp for 8 hours ago
    $eightHoursAgo = date('Y-m-d H:i:s', strtotime('-8 hours'));

    // Adjust the query to get reports newer than 8 hours ago
    $reports = $model->where('timestamp >=', $eightHoursAgo)
                     ->orderBy('timestamp', 'DESC')
                     ->findAll();

    return $this->response->setJSON($reports);
    }
}
