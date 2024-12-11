<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FinalIncidentReportModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FinalIncidentReportController extends BaseController
{
    protected $finalIncidentReportModel;
    protected $rescuerReportModel;

    public function __construct()
    {
        $this->finalIncidentReportModel = new FinalIncidentReportModel();
    }

    public function finalreport()
    {
        $data['reports'] = $this->finalIncidentReportModel->findAll();
        return view('Areport/final_incident_report/finalreport', $data);
    }

    public function create()
    {
        return view('Areport/final_incident_report/create');
    }

    public function store()
    {
        $data = $this->request->getPost();
    
        // Check for duplicate entry
        $existingReport = $this->finalIncidentReportModel
            ->where('rescuer_name', $data['rescuer_name'])
            ->where('address', $data['address'])
            ->where('report_date', $data['report_date'])
            ->first();
    
        if ($existingReport) {
            return redirect()->back()->with('error', 'Duplicate report already exists for this rescuer, address, and date.');
        }
    
        // Save new report if no duplicate found
        $this->finalIncidentReportModel->save($data);
    
        return redirect()->to('rescuer/final-incident-report')->with('success', 'Report saved successfully!');
    }
    

    public function edit($id)
    {
        $data['report'] = $this->finalIncidentReportModel->find($id);
    if (!$data['report']) {
        // Handle the case where the report is not found
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
    }
        return view('Areport/final_incident_report/edit', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $this->finalIncidentReportModel->update($id, $data);
        return redirect()->to('rescuer/final-incident-report');
    }

    public function delete($id)
    {
        $this->finalIncidentReportModel->delete($id);
        return redirect()->to('rescuer/final-incident-report');
    }

public function exportPdf($id)
{
    date_default_timezone_set('Asia/Manila');

    $report = $this->finalIncidentReportModel->find($id);
    if (!$report) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
    }

    // Generate Base64-encoded logo
    $path = FCPATH . 'bfpcalapancity/public/design/logo.png'; // Ensure this path points to your logo file
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    } else {
        // Fallback for missing logo
        $base64Logo = ''; // Leave empty or set a placeholder if needed
    }

    // Configure Dompdf
    $options = new Options();
    $options->set('isRemoteEnabled', true); // Enable remote resources
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);

    // Render the HTML with the Base64 logo passed to the view
    $html = view('Areport/final_incident_report/pdf', [
        'report' => $report,
        'base64Logo' => $base64Logo, // Pass the Base64 logo to the view
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('Folio', 'portrait');
    $dompdf->render();

    // Output the PDF
    $dompdf->stream('Final Incident Report No.' . $report['final_report_id'] . '.pdf', ['Attachment' => true]);
}


    public function exportExcel($id)
    {
        date_default_timezone_set('Asia/Manila');

        $report = $this->finalIncidentReportModel->find($id);
        if (!$report) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $sheet->setCellValue('A1', 'Report ID')
              ->setCellValue('B1', 'Rescuer Name')
              ->setCellValue('C1', 'Address')
              ->setCellValue('D1', 'Report Date')
              ->setCellValue('E1', 'Start Time')
              ->setCellValue('F1', 'End Time')
              ->setCellValue('G1', 'Cause of Fire')
              ->setCellValue('H1', 'Property Damage Cost');

        // Add data
        $sheet->setCellValue('A2', $report['final_report_id'])
              ->setCellValue('B2', $report['rescuer_name'])
              ->setCellValue('C2', $report['address'])
              ->setCellValue('D2', $report['report_date'])
              ->setCellValue('E2', $report['start_time'])
              ->setCellValue('F2', $report['end_time'])
              ->setCellValue('G2', $report['cause_of_fire'])
              ->setCellValue('H2', number_format((float)$report['property_damage_cost_estimate'], 2));

        // Style header row
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '007bff']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Auto-size columns
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Save and download
        $writer = new Xlsx($spreadsheet);
        $filePath = WRITEPATH . 'exports/Final_Incident_Report_' . $report['final_report_id'] . '.xlsx';
        $writer->save($filePath);

        return $this->response->download($filePath, null)->setFileName('Final_Incident_Report_' . $report['final_report_id'] . '.xlsx');
    }


public function previewPdf($id)
{
    date_default_timezone_set('Asia/Manila');

    $report = $this->finalIncidentReportModel->find($id);
    if (!$report) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
    }

    // Generate Base64-encoded logo
    $path = FCPATH . 'bfpcalapancity/public/design/logo.png'; // Ensure this path is correct
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    } else {
        // Handle missing image gracefully
        $base64Logo = ''; // You can add a placeholder here
    }

    // Load PDF library and configure
    $options = new Options();
    $options->set('isRemoteEnabled', true); // Enable remote images
    $dompdf = new Dompdf($options);

    // Pass the report and Base64 logo to the view
    $html = view('Areport/final_incident_report/pdf', [
        'report' => $report,
        'base64Logo' => $base64Logo
    ]);

    // Load content and render
    $dompdf->loadHtml($html);
    $dompdf->setPaper('Folio', 'portrait');
    $dompdf->render();

    // Stream the PDF inline (same tab)
    $dompdf->stream('Final Incident Report No.' . $report['final_report_id'] . '.pdf', [
        'Attachment' => false // Inline view
    ]);

    // Prevent further processing
    exit;
}

public function previewExcel($id)
{
    date_default_timezone_set('Asia/Manila');

    $report = $this->finalIncidentReportModel->find($id);
    if (!$report) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set column headers and populate data
    $sheet->setCellValue('A1', 'Report ID')->setCellValue('B1', 'Rescuer Name')->setCellValue('C1', 'Address');
    $sheet->setCellValue('A2', $report['final_report_id'])->setCellValue('B2', $report['rescuer_name'])->setCellValue('C2', $report['address']);

    // Stream the Excel file inline
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: inline; filename="report_preview.xlsx"');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}



}
