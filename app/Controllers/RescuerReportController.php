<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FinalIncidentReportModel;

class RescuerReportController extends BaseController
{
    public function reportform()
    {
        return view('RESCUERREPORT/fire_report_form');
    }

    public function store()
    {
        $fireReportModel = new FinalIncidentReportModel();

        // Retrieve the file
        $file = $this->request->getFile('photo');
        $photoPath = null;

        // Check if file is valid and has not been moved
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $originalName = $file->getRandomName(); // Generate a random name for security reasons
            $file->move(ROOTPATH . 'public/rescuer_report/', $originalName); // Move the file to the public directory
            $photoPath = $originalName; // Save the relative path
        }

        // Validate input data
        if (!$this->validate($fireReportModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for saving, including latitude and longitude
        $data = [
            'rescuer_name' => $this->request->getPost('rescuer_name'),
            'report_date' => $this->request->getPost('report_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'address' => $this->request->getPost('address'),
            'cause_of_fire' => $this->request->getPost('cause_of_fire'),
            'property_damage_cost' => $this->request->getPost('property_damage_cost'),
            'number_of_injuries' => $this->request->getPost('number_of_injuries'),
            'additional_information' => $this->request->getPost('additional_information'),
            'latitude' => $this->request->getPost('latitude'), // Save latitude
            'longitude' => $this->request->getPost('longitude'), // Save longitude
            'photo' => $photoPath,
        ];

        // Save the data
        if ($fireReportModel->save($data)) {
            return redirect()->to('rescuer-report/success')->with('success', 'Fire Report successfully submitted!');
        } else {
            return redirect()->back()->withInput()->with('errors', $fireReportModel->errors());
        }
    }

    public function success()
    {
        return view('RESCUERREPORT/success');
    }

    public function heatmap()
    {
        return view('EMERGENCYCALL/heatmap');
    }

public function getHeatmapData()
{
    $fireReportModel = new FinalIncidentReportModel();

    // Fetch reports with valid latitude and longitude
    $reports = $fireReportModel
        ->where('latitude IS NOT NULL')
        ->where('longitude IS NOT NULL')
        ->findAll();

    // Transform the data for both heatmap and clustering
    $heatmapData = [];
    foreach ($reports as $report) {
        $heatmapData[] = [
            'latitude' => $report['latitude'],
            'longitude' => $report['longitude'],
            'intensity' => $this->calculateIntensity($report), // Optional intensity calculation
            'address' => $report['address'], // Include address for popup
            'cause_of_fire' => $report['cause_of_fire'], // Include cause for popup
            'property_damage_cost_estimate' => $report['property_damage_cost_estimate'], // Include damage cost
            'number_of_injuries' => $report['number_of_injuries'], // Include number of injuries
        ];
    }

    return $this->response->setJSON($heatmapData);
}

private function calculateIntensity($report)
{
    // Example: Intensity based on injuries and property damage
    $injuriesWeight = 0.7;
    $damageWeight = 0.3;

    $injuriesIntensity = $report['number_of_injuries'] * $injuriesWeight;
    $damageIntensity = $report['property_damage_cost_estimate'] * $damageWeight / 1000000; // Normalize large values

    return $injuriesIntensity + $damageIntensity;
}

}
