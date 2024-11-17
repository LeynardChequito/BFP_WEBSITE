<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AnnouncementModel;

class AnnouncementController extends BaseController
{
    protected $announcementModel;

    public function __construct()
    {
        $this->announcementModel = new AnnouncementModel();
    }

    public function announcement()
    {
        // Fetch all announcements from the database
        $data['announcements'] = $this->announcementModel->findAll(); // Use 'announcements' as the key
    
        // Log the fetched announcements for debugging (optional)
        // log_message('debug', 'Fetched announcements: ' . print_r($data['announcements'], true));
    
        // Return the view with the announcement data
        return view('ACOMPONENTS/ANNOUNCEMENTS/announcement', $data);
    }
    

    public function create()
    {
        return view('ACOMPONENTS/ANNOUNCEMENTS/create');
    }

    public function store()
    {
        // Validate the incoming request
        $validation = \Config\Services::validation();
    
        $validation->setRules([
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required',
            'image' => [
                'rules' => 'uploaded[image]|is_image[image]|max_size[image,2048]|ext_in[image,jpg,jpeg,png]',
                'label' => 'Image'
            ]
        ]);
    
        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    
        // Handle the image upload
        $file = $this->request->getFile('image');
    
        if ($file->isValid() && !$file->hasMoved()) {
            // Define the file path to save the uploaded image
            $imageName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $imageName); // Changed to 'uploads'
        } else {
            return redirect()->back()->withInput()->with('errors', ['image' => 'Image upload failed.']);
        }
    
        // Prepare the data for saving
        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'image' => $imageName,
        ];
    
        // Save the announcement
        if ($this->announcementModel->save($data)) {
            return redirect()->to('/announcements')->with('success', 'Announcement created successfully.');
        } else {
            return redirect()->back()->with('errors', $this->announcementModel->errors());
        }
    }
    

    public function edit($id)
    {
        $data['announcement'] = $this->announcementModel->find($id);
        return view('ACOMPONENTS/ANNOUNCEMENTS/edit', $data);
    }

    public function update($id)
    {
        // Validate the incoming request
        $validation = \Config\Services::validation();
    
        $validation->setRules([
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required',
            'image' => [
                'rules' => 'is_image[image]|max_size[image,2048]|ext_in[image,jpg,jpeg,png]',
                'label' => 'Image',
                'errors' => [
                    'is_image' => 'Please upload a valid image.',
                    'max_size' => 'The image size should not exceed 2MB.',
                    'ext_in' => 'The image format should be jpg, jpeg, or png.'
                ]
            ]
        ]);
    
        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    
        // Retrieve the existing announcement
        $announcement = $this->announcementModel->find($id);
        if (!$announcement) {
            return redirect()->to('/announcements')->with('errors', ['announcement' => 'Announcement not found.']);
        }
    
        // Prepare the data for updating
        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
        ];
    
        // Handle the image upload if a new image is provided
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete the old image if it exists
            if (!empty($announcement['image'])) {
                $oldImagePath = WRITEPATH . 'public/uploads' . $announcement['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Delete the old image file
                }
            }
    
            // Save the new image
            $imageName = $file->getRandomName();
            $file->move(WRITEPATH . 'public/uploads', $imageName);
            $data['image'] = $imageName; // Save the new image name in the data
        }
    
        // Update the announcement
        if ($this->announcementModel->update($id, $data)) {
            return redirect()->to('/announcements')->with('success', 'Announcement updated successfully.');
        } else {
            return redirect()->back()->with('errors', $this->announcementModel->errors());
        }
    }    

    public function delete($id)
    {
        $this->announcementModel->delete($id);
        return redirect()->to('/announcements')->with('success', 'Announcement deleted successfully.');
    }
}