<?php

namespace App\Controllers;

use App\Models\CarouselModel;
use App\Controllers\BaseController;
use CodeIgniter\Pager\Pager;

class CarouselController extends BaseController
{
    protected $carouselModel;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->carouselModel = new CarouselModel();
    }

    // Show the form for managing images
    public function image()
    {
        $perPage = 5; // Number of items per page
        $imageSources = $this->carouselModel->paginate($perPage); // Get paginated data
        $pager = $this->carouselModel->pager; // Get the pagination object
    
        return view('ACOMPONENTS/CAROUSEL/managecarousel', [
            'imageSources' => $imageSources,
            'pager' => $pager
        ]);
    }
    

    // Store a new image
    public function store()
    {
        $files = $this->request->getFiles(); // Get all uploaded files
    
        if ($files) {
            foreach ($files['image_source'] as $file) {
                // Check if the file is valid and has been uploaded
                if ($file->isValid() && !$file->hasMoved()) {
                    // Define the path to the public/images directory
                    $filePath = 'public/images/' . $file->getName();
        
                    // Move the file to the public/images directory
                    $file->move('public/images');
        
                    // Save the image URL in the database
                    $this->carouselModel->save(['image_url' => $filePath]);
                }
            }
            return redirect()->to('/carousel')->with('status', 'Images added successfully!');
        }
    
        return redirect()->to('/carousel')->with('status', 'Failed to add images.');
    }
    
    // Show the edit form
    public function edit($id)
    {
        $imageSource = $this->carouselModel->find($id);
        return view('ACOMPONENTS/CAROUSEL/editcarousel', ['imageSource' => $imageSource, 'id' => $id]);
    }

    // Update an existing image
    public function update($id)
    {
        $file = $this->request->getFile('image_source');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Get the current image path to delete the old file
            $currentImage = $this->carouselModel->find($id);

            // Delete the old image from the public/images directory
            if (file_exists($currentImage['image_url'])) {
                unlink($currentImage['image_url']);
            }

            // Define the new file path
            $newFilePath = 'public/images/' . $file->getName();

            // Move the new file to the public/images directory
            $file->move('public/images');

            // Update the image URL in the database
            $this->carouselModel->update($id, ['image_url' => $newFilePath]);

            return redirect()->to('/carousel')->with('status', 'Image updated successfully!');
        }

        return redirect()->to('/carousel')->with('status', 'Failed to update image.');
    }

    // Delete an image
    public function delete($id)
    {
        $imageSource = $this->carouselModel->find($id);

        if ($this->carouselModel->delete($id)) {
            // Delete the image from the public/images directory
            if (file_exists($imageSource['image_url'])) {
                unlink($imageSource['image_url']);
            }

            return redirect()->to('/carousel')->with('status', 'Image deleted successfully!');
        }

        return redirect()->to('/carousel')->with('status', 'Failed to delete image.');
    }
}
