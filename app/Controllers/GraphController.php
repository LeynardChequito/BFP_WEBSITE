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
        return $this->response->setJSON($reports);
    }
}
