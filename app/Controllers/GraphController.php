<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RescuerReportModel;

class GraphController extends BaseController
{

    protected $rescuerReportModel;

    public function __construct()
    {
        $this->rescuerReportModel = new RescuerReportModel();
    }

    public function graph()
    {
        return view('ACOMPONENTS/GRAPH/graph');
    }

    public function getReports()
{
    $reports = $this->rescuerReportModel->findAll();

    // Sanitize the data
    foreach ($reports as &$report) {
        // Sanitize property_damage_cost
        if (!is_numeric($report['property_damage_cost']) || $report['property_damage_cost'] === 'UNKNOWN') {
            $report['property_damage_cost'] = 0; // You can set this to `null` if you prefer
        } else {
            // Ensure it's a float value
            $report['property_damage_cost'] = (float) $report['property_damage_cost'];
        }
    }
    foreach ($reports as &$report) {
        // Sanitize number_of_injuries
        if (!is_numeric($report['number_of_injuries'])) {
            $report['number_of_injuries'] = 0;
        } else {
            $report['number_of_injuries'] = (int) $report['number_of_injuries'];
        }
    
        // Debugging log
        logger()->info('Sanitized Report:', $report);
    }
    
    return $this->response->setJSON($reports);
}

}
