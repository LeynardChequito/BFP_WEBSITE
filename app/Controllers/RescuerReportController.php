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

        // Prepare data for saving
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
            'photo' => $photoPath, // Save the relative path in the database
        ];

        // Save the data
        if ($fireReportModel->save($data)) {
            return redirect()->to('admin-home')->with('success', 'Fire Report successfully submitted!');
        } else {
            return redirect()->back()->withInput()->with('errors', $fireReportModel->errors());
        }
    }

    public function success()
    {
        return view('RESCUERREPORT/success');
    }
}
