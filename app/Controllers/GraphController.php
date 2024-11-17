<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FinalIncidentReportModel;

class GraphController extends BaseController
{
    protected $finalIncidentReportModel;

    public function __construct()
    {
        $this->finalIncidentReportModel = new FinalIncidentReportModel();
    }

    public function graph()
    {
        return view('ACOMPONENTS/GRAPH/graph');
    }

    public function getReports()
    {
        $finalIncidentReports = $this->finalIncidentReportModel->findAll();

        if (empty($finalIncidentReports)) {
            return $this->response->setJSON([]);
        }

        $data = [
            'fireIncidents' => $this->calculateNumberOfFireIncidents($finalIncidentReports),
            'damageCosts' => $this->calculateDamageCosts($finalIncidentReports),
            'causeOfFire' => $this->calculateCauseOfFireIncidents($finalIncidentReports),
        ];

        return $this->response->setJSON($data);
    }

    private function calculateNumberOfFireIncidents($reports)
    {
        // Count the number of unique `final_report_id` for each month.
        $fireIncidents = [];
        foreach ($reports as $report) {
            $month = date('F', strtotime($report['report_date']));
            if (!isset($fireIncidents[$month])) {
                $fireIncidents[$month] = 0;
            }
            $fireIncidents[$month]++;
        }
        return $fireIncidents;
    }

    private function calculateDamageCosts($reports)
    {
        // Calculate total damage cost estimate for each month.
        $damageCosts = [];
        foreach ($reports as $report) {
            $month = date('F', strtotime($report['report_date']));
            if (!isset($damageCosts[$month])) {
                $damageCosts[$month] = 0;
            }
            $damageCosts[$month] += $report['property_damage_cost_estimate'];
        }
        return $damageCosts;
    }

    private function calculateCauseOfFireIncidents($reports)
    {
        // Count the number of occurrences of each `cause_of_fire`.
        $causeOfFire = [];
        foreach ($reports as $report) {
            $cause = $report['cause_of_fire'];
            if (!isset($causeOfFire[$cause])) {
                $causeOfFire[$cause] = 0;
            }
            $causeOfFire[$cause]++;
        }
        return $causeOfFire;
    }


    public function getReport()
    {
        $timePeriod = $this->request->getVar('timePeriod') ?? 'weekly';
        $selectedMonth = $this->request->getVar('month') ?? null;

        // Fetch all final incident reports
        $finalIncidentReports = $this->finalIncidentReportModel->findAll();

        // Calculate injuries by address based on the selected time period
        $data = $this->calculateInjuriesByAddress($finalIncidentReports, $timePeriod, $selectedMonth);

        log_message('debug', 'Graph data returned: ' . json_encode($data));

        return $this->response->setJSON($data);
    }

    private function calculateInjuriesByAddress($reports, $timePeriod, $selectedMonth = null)
    {
        $injuriesByAddress = [];

        foreach ($reports as $report) {
            $reportDate = strtotime($report['report_date']);
            $address = $report['address'];
            $year = date('Y', $reportDate);
            $month = date('F', $reportDate);
            $day = date('j', $reportDate);

            // Determine the key for grouping (weekly, monthly, yearly)
            $groupKey = '';
            switch ($timePeriod) {
                case 'weekly':
                    if ($selectedMonth && $month !== $selectedMonth) {
                        // Skip reports outside the selected month
                        break; // Exit this case block, effectively skipping the report
                    }
                    $weekNumber = ceil($day / 7); // Determine the week number
                    $groupKey = "Week $weekNumber of $month";
                    break;
            
                case 'monthly':
                    $groupKey = $month . ' ' . $year;
                    break;
            
                case 'yearly':
                    $groupKey = $year;
                    break;
            
                default:
                    throw new \InvalidArgumentException("Invalid time period: $timePeriod");
            }
            

            // Initialize if not set
            if (!isset($injuriesByAddress[$groupKey])) {
                $injuriesByAddress[$groupKey] = [];
            }
            if (!isset($injuriesByAddress[$groupKey][$address])) {
                $injuriesByAddress[$groupKey][$address] = 0;
            }

            // Add injuries to the appropriate group and address
            $injuriesByAddress[$groupKey][$address] += $report['number_of_injuries'];
        }

        return $injuriesByAddress;
    }
}
