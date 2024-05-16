<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommunityReportModel;
use App\Models\AccountModel;

class CommunityReportController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
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

            // Handle file upload
            
                
                $fileproof = $this->request->getFile('fileproof');
                $fileproofname = $fileproof->getRandomName();
                $fileproof->move(ROOTPATH . 'public/community_report', $fileproofname);
            // Build data array
            $data = [
                'fullName' => $this->request->getVar('fullName'),
                'latitude' => $this->request->getVar('latitude'),
                'longitude' => $this->request->getVar('longitude'),
                'fileproof' => $fileproofname,
            ];

            // Insert data into the database
            $CommunityReportModel->insert($data);

            // Set success message
            $this->session->setFlashdata('success', 'Emergency Call successfully submitted!');

            // Redirect to home page after successful submission
            return redirect()->to('home');
        } else {
            $data['validation'] = $this->validator;
            $this->session->setFlashdata('failed', 'Failed! Emergency Call unsent. Please Try Again.');
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
    }

    // Add this method inside your CommunityReportController class
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
            // Pass the coordinates to the view
            return view('EMERGENCYCALL/Rescuemap', ['emergencyCalls' => $coordinates]);
        } else {
            // If no emergency calls found, pass an empty array to the view
            return view('EMERGENCYCALL/Rescuemap', ['emergencyCalls' => []]);
        }
    }
}
