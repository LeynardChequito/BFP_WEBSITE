<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RescuerReportModel;
use App\Models\FinalIncidentReportModel; 

class GraphController extends BaseController
{
    protected $rescuerReportModel;
    protected $finalIncidentReportModel;

    public function __construct()
    {
        $this->rescuerReportModel = new RescuerReportModel();
        $this->finalIncidentReportModel = new FinalIncidentReportModel(); 
    }


    public function graph()
    {
        return view('ACOMPONENTS/GRAPH/graph');
    }

 
    public function getReports()
    {
        
        $rescuerReports = $this->rescuerReportModel->findAll();
        $finalIncidentReports = $this->finalIncidentReportModel->findAll();

        // Merge both sets of reports into one array (assuming they have similar structure)
        $reports = array_merge($rescuerReports, $finalIncidentReports);

        // If no reports found, return an empty array
        if (empty($reports)) {
            return $this->response->setJSON([]); // Return an empty array if no reports are found
        }

        return $this->response->setJSON($reports); // Return all merged reports as JSON
    }

    public function getReport()
    {
        // Get the selected time period and month (if applicable)
        $timePeriod = $this->request->getVar('timePeriod') ?? 'weekly';
        $selectedMonth = $this->request->getVar('month') ?? null;

        // Fetch all data from RescuerReportModel and FinalIncidentReportModel
        $rescuerReports = $this->rescuerReportModel->findAll();
        $finalIncidentReports = $this->finalIncidentReportModel->findAll();

        // Merge both sets of reports into one array
        $reports = array_merge($rescuerReports, $finalIncidentReports);

        // Process data based on the selected time period
        switch ($timePeriod) {
            case 'weekly':
                $data = $this->calculateWeeklyInjuries($reports, $selectedMonth);
                break;
            case 'monthly':
                $data = $this->calculateMonthlyInjuries($reports);
                break;
            case 'yearly':
                $data = $this->calculateYearlyInjuries($reports);
                break;
            default:
                // If time period is not valid, default to weekly
                $data = $this->calculateWeeklyInjuries($reports, $selectedMonth);
        }

        // Return the processed data as JSON
        return $this->response->setJSON($data);
    }

    // Function to calculate weekly injuries for a specific month
    private function calculateWeeklyInjuries($reports, $selectedMonth)
    {
        // Initialize an array to store injuries by week
        $weeklyInjuries = [
            'Week 1' => 0,
            'Week 2' => 0,
            'Week 3' => 0,
            'Week 4' => 0,
            'Week 5' => 0
        ];

        // Loop through all the reports
        foreach ($reports as $report) {
            // Convert the report date to a timestamp
            $reportDate = strtotime($report['report_date']);

            // Filter by the selected month if provided
            if ($selectedMonth) {
                $reportMonth = date('F', $reportDate); // Get the month name
                if ($reportMonth !== $selectedMonth) {
                    continue; // Skip if the report is not from the selected month
                }
            }

            // Determine the week number within the month
            $day = date('j', $reportDate); // Get the day of the month (1-31)

            // Calculate the week based on the day of the month
            $weekNumber = ceil($day / 7);

            // Map the week number to "Week 1", "Week 2", etc.
            $weekLabel = 'Week ' . $weekNumber;

            // Increment the injuries for that week
            if (isset($weeklyInjuries[$weekLabel])) {
                $weeklyInjuries[$weekLabel] += $report['number_of_injuries'];
            }
        }

        return $weeklyInjuries;
    }

    // Function to calculate monthly injuries (using month names)
    private function calculateMonthlyInjuries($reports)
    {
        $monthlyInjuries = [
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0
        ];

        foreach ($reports as $report) {
            $month = date('F', strtotime($report['report_date'])); // Get the full month name (January, February, etc.)
            $monthlyInjuries[$month] += $report['number_of_injuries'];
        }

        return $monthlyInjuries;
    }

    // Function to calculate yearly injuries (grouped by year)
    private function calculateYearlyInjuries($reports)
    {
        $yearlyInjuries = [];

        foreach ($reports as $report) {
            $year = date('Y', strtotime($report['report_date'])); // Get the year (e.g., 2023)

            if (!isset($yearlyInjuries[$year])) {
                $yearlyInjuries[$year] = 0;
            }

            $yearlyInjuries[$year] += $report['number_of_injuries'];
        }

        return $yearlyInjuries;
    }
}
