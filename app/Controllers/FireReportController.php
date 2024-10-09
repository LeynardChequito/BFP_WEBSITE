<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FinalIncidentReportModel;
use App\Models\CommunityReportModel;

class FireReportController extends BaseController
{
    public function firereportform($communityReportId = null)
    {
        $communityModel = new CommunityReportModel();
        $communityReport = $communityModel->find($communityReportId);

        if (!$communityReport) {
            return redirect()->back()->with('error', 'Community report not found.');
        }

        return view('RESCUERREPORT/fire_report_form', [
            'communityReport' => $communityReport // Pass data to the view
        ]);
    }
    public function storeFinalReport()
{
    try {
        $finalModel = new FinalIncidentReportModel();
        $communityModel = new CommunityReportModel();

        // Get community report ID from POST data
        $communityReportId = $this->request->getPost('communityreport_id');
        $communityReport = $communityModel->find($communityReportId);

        if (!$communityReport) {
            return $this->response->setJSON(['success' => false, 'message' => 'Community report not found.']);
        }

        // Prepare final report data
        $finalData = [
            'communityreport_id' => $communityReportId,
            'fullName' => $communityReport['fullName'],
            'latitude' => $communityReport['latitude'],
            'longitude' => $communityReport['longitude'],
            'fileproof' => $communityReport['fileproof'],
            'timestamp' => $communityReport['timestamp'],
            
            'rescuer_name' => $this->request->getPost('rescuer_name'),
            'report_date' => $this->request->getPost('report_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'address' => $this->request->getPost('address'),
            'cause_of_fire' => $this->request->getPost('cause_of_fire'),
            'property_damage_cost' => $this->request->getPost('property_damage_cost'),
            'number_of_injuries' => $this->request->getPost('number_of_injuries'),
            'additional_information' => $this->request->getPost('additional_information'),
            'photo' => $this->request->getFile('photo') ? $this->savePhoto($this->request->getFile('photo')) : null,
            
        ];

        // Validate data before insertion
        if (!$finalModel->insert($finalData)) {
            log_message('error', 'Error inserting final incident report: ' . json_encode($finalModel->errors()));
            return $this->response->setJSON(['success' => false, 'message' => 'Error saving final incident report', 'errors' => $finalModel->errors()]);
        }

        $communityModel->delete($communityReportId); // Optional deletion of the community report

        return $this->response->setJSON(['success' => true, 'message' => 'Final report successfully saved.']);
    } catch (\Exception $e) {
        log_message('error', 'Error storing final report: ' . $e->getMessage());
        return $this->response->setJSON(['success' => false, 'message' => 'An internal error occurred. Please try again later.']);
    }
}

public function getReportByCommunityReportId($id)
{
    $communityModel = new CommunityReportModel();
    // Use $id to find the community report
    $communityReport = $communityModel->find($id);

    if (!$communityReport) {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Community report not found.']);
    }

    // Return the found community report as a JSON response
    return $this->response->setJSON($communityReport);
}

    
    // Helper function to save the uploaded photo
    private function savePhoto($photo)
    {
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $newName = $photo->getRandomName();
            $photo->move(WRITEPATH . 'public/final-report', $newName);
            return $newName;
        }
        return null;
    }
}
