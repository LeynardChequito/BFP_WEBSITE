<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MainFolderModel;
use App\Models\SubFolderModel;
use App\Models\FileModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class FolderController extends BaseController
{
public function navigationFolders()
{
    date_default_timezone_set('Asia/Manila'); // Set timezone to Asia/Manila

    $mainFolderModel = new MainFolderModel();
    $subFolderModel = new SubFolderModel();
    $fileModel = new FileModel();

    // Retrieve all main folders
    $mainFolders = $mainFolderModel->findAll();

    // Fetch and attach subfolders for each main folder
    foreach ($mainFolders as &$mainFolder) {
        $mainFolder['subfolders'] = $subFolderModel->where('main_folder_id', $mainFolder['main_folder_id'])->findAll();
    }

    // Fetch advertisement files with main folder info
    $advertisementFiles = $fileModel
        ->select('files.file_id, files.title, files.file_path, main_folders.name as main_folder_name, files.created_at')
        ->join('sub_folders', 'sub_folders.sub_folder_id = files.sub_folder_id')
        ->join('main_folders', 'main_folders.main_folder_id = sub_folders.main_folder_id')
        ->orderBy('files.created_at', 'DESC') // Order by latest
        ->limit(5) // Limit to 5 for display
        ->findAll();

    // Convert `created_at` and other date fields to Philippine Time
    foreach ($advertisementFiles as &$file) {
        $dateTime = new \DateTime($file['created_at'], new \DateTimeZone('UTC'));
        $dateTime->setTimezone(new \DateTimeZone('Asia/Manila'));
        $file['created_at'] = $dateTime->format('Y-m-d H:i:s');
    }

    // Pass data to the view
    return view('WEBSITE/home', ['mainFolders' => $mainFolders, 'advertisementFiles' => $advertisementFiles]);
}

public function editFile($fileId)
{
    $fileModel = new FileModel();
    $mainFolderModel = new MainFolderModel();
    $subFolderModel = new SubFolderModel();

    // Retrieve the file details
    $file = $fileModel->find($fileId);
    if (!$file) {
        return redirect()->back()->with('error', 'File not found.');
    }

    // Retrieve all main folders and their subfolders for selection
    $mainFolders = $mainFolderModel->findAll();
    foreach ($mainFolders as &$mainFolder) {
        $mainFolder['subfolders'] = $subFolderModel->where('main_folder_id', $mainFolder['main_folder_id'])->findAll();
    }

    // Pass data to the view
    return view('folder/edit_file', [
        'file' => $file,
        'mainFolders' => $mainFolders,
    ]);
}

public function updateFile($fileId)
{
    $fileModel = new FileModel();
    $file = $fileModel->find($fileId);

    if (!$file) {
        return redirect()->back()->with('error', 'File not found.');
    }

    $title = $this->request->getPost('title');
    $description = $this->request->getPost('description');
    $subFolderId = $this->request->getPost('sub_folder');

    // Update file details
    $fileModel->update($fileId, [
        'title' => $title,
        'description' => $description,
        'sub_folder_id' => $subFolderId,
    ]);

    return redirect()->to('/folders/manage')->with('message', 'File updated successfully.');
}

    public function viewFolder($mainFolder, $subFolder = null)
    {
        $directory = FCPATH . "bfpcalapancity/public/gallery/{$mainFolder}";
        if ($subFolder) {
            $directory .= "/{$subFolder}";
        }

        $files = array_map('basename', glob("{$directory}/*.*"));

        return view('home', [
            'mainFolder' => $mainFolder,
            'subFolder' => $subFolder,
            'files' => $files
        ]);
    }

    public function manageFolderFiles()
    {
        $mainFolderModel = new MainFolderModel();
        $subFolderModel = new SubFolderModel();
        $fileModel = new FileModel();

        $data['mainFolders'] = $mainFolderModel->findAll();
        foreach ($data['mainFolders'] as &$mainFolder) {
            $mainFolder['subfolders'] = $subFolderModel->where('main_folder_id', $mainFolder['main_folder_id'])->findAll();
            foreach ($mainFolder['subfolders'] as &$subFolder) {
                $subFolder['files'] = $fileModel->where('sub_folder_id', $subFolder['sub_folder_id'])->findAll();
            }
        }

        return view('folder/manage_folder_files', $data);
    }
    
    public function loadNavigationData()
{
    $mainFolderModel = new MainFolderModel();
    $subFolderModel = new SubFolderModel();

    // Fetch all main folders
    $mainFolders = $mainFolderModel->findAll();

    // Attach subfolders to each main folder
    foreach ($mainFolders as &$mainFolder) {
        $mainFolder['subfolders'] = $subFolderModel->where('main_folder_id', $mainFolder['main_folder_id'])->findAll();
    }

    return $mainFolders;
}


public function viewSubFolders($mainFolderId)
{
    $mainFolders = $this->loadNavigationData();
    $mainFolderModel = new MainFolderModel();
    $subFolderModel = new SubFolderModel();
    $fileModel = new FileModel();

    // Retrieve all main folders with subfolders
    $mainFolders = $mainFolderModel->findAll();
    foreach ($mainFolders as &$mainFolder) {
        $mainFolder['subfolders'] = $subFolderModel->where('main_folder_id', $mainFolder['main_folder_id'])->findAll();
    }

    // Retrieve the specific main folder and its subfolders
    $mainFolder = $mainFolderModel->find($mainFolderId);
    if (!$mainFolder) {
        return redirect()->back()->with('error', 'Main folder not found.');
    }
    $subFolders = $subFolderModel->where('main_folder_id', $mainFolderId)->findAll();

    // Attach files to each subfolder
    foreach ($subFolders as &$subFolder) {
        $subFolder['files'] = $fileModel->where('sub_folder_id', $subFolder['sub_folder_id'])->findAll();
    }

    // Pass data to the view
    return view('folder/view_sub_folders', [
        'mainFolders' => $mainFolders,    // Pass all main folders with subfolders
        'mainFolder' => $mainFolder,      // Pass the current main folder
        'subFolders' => $subFolders       // Pass the current subfolders for the main folder
    ]);
}

public function fileDetails($fileId)
{
    $fileModel = new FileModel();
    $mainFolderModel = new MainFolderModel();
    $subFolderModel = new SubFolderModel();

    // Retrieve the file details
    $file = $fileModel->find($fileId);
    if (!$file) {
        return redirect()->back()->with('error', 'File not found.');
    }

    // Retrieve all main folders with their subfolders
    $mainFolders = $mainFolderModel->findAll();
    foreach ($mainFolders as &$mainFolder) {
        $mainFolder['subfolders'] = $subFolderModel->where('main_folder_id', $mainFolder['main_folder_id'])->findAll();
    }

    // Pass data to the view
    return view('folder/file_details', [
        'mainFolders' => $mainFolders, // Pass all main folders with subfolders
        'file' => $file                 // Pass the current file details
    ]);
}

    public function createFile()
    {
        $mainFolderModel = new MainFolderModel();
        $subFolderModel = new SubFolderModel();

        $data['mainFolders'] = $mainFolderModel->findAll();
        foreach ($data['mainFolders'] as &$mainFolder) {
            $mainFolder['subfolders'] = $subFolderModel->where('main_folder_id', $mainFolder['main_folder_id'])->findAll();
        }

        return view('folder/create_file', $data);
    }

 public function storeFile()
{
    $fileModel = new FileModel();
    $mainFolderModel = new MainFolderModel();
    $subFolderModel = new SubFolderModel();

    $mainFolderId = $this->request->getPost('main_folder');
    $subFolderId = $this->request->getPost('sub_folder');
    $title = $this->request->getPost('title');
    $description = $this->request->getPost('description');

    // Fetch main folder and subfolder information
    $mainFolder = $mainFolderModel->find($mainFolderId);
    $subFolder = $subFolderModel->find($subFolderId);

    if (!$mainFolder || !$subFolder) {
        return redirect()->back()->with('error', 'Invalid folder selection');
    }

    $mainFolderName = $mainFolder['name'];
    $subFolderName = $subFolder['name'];

    // Directory path
    $directoryPath = FCPATH . "../public_html/bfpcalapancity/public/gallery/{$mainFolderName}/{$subFolderName}";
    if (!is_dir($directoryPath)) {
        mkdir($directoryPath, 0777, true);
    }

    // Get uploaded files and initialize an array for file paths
    $uploadedFiles = $this->request->getFiles()['files'];
    $filePaths = [];

    foreach ($uploadedFiles as $file) {
        if ($file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getName();
            $filePath = "bfpcalapancity/public/gallery/{$mainFolderName}/{$subFolderName}/" . $fileName;

            // Move file to the directory and add to the paths array
            $file->move($directoryPath, $fileName);
            $filePaths[] = $filePath;
        }
    }

    // Check if a record already exists for this subfolder and title
    $existingFile = $fileModel->where('sub_folder_id', $subFolderId)
                              ->where('title', $title)
                              ->first();

    if ($existingFile) {
        // Update the existing record, merging old and new file paths
        $existingPaths = json_decode($existingFile['file_path'], true);
        $updatedPaths = array_merge($existingPaths, $filePaths);
        $fileModel->update($existingFile['file_id'], [
            'title' => $title,
            'description' => $description,
            'file_path' => json_encode($updatedPaths)
        ]);
    } else {
        // Save a new record with the files
        $fileModel->save([
            'sub_folder_id' => $subFolderId,
            'title' => $title,
            'description' => $description,
            'file_path' => json_encode($filePaths)
        ]);
    }

    return redirect()->to('/folders/manage')->with('message', 'Files uploaded or updated successfully.');
}

    public function createFolder()
    {
        return view('folder/create_folder');
    }

   public function storeFolder()
{
    $mainFolderModel = new MainFolderModel();
    $subFolderModel = new SubFolderModel();

    $mainFolderName = $this->request->getPost('main_folder_name');
    $subFolderName = $this->request->getPost('sub_folder_name');

    // Check if the main folder exists
    $mainFolder = $mainFolderModel->where('name', $mainFolderName)->first();

    if ($mainFolder) {
        // If the main folder exists, update its name (if needed)
        $mainFolderId = $mainFolder['main_folder_id'];
        $mainFolderModel->update($mainFolderId, ['name' => $mainFolderName]);
    } else {
        // Otherwise, create a new main folder
        $mainFolderModel->save(['name' => $mainFolderName]);
        $mainFolderId = $mainFolderModel->insertID;

        // Create the main folder directory if it doesn't exist
        $mainFolderPath = FCPATH . 'bfpcalapancity/public/gallery/' . $mainFolderName;
        if (!is_dir($mainFolderPath)) {
            mkdir($mainFolderPath, 0777, true);
        }
    }

    // Check if the subfolder exists within this main folder
    $subFolder = $subFolderModel->where('main_folder_id', $mainFolderId)->where('name', $subFolderName)->first();

    if ($subFolder) {
        // If the subfolder exists, update it
        $subFolderModel->update($subFolder['sub_folder_id'], ['name' => $subFolderName]);
    } else {
        // Otherwise, create a new subfolder
        $subFolderModel->save(['main_folder_id' => $mainFolderId, 'name' => $subFolderName]);

        // Create the subfolder directory if it doesn't exist
        $subFolderPath = FCPATH . 'bfpcalapancity/public/gallery/' . $mainFolderName . '/' . $subFolderName;
        if (!is_dir($subFolderPath)) {
            mkdir($subFolderPath, 0777, true);
        }
    }

    return redirect()->to('/folders/createFile')->with('message', 'Folder structure created or updated successfully.');
}


    public function toggleVisibility($file_id)
    {
        $fileModel = new FileModel();
        $file = $fileModel->find($file_id);
        if ($file) {
            $fileModel->update($file_id, ['is_visible' => !$file['is_visible']]);
        }
        return redirect()->back()->with('message', 'File visibility updated');
    }

   public function deleteFile($fileId)
{
    $fileModel = new FileModel();
    $file = $fileModel->find($fileId);

    if ($file) {
        // Remove the file from the filesystem
        $filePath = FCPATH . $file['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the file record from the database
        $fileModel->delete($fileId);

        return $this->response->setJSON(['status' => 'success', 'message' => 'File deleted successfully']);
    } else {
        return $this->response->setJSON(['status' => 'error', 'message' => 'File not found'], 404);
    }
}



public function editMainFolder($mainFolderId)
{
    $mainFolderModel = new MainFolderModel();
    $mainFolder = $mainFolderModel->find($mainFolderId);

    if (!$mainFolder) {
        return redirect()->back()->with('error', 'Main Folder not found.');
    }

    return view('folder/edit_main_folder', ['mainFolder' => $mainFolder]);
}

public function updateMainFolder($mainFolderId)
{
    $mainFolderModel = new MainFolderModel();
    $name = $this->request->getJSON()->name;

    if ($mainFolderModel->update($mainFolderId, ['name' => $name])) {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Main folder updated successfully']);
    } else {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update main folder'], 500);
    }
}

public function deleteMainFolder($mainFolderId)
{
    $mainFolderModel = new MainFolderModel();
    $subFolderModel = new SubFolderModel();
    $fileModel = new FileModel();

    // Retrieve main folder information
    $mainFolder = $mainFolderModel->find($mainFolderId);
    if (!$mainFolder) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Main folder not found'], 404);
    }

    // Define the main folder path based on the provided link structure
    $mainFolderPath = FCPATH . "../public_html/bfpcalapancity/public/gallery/{$mainFolder['name']}";

    try {
        // Retrieve and delete all subfolders and associated files
        $subFolders = $subFolderModel->where('main_folder_id', $mainFolderId)->findAll();
        foreach ($subFolders as $subFolder) {
            $files = $fileModel->where('sub_folder_id', $subFolder['sub_folder_id'])->findAll();
            foreach ($files as $file) {
                $filePath = FCPATH . "../public_html/{$file['file_path']}";
                if (file_exists($filePath)) {
                    if (!unlink($filePath)) {
                        throw new \Exception("Failed to delete file: {$file['file_path']}");
                    }
                }
                $fileModel->delete($file['file_id']);
            }
            $subFolderModel->delete($subFolder['sub_folder_id']);
        }

        // Delete main folder directory and contents
        $this->deleteDirectory($mainFolderPath);

        // Delete main folder record from the database
        $mainFolderModel->delete($mainFolderId);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Main folder and all contents deleted successfully']);
    } catch (\Exception $e) {
        log_message('error', 'Failed to delete main folder: ' . $e->getMessage());
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete main folder'], 500);
    }
}


public function editSubFolder($subFolderId)
{
    $subFolderModel = new SubFolderModel();
    $subFolder = $subFolderModel->find($subFolderId);

    if (!$subFolder) {
        return redirect()->back()->with('error', 'Sub Folder not found.');
    }

    return view('folder/edit_sub_folder', ['subFolder' => $subFolder]);
}

public function updateSubFolder($subFolderId)
{
    $subFolderModel = new SubFolderModel();
    $name = $this->request->getJSON()->name;

    if ($subFolderModel->update($subFolderId, ['name' => $name])) {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Subfolder updated successfully']);
    } else {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update subfolder'], 500);
    }
}


public function deleteSubFolder($subFolderId)
{
    $subFolderModel = new SubFolderModel();
    $fileModel = new FileModel();

    // Retrieve subfolder information
    $subFolder = $subFolderModel->find($subFolderId);
    if (!$subFolder) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Subfolder not found'], 404);
    }

    // Construct the subfolder path based on provided link structure
    $subFolderPath = FCPATH . "../public_html/bfpcalapancity/public/gallery/{$subFolder['main_folder_name']}/{$subFolder['name']}";

    try {
        // Retrieve and delete all files within the subfolder
        $files = $fileModel->where('sub_folder_id', $subFolderId)->findAll();
        foreach ($files as $file) {
            $filePath = FCPATH . "../public_html/{$file['file_path']}";
            if (file_exists($filePath)) {
                if (!unlink($filePath)) {
                    throw new \Exception("Failed to delete file: {$filePath}");
                }
            }
            $fileModel->delete($file['file_id']);
        }

        // Delete the subfolder directory
        $this->deleteDirectory($subFolderPath);

        // Delete the subfolder record from the database
        $subFolderModel->delete($subFolderId);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Subfolder and all contents deleted successfully']);
    } catch (\Exception $e) {
        log_message('error', 'Failed to delete subfolder: ' . $e->getMessage());
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete subfolder'], 500);
    }
}

private function deleteDirectory($dirPath)
{
    if (!is_dir($dirPath)) {
        throw new \Exception("Directory {$dirPath} not found.");
    }

    $files = scandir($dirPath);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $filePath = "{$dirPath}/{$file}";
        if (is_dir($filePath)) {
            $this->deleteDirectory($filePath); // Recursive deletion for nested folders
        } else {
            if (!unlink($filePath)) {
                throw new \Exception("Failed to delete file: {$filePath}");
            }
        }
    }

    if (!rmdir($dirPath)) {
        throw new \Exception("Failed to delete directory: {$dirPath}");
    }
}


public function previewFile($fileId)
{
    $fileModel = new FileModel();
    $file = $fileModel->find($fileId);

    if (!$file) {
        return redirect()->back()->with('error', 'File not found.');
    }

    // Pass file details to the preview view
    return view('folder/preview_files', ['file' => $file]);
}


public function exportAsPDF($fileId)
    {
        $fileModel = new FileModel();
        $file = $fileModel->find($fileId);

        if (!$file) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Prepare the data for the view
        $data = [
            'file' => $file,
            'filePaths' => json_decode($file['file_path'], true),
        ];

        // Load the view content for the PDF
        $htmlContent = view('folder/pdf_template', $data);

        // Initialize Dompdf with options
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans'); // Use a font with Unicode support if needed
        $dompdf = new Dompdf($options);

        // Load HTML and render
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('Letter', 'portrait'); // Set paper size and orientation
        $dompdf->render();

        // Stream the generated PDF back to the browser
        $dompdf->stream($file['title'] . '.pdf', ["Attachment" => true]); // Set to `true` to download as file
    }
}
