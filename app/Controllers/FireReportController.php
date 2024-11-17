<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FinalIncidentReportModel;

// <!-- app\Controllers\FireReportController.php -->

class FireReportController extends BaseController
{
    public function firereportform()
    {
        //  return view('RESCUERREPORT/fire_report_form');

        // Retrieve query parameters
        $lat = $this->request->getGet('lat');
        $lng = $this->request->getGet('lng');
        $communityreport_id = $this->request->getGet('communityreport_id');

        // Pass them to the view
        return view('RESCUERREPORT/fire_report_form', [
            'lat' => $lat,
            'lng' => $lng,
            'communityreport_id' => $communityreport_id,
        ]);
    }

    public function store()
    {
        helper(['form', 'url', 'session']);
        $model = new FinalIncidentReportModel();

        try {
            // Validate the form input
            if (!$this->validate([
                'rescuer_name' => 'required|min_length[3]|max_length[50]',
                'report_date' => 'required|valid_date',
                'start_time' => 'required',
                'end_time' => 'required',
                'address' => 'required',
                'cause_of_fire' => 'required',
                'property_damage_cost' => 'required',
                'number_of_injuries' => 'required|integer',
                'additional_information' => 'permit_empty|max_length[255]',
                'photo' => 'permit_empty|is_image[photo]|max_size[photo,5000]|mime_in[photo,image/jpeg,image/png,image/jpg]|ext_in[photo,png,jpg,jpeg]',
            ])) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->validator->getErrors(),
                ]);
            }

            // Get all input data
            $data = $this->request->getPost();

            // Map the property damage cost to estimated value
            $data['property_damage_cost_estimate'] = $model->getEstimatedValue($data['property_damage_cost']);

            // Handle photo upload
            $photo = $this->request->getFile('photo');
            if ($photo && $photo->isValid()) {
                log_message('debug', 'Temp file: ' . $photo->getTempName());
                log_message('debug', 'Original file name: ' . $photo->getName());
                log_message('debug', 'Error code: ' . $photo->getError());

                if (!$photo->hasMoved()) {
                    $newPhotoName = $photo->getRandomName();

                    $photo->move(ROOTPATH . 'public/final-report', $newPhotoName);



                    if (!file_exists(ROOTPATH . 'public/final-report/' . $newPhotoName)) {
                        return $this->response->setJSON([
                            'success' => false,
                            'errors' => ['photo' => $photo->getErrorString()],
                        ]);
                    }

                    $data['photo'] = $newPhotoName;
                } else {
                    log_message('error', 'File has already been moved.');
                }





                // Move the uploaded file to the uploads directory
                // if (!$photo->move(ROOTPATH . 'public/final-report', $newPhotoName)) {
                //     log_message('error', 'File move failed: ' . $photo->getErrorString());
                //     return $this->response->setJSON([
                //         'success' => false,
                //         'errors' => ['photo' => $photo->getErrorString()],
                //     ]);
                // }


            } else {
                log_message('error', 'File upload error: ' . ($photo ? $photo->getErrorString() : 'No file uploaded'));
            }

            // Save the data to the database
            if (!$model->insert($data)) {
                log_message('error', 'Database insert failed: ' . json_encode($model->errors()));
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $model->errors(),
                ]);
            }

            // Respond with success
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Report successfully saved.',
                'data' => $data,
            ]);
            
        } catch (\Exception $e) {
            log_message('critical', 'Exception occurred: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
