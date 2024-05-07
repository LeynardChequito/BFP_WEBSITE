<?php

namespace App\Controllers;

use App\Models\LocationsModel;

class LocationController extends BaseController
{
    public function showUserLocation()
    {
        return view('COMPONENTS/contactus');
    }

    public function map()
    {
        return view('EMERGENCYCALL/Rescuemap');
    }

    public function updateLocation()
    {
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');

        // Assuming you have the user's ID, replace USER_ID with the actual user ID
        $userId = 'user_id';

        $LocationsModel = new LocationsModel();

        // Check if the user's location already exists
        $existingLocation = $LocationsModel->where('user_id', $userId)->first();

        if ($existingLocation) {
            // Update the existing location
            $LocationsModel->update($existingLocation['location_id'], [
                'latitude'  => $latitude,
                'longitude' => $longitude,
            ]);
        } else {
            // Insert a new location record
            $LocationsModel->insert([
                'user_id'   => $userId,
                'latitude'  => $latitude,
                'longitude' => $longitude,
            ]);
        }

        return json_encode(['status' => 'success']);
    }
}
