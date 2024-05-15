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

    public function data()
    {
        $CommunityReportModel = new CommunityReportModel();
    
        $data = [
            'news' => $CommunityReportModel->paginate(6),
            'pager' => $CommunityReportModel->pager,
        ];
    
        return view('EMERGENCYCALL/Rescuemap', $data);
    }

    public function submitcall()
    {
        return view('WEBSITE/site');
    }
    public function submitCommunityReport()
    {
        helper(['form', 'url', 'session']);

        $rules = [
            'fullName' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ];

        $messages = [
            'fullName' => [
                'required' => 'Full Name is required.',
                'max_length' => 'Full Name should not exceed 255 characters.',
            ],
            'latitude' => [
                'required' => 'Home Address is required.',
            ],
            'longitude' => [
                'required' => 'Home Address is required.',
            ],
        ];

        if ($this->validate($rules, $messages)) {
            $CommunityReportModel = new CommunityReportModel();
            // Build data array
            $data = [
                'fullName' => $this->request->getVar('fullName'),
                'latitude' => $this->request->getVar('latitude'),
                'longitude' => $this->request->getVar('longitude')
            ];

            // Insert data into the database
            $CommunityReportModel->insert($data);

            // Set success message
            $this->session->setFlashdata('success', 'Emergency Call successfully!');

            // Redirect to login page after successful registration
            return redirect()->to('home');
        } else {
            $data['validation'] = $this->validator;
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
