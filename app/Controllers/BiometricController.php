<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class BiometricController extends BaseController
{
public function register()
    {
        $data = $this->request->getJSON();

        $accountModel = new \App\Models\AccountModel();
        $userId = session()->get('user_id'); // Get logged-in user ID

        $accountModel->update($userId, [
            'credential_id' => $data->id,
            'public_key' => $data->attestationObject,
            'attestation_format' => $data->clientDataJSON
        ]);

        return $this->respond(['status' => 'success'], 200);
    }

    public function login()
    {
        $data = $this->request->getJSON();
        $accountModel = new \App\Models\AccountModel();

        // Retrieve the stored public key for the user
        $user = $accountModel->where('credential_id', $data->id)->first();

        if (!$user) {
            return $this->respond(['status' => 'error', 'message' => 'User not found'], 404);
        }

        // Validate the signature using the public key
        $isValid = $this->validateSignature(
            $user['public_key'],
            $data->clientDataJSON,
            $data->authenticatorData,
            $data->signature
        );

        if ($isValid) {
            session()->set(['isLoggedIn' => true, 'user_id' => $user['user_id']]);
            return $this->respond(['status' => 'success'], 200);
        } else {
            return $this->respond(['status' => 'error', 'message' => 'Invalid biometric data'], 400);
        }
    }

    private function validateSignature($publicKey, $clientDataJSON, $authenticatorData, $signature)
    {
        // Perform cryptographic verification of the signature
        // Use a PHP library like web-auth/webauthn-lib for this part
        return true; // Stub for demonstration
    }
}
