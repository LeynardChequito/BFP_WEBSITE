<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admins';
    protected $primaryKey       = 'admin_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['first_name', 'middle_name', 'last_name', 'email_address', 'contact_number', 'organization_name', 'position_role', 'username', 'password', 'address', 'date_of_birth', 'gender', 'token', 'verified', 'verification_token', 'verification_expiration'];

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
    public function generateVerificationToken($adminId)
    {
        $token = bin2hex(random_bytes(16)); // Generate a random token
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // Set expiration time (1 hour from now)

        $this->update($adminId, [
            'verification_token' => $token,
            'verification_expiration' => $expiration
        ]);

        return $token;
    }
}
