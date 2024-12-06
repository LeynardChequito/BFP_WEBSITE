<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialSourceRepository;
use Webauthn\PublicKeyCredentialRpEntity;

class BiometricController extends BaseController
{
    protected $publicKeyCredentialLoader;
    protected $rpEntity;

    public function __construct()
    {
        // Initialize WebAuthn components
        $this->publicKeyCredentialLoader = new PublicKeyCredentialLoader();
        $this->rpEntity = new PublicKeyCredentialRpEntity('example.com'); // Replace with your domain name
    }

    /**
     * Generate a challenge for the biometric process
     */
    public function generateChallenge()
    {
        try {
            $challenge = random_bytes(32); // Generate a secure random challenge
            session()->set('webauthn_challenge', $challenge); // Store in session
            return $this->response->setJSON(['challenge' => base64_encode($challenge)]);
        } catch (\Exception $e) {
            return $this->respond(['status' => 'error', 'message' => 'Failed to generate challenge.'], 500);
        }
    }

    /**
     * Register a new biometric credential
     */
    public function registerBiometric()
    {
        try {
            $data = $this->request->getJSON();

            // Retrieve challenge from session
            $challenge = session()->get('webauthn_challenge');
            if (!$challenge) {
                return $this->respond(['status' => 'error', 'message' => 'Challenge not found.'], 400);
            }

            // Parse the attestation object
            $publicKeyCredential = $this->publicKeyCredentialLoader->load($data->attestationObject);

            if (!$publicKeyCredential->getResponse() instanceof AuthenticatorAttestationResponse) {
                return $this->respond(['status' => 'error', 'message' => 'Invalid attestation response.'], 400);
            }

            /** @var AuthenticatorAttestationResponse $response */
            $response = $publicKeyCredential->getResponse();

            // Validate attestation
            $authenticatorData = $response->getAuthenticatorData();
            $credentialId = $publicKeyCredential->getRawId();

            // Save to the database
            $accountModel = new AccountModel();
            $userId = session()->get('user_id'); // Ensure user ID is set in session

            $accountModel->update($userId, [
                'credential_id' => base64_encode($credentialId),
                'public_key' => base64_encode($authenticatorData->getCredentialPublicKey()),
                'attestation_format' => $data->attestationObject,
            ]);

            return $this->respond(['status' => 'success']);
        } catch (\Exception $e) {
            return $this->respond(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Authenticate using biometric credentials
     */
    public function loginBiometric()
    {
        try {
            $data = $this->request->getJSON();

            // Retrieve stored credential
            $accountModel = new AccountModel();
            $user = $accountModel->where('credential_id', base64_decode($data->id))->first();

            if (!$user) {
                return $this->respond(['status' => 'error', 'message' => 'Credential not found.'], 404);
            }

            // Validate the assertion response
            /** @var AuthenticatorAssertionResponse $response */
            $response = $this->publicKeyCredentialLoader->load($data->assertionObject)->getResponse();

            if (!$response instanceof AuthenticatorAssertionResponse) {
                return $this->respond(['status' => 'error', 'message' => 'Invalid assertion response.'], 400);
            }

            $challenge = session()->get('webauthn_challenge');
            $publicKey = base64_decode($user['public_key']);

            // Perform signature validation
            $isValid = $response->check(
                $challenge,
                $publicKey,
                $response->getAuthenticatorData(),
                $data->signature
            );

            if (!$isValid) {
                return $this->respond(['status' => 'error', 'message' => 'Invalid assertion.'], 400);
            }

            // Successful login
            session()->set(['isLoggedIn' => true, 'user_id' => $user['user_id']]);
            return $this->respond(['status' => 'success']);
        } catch (\Exception $e) {
            return $this->respond(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
