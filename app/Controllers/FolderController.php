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
    public function navigationfolders()
    {
        $mainFolders = $this->getFoldersInDirectory(FCPATH . 'bfpcalapancity/public/gallery');
        
        return view('WEBSITE/home' || 'WEBSITE/site', ['mainFolders' => $mainFolders]);
    }
    

    private function getFoldersInDirectory($directory)
    {
        $folders = [];
        if (is_dir($directory)) {
            $mainFolders = array_filter(glob($directory . '/*'), 'is_dir');
            foreach ($mainFolders as $folder) {
                $folderName = basename($folder);
                $subFolders = array_filter(glob($folder . '/*'), 'is_dir');
                $folders[$folderName] = array_map('basename', $subFolders);
            }
        }
        return $folders;
    }
    

    public function viewFolder($mainFolder, $subFolder = null)
    {
        $directory = FCPATH . "bfpcalapancity/public/gallery/$mainFolder";
        if ($subFolder) {
            $directory .= "/$subFolder";
        }

        $files = array_map('basename', glob("$directory/*.*"));
        return view('folder/folder_contents', [
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

        // Fetch main folder and subfolder names
        $mainFolder = $mainFolderModel->find($mainFolderId);
        $subFolder = $subFolderModel->find($subFolderId);

        if (!$mainFolder || !$subFolder) {
            return redirect()->back()->with('error', 'Invalid folder selection');
        }

        $mainFolderName = $mainFolder['name'];
        $subFolderName = $subFolder['name'];

        // Create the directory path if it doesn't exist
        $directoryPath = FCPATH . "bfpcalapancity/public/gallery/{$mainFolderName}/{$subFolderName}";
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        // Get all uploaded files and restrict to images and videos only
        $files = $this->request->getFiles()['files'];
        $filePaths = []; // Array to store each file path

        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                // Check if the file is an image or video
                $fileType = $file->getMimeType();
                if (strpos($fileType, 'image/') === 0 || strpos($fileType, 'video/') === 0) {
                    // Define file path and move the file
                    $filePath = "bfpcalapancity/public/gallery/{$mainFolderName}/{$subFolderName}/" . $file->getName();
                    $file->move($directoryPath, $file->getName());

                    // Store each file path in the array
                    $filePaths[] = $filePath;
                }
            }
        }

        // Join all file paths into a single string, separated by commas
        $filePathsString = implode(',', $filePaths);

        // Save a single record in the database with concatenated file paths
        $fileModel->save([
            'sub_folder_id' => $subFolderId,
            'title' => $title,
            'description' => $description,
            'file_path' => $filePathsString
        ]);

        return redirect()->to('/folders/manage')->with('message', 'Files uploaded successfully as a single record');
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

        // Save main folder in the database
        $mainFolderModel->save(['name' => $mainFolderName]);
        $mainFolderId = $mainFolderModel->insertID;

        // Create main folder directory
        mkdir(FCPATH . 'bfpcalapancity/public/gallery/' . $mainFolderName, 0777, true);

        // Save subfolder in the database
        $subFolderModel->save([
            'main_folder_id' => $mainFolderId,
            'name' => $subFolderName
        ]);

        // Create subfolder directory
        mkdir(FCPATH . 'bfpcalapancity/public/gallery/' . $mainFolderName . '/' . $subFolderName, 0777, true);

        return redirect()->to('/folders/createFile')->with('message', 'Main folder and subfolder created successfully.');
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

    public function deleteFile($file_id)
    {
        $fileModel = new FileModel();
        $file = $fileModel->find($file_id);

        if ($file) {
            if (file_exists(FCPATH . $file['file_path'])) {
                unlink(FCPATH . $file['file_path']);
            }
            $fileModel->delete($file_id);
        }

        return redirect()->to('/folders/manage')->with('message', 'File deleted successfully');
    }

    public function exportFilePDF($file_id)
    {
        $fileModel = new FileModel();
        $file = $fileModel->find($file_id);

        if ($file) {
            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $dompdf = new Dompdf($options);

            $html = "<h1>{$file['title']}</h1><p>{$file['description']}</p><p>Path: {$file['file_path']}</p>";
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("File_{$file_id}.pdf", ["Attachment" => 0]);
        }
    }
}
