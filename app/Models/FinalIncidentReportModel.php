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
    protected $allowedFields    = ['rescuerreport_id', 'communityreport_id', 'user_name', 'report_date', 
        'start_time', 'end_time', 'address', 'cause_of_fire', 'fire_undetermined', 
        'property_damage_cost', 'number_of_injuries', 'additional_information', 
        'photo', 'latitude', 'longitude', 'fullName', 'fileproof', 'timestamp'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
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
