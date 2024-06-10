<?php

namespace App\Controllers;

use App\Models\CommunityReportModel;

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

    public function fetchCommunityReports()
    {
        $communityReportModel = new CommunityReportModel();
        $reports = $communityReportModel->orderBy('communityreport_id', 'DESC')->findAll(1); // Get the latest report
        return $this->response->setJSON($reports);
    }
}
