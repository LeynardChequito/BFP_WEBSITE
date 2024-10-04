<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RescuerReportModel;
use App\Models\CommunityReportModel;
use App\Models\FinalIncidentReportModel;

class FireReportController extends BaseController
{    public function firereportform($communityReportId = null)
    {
        // Fetch community report data based on communityReportId
        $communityModel = new CommunityReportModel();
        $communityReport = $communityModel->find($communityReportId);
    
        // Check if the community report exists
        if (!$communityReport) {
            return redirect()->back()->with('error', 'Community report not found.');
        }
    
        // Pass the community report data to the view
        return view('RESCUERREPORT/fire_report_form', [
            'communityReport' => $communityReport // Pass the whole object if needed
        ]);
    }
    
    public function storeRescuerReport()
    {
        // Load models
        $rescuerModel = new RescuerReportModel();
        $communityModel = new CommunityReportModel();
        $finalModel = new FinalIncidentReportModel();

        // Get POST data
        $communityReportId = $this->request->getPost('communityreport_id'); // Coming from your front-end hidden field
        $rescuerData = [
            'user_name'             => $this->request->getPost('user_name'),
            'report_date'           => $this->request->getPost('report_date'),
            'start_time'            => $this->request->getPost('start_time'),
            'end_time'              => $this->request->getPost('end_time'),
            'address'               => $this->request->getPost('address'),
            'cause_of_fire'         => $this->request->getPost('cause_of_fire'),
            'fire_undetermined'     => $this->request->getPost('fire_undetermined'),
            'property_damage_cost'  => $this->request->getPost('property_damage_cost'),
            'number_of_injuries'    => $this->request->getPost('number_of_injuries'),
            'additional_information'=> $this->request->getPost('additional_information'),
            'photo'                 => $this->request->getFile('photo') ? $this->savePhoto($this->request->getFile('photo')) : null,
        ];

        // Save Rescuer Report
        $rescuerModel->insert($rescuerData);
        $rescuerReportId = $rescuerModel->getInsertID(); // Get the last inserted rescuer report ID

        // Fetch the related community report
        $communityReport = $communityModel->find($communityReportId);

        if (!$communityReport) {
            return redirect()->back()->with('error', 'Community report not found.');
        }

        // Combine data from both reports
        $finalData = [
            'rescuerreport_id'      => $rescuerReportId,
            'communityreport_id'    => $communityReport['communityreport_id'],
            'user_name'             => $rescuerData['user_name'],
            'report_date'           => $rescuerData['report_date'],
            'start_time'            => $rescuerData['start_time'],
            'end_time'              => $rescuerData['end_time'],
            'address'               => $rescuerData['address'],
            'cause_of_fire'         => $rescuerData['cause_of_fire'],
            'fire_undetermined'     => $rescuerData['fire_undetermined'],
            'property_damage_cost'  => $rescuerData['property_damage_cost'],
            'number_of_injuries'    => $rescuerData['number_of_injuries'],
            'additional_information'=> $rescuerData['additional_information'],
            'photo'                 => $rescuerData['photo'],
            'latitude'              => $communityReport['latitude'],
            'longitude'             => $communityReport['longitude'],
            'fullName'              => $communityReport['fullName'],
            'fileproof'             => $communityReport['fileproof'],
            'timestamp'             => $communityReport['timestamp']
        ];

        // Save the combined data into the final_incident_report table
        $finalModel->insert($finalData);

        // Delete the community report from the community_report table
        $communityModel->delete($communityReportId);

        // Success, show the success message
        return redirect()->back()->with('success', 'Rescuer report and community report successfully combined and saved. Community report deleted.');
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
