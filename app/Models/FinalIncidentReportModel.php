<?php

namespace App\Models;

use CodeIgniter\Model;

class FinalIncidentReportModel extends Model
{
    protected $table            = 'final_incident_report';
    protected $primaryKey       = 'final_report_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'communityreport_id', 
        'fullName', 
        'latitude', 
        'longitude', 
        'fileproof', 
        'timestamp',
        'rescuer_name',
        'report_date', 
        'start_time', 
        'end_time', 
        'address', 
        'cause_of_fire',
        'property_damage_cost', 
        'number_of_injuries', 
        'additional_information', 
        'photo' ];
        
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
  // In FinalIncidentReportModel
protected $validationRules = [
    'rescuer_name' => 'required|min_length[3]|max_length[50]',
    'report_date' => 'required|valid_date',
    'start_time' => 'required',
    'end_time' => 'required',
    'address' => 'required',
    'cause_of_fire' => 'required',
    'property_damage_cost' => 'required',
    'number_of_injuries' => 'required|integer',
    'additional_information' => 'permit_empty|max_length[255]',
    'photo' => 'permit_empty|is_image[photo]|max_size[photo,2048]', // Adjust size limit as needed
];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
