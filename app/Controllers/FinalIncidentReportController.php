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
        return redirect()->to('rescue/final-incident-report');
    }

    public function delete($id)
    {
        $this->finalIncidentReportModel->delete($id);
        return redirect()->to('rescue/final-incident-report');
    }

    public function exportPdf($id)
    {
        $report = $this->finalIncidentReportModel->find($id);
        if (!$report) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
        }

        // Load PDF library and configure
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $dompdf = new Dompdf($options);

        // Create HTML content for the PDF
        $html = view('Areport/final_incident_report/pdf', ['report' => $report]);

        // Load content and render
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('report_' . $report['final_report_id'] . '.pdf', ['Attachment' => true]);
    }

    public function exportExcel($id)
    {
        $report = $this->finalIncidentReportModel->find($id);
        if (!$report) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $sheet->setCellValue('A1', 'Community Report ID');
        $sheet->setCellValue('B1', 'Full Name');
        $sheet->setCellValue('C1', 'Address');
        $sheet->setCellValue('D1', 'Cause of Fire');
        // Add other columns as needed...

        // Fill data
        $sheet->setCellValue('A2', $report['communityreport_id']);
        $sheet->setCellValue('B2', $report['fullName']);
        $sheet->setCellValue('C2', $report['address']);
        $sheet->setCellValue('D2', $report['cause_of_fire']);
        // Add other data as needed...

        // Create Writer
        $writer = new Xlsx($spreadsheet);
        $fileName = 'report_' . $report['final_report_id'] . '.xlsx';
        $writer->save($fileName);

        return $this->response->download($fileName, null)->setFileName($fileName);
    }

    public function previewPdf($id)
{
    $report = $this->finalIncidentReportModel->find($id);
    if (!$report) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
    }

    // Load the PDF preview view
    return view('Areport/final_incident_report/pdf', ['report' => $report]);
}

public function previewExcel($id)
{
    $report = $this->finalIncidentReportModel->find($id);
    if (!$report) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
    }

    // Create a new Spreadsheet for previewing Excel content
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header row
    $sheet->setCellValue('A1', 'Community Report ID');
    $sheet->setCellValue('B1', 'Full Name');
    $sheet->setCellValue('C1', 'Address');
    $sheet->setCellValue('D1', 'Cause of Fire');
    // Add other columns as needed...

    // Fill data
    $sheet->setCellValue('A2', $report['communityreport_id']);
    $sheet->setCellValue('B2', $report['fullName']);
    $sheet->setCellValue('C2', $report['address']);
    $sheet->setCellValue('D2', $report['cause_of_fire']);
    // Add other data as needed...

    // Prepare output for preview
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: inline; filename="report_preview.xlsx"');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

}
