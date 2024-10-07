<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RescuerReportModel;

class RescuerReportController extends BaseController
{

    public function reportform()
    {
        return view('RESCUERREPORT/report_form');
    }

    public function store()
    {
        $fireReportModel = new RescuerReportModel();

        // Retrieve the file
        $file = $this->request->getFile('photo');
        $photoPath = null;

        // Check if file is valid and has not been moved
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $originalName = $file->getClientName(); // Use the actual name
            $file->move(ROOTPATH . 'public/rescuer_report/', $originalName); // Move the file to public directory
            $photoPath = $originalName; // Save relative path
        }

        // Save the data including the path to the file
        $data = [
            'rescuer_name' => $this->request->getPost('rescuer_name'),
            'report_date' => $this->request->getPost('report_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'address' => $this->request->getPost('address'),
            'cause_of_fire' => $this->request->getPost('cause_of_fire'),
            'fire_undetermined' => $this->request->getPost('fire_undetermined'),
            'property_damage_cost' => $this->request->getPost('property_damage_cost'),
            'number_of_injuries' => $this->request->getPost('number_of_injuries'),
            'additional_information' => $this->request->getPost('additional_information'),
            'photo' => $photoPath // Save the relative path in the database
        ];

        $fireReportModel->save($data);

        return redirect()->to('rescuemap');
    }

    public function save()
    {
        $rescuerReportModel = new RescuerReportModel();

        // Retrieve the file
        $file = $this->request->getFile('photo');
        $photoPath = null;

        // Check if file is valid and has not been moved
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $originalName = $file->getClientName(); // Use the actual name
            $file->move(ROOTPATH . 'public/rescuer_report/', $originalName); // Move the file to public directory
            $photoPath =  $originalName; // Save relative path
        }

        // Save the data including the path to the file
        $data = [
            'rescuer_name' => $this->request->getPost('rescuer_name'),
            'report_date' => $this->request->getPost('report_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'address' => $this->request->getPost('address'),
            'cause_of_fire' => $this->request->getPost('cause_of_fire'),
            'fire_undetermined' => $this->request->getPost('fire_undetermined') ? 1 : 0,
            'property_damage_cost' => $this->request->getPost('property_damage_cost'),
            'number_of_injuries' => $this->request->getPost('number_of_injuries'),
            'additional_information' => $this->request->getPost('additional_information'),
            'photo' => $photoPath // Save the relative path in the database
        ];

        $rescuerReportModel->save($data);

        return redirect()->to('rescuer-report/success');
    }
}

