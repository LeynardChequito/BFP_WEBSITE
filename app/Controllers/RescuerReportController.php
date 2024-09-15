<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RescuerReportModel;

class RescuerReportController extends BaseController
{
    public function firereportform()
    {
        return view('RESCUERREPORT/fire_report_form');
    }
    public function reportform()
    {
        return view('RESCUERREPORT/report_form');
    }

    public function store()
    {
        $fireReportModel = new RescuerReportModel();

        $file = $this->request->getFile('photo');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'public/rescuer_report/', $newName);
            $photoPath = WRITEPATH . 'public/rescuer_report/' . $newName;
        } else {
            $photoPath = null;
        }

        $data = [
            'user_name' => $this->request->getPost('user_name'),
            'report_date' => $this->request->getPost('report_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'address' => $this->request->getPost('address'),
            'cause_of_fire' => $this->request->getPost('cause_of_fire'),
            'fire_undetermined' => $this->request->getPost('fire_undetermined'),
            'property_damage_cost' => $this->request->getPost('property_damage_cost'),
            'number_of_injuries' => $this->request->getPost('number_of_injuries'),
            'additional_information' => $this->request->getPost('additional_information'),
            'photo' => $photoPath
        ];

        $fireReportModel->save($data);

        return redirect()->to('rescuemap');
    }
    
    public function save()
{
    $rescuerReportModel = new RescuerReportModel();

    $file = $this->request->getFile('photo');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'public/rescuer_report/', $newName);
        $photoPath = WRITEPATH . 'public/rescuer_report/' . $newName;
    } else {
        $photoPath = null; 
    }

    $data = [
        'user_name' => $this->request->getPost('user_name'),
        'report_date' => $this->request->getPost('report_date'),
        'start_time' => $this->request->getPost('start_time'),
        'end_time' => $this->request->getPost('end_time'),
        'address' => $this->request->getPost('address'),
        'cause_of_fire' => $this->request->getPost('cause_of_fire'),
        'fire_undetermined' => $this->request->getPost('fire_undetermined') ? 1 : 0,
        'property_damage_cost' => $this->request->getPost('property_damage_cost'),
        'number_of_injuries' => $this->request->getPost('number_of_injuries'),
        'additional_information' => $this->request->getPost('additional_information'),
        'photo' => $photoPath
    ];

    $rescuerReportModel->save($data);

    return redirect()->to('rescuer-report/success');
}

    
}
