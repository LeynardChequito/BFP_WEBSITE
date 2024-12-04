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
        'rescuer_name',
        'report_date',
        'start_time',
        'end_time',
        'address',
        'cause_of_fire',
        'property_damage_cost',
        'property_damage_cost_estimate',
        'number_of_injuries',
        'additional_information',
        'photo',
        'latitude',
        'longitude'
    ];
    public function getEstimatedValue($enumValue)
    {
        $mapping = [
            '₱0 - ₱99' => 50,
            '₱100 - ₱999' => 550,
            '₱1000 - ₱9999' => 5500,
            '₱10000 - ₱24999' => 17500,
            '₱25000 - ₱49999' => 37500,
            '₱50000 - ₱99999' => 75000,
            '₱100000 - ₱249999' => 175000,
            '₱250000 - ₱499999' => 375000,
            '₱500000 - ₱999999' => 750000,
            '₱1000000 - ₱1999999' => 1000000,
        ];

        $enumValue = trim($enumValue);

        return isset($mapping[$enumValue]) ? $mapping[$enumValue] : 0;
    }

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
        'photo' => 'permit_empty|max_length[255]',
        // 'photo' => 'permit_empty|is_image[photo]|max_size[photo,5000]|mime_in[photo,image/jpeg,image/png,image/jpg]|ext_in[photo,png,jpg,jpeg]',
    ];


    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected function beforeInsert(array $data)
    {
        if (isset($data['data']['property_damage_cost'])) {
            $data['data']['property_damage_cost_estimate'] = $this->getEstimatedValue($data['data']['property_damage_cost']);
        }
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['property_damage_cost'])) {
            $data['data']['property_damage_cost_estimate'] = $this->getEstimatedValue($data['data']['property_damage_cost']);
        }
        return $data;
    }

    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
