<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NewsModel;
use CodeIgniter\Pager\Pager;
class NewsController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function news()
    {
        $newsModel = new NewsModel();

        $data = [
            'news' => $newsModel->paginate(6),
            'pager' => $newsModel->pager,
        ];

        return view('NEWS/NewsView', $data);
    }

    public function show($slug)
    {
        $newsModel = new NewsModel();

        $data = [
            'news' => $newsModel->where('slug', $slug)->first(),
        ];

        if (empty($data['news'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the news item: ' . $slug);
        }

        return view('NEWS/NewsContent', $data);
    }

    public function newscreate()
    {
        $newsModel = new NewsModel(); 
        $perPage = 5; // Number of news items per page

        $data = [
            'news' => $newsModel->paginate($perPage, 'default'),
            'pager' => $newsModel->pager,
        ];

        // If there's a selected news ID, pass it to the view
        $data['selected_news_id'] = $this->request->getPost('selected_news_id'); 
        $data['news_data'] = isset($data['selected_news_id']) ? $newsModel->find($data['selected_news_id']) : [];

        return view('ACOMPONENTS/NEWS/newsmaincontent', $data);
    }

public function store()
{
    helper(['form', 'url', 'session']);

    $rules = [
        'title' => 'required|max_length[255]',
        'content' => 'required',
        'image' => 'uploaded[image]|max_size[image,5000]|mime_in[image,image/jpeg,image/png,image/heic,image/jpg]|ext_in[image,png,jpg,jpeg,heic]',
    ];

    if ($this->validate($rules)) {
        $newsModel = new NewsModel();

        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true);

        // Handle multiple image uploads
        $images = $this->request->getFiles();
        $imagePaths = [];

        if (isset($images['image'])) {
            foreach ($images['image'] as $image) {
                if ($image->isValid() && !$image->hasMoved()) {
                    $imageFileName = $image->getRandomName();
                    // Attempt to move the uploaded file to the desired directory
                    if ($image->move(ROOTPATH . 'public/newsphoto', $imageFileName)) {
                        $imagePaths[] = $imageFileName;
                    } else {
                        // Handle the error if the file could not be moved
                        return redirect()->back()->with('error', 'Failed to save one or more images.');
                    }
                }
            }
        }

        // Ensure the image paths are joined correctly
        $imageString = implode(',', $imagePaths);

        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $this->request->getPost('content'),
            'image' => $imageString, // Store images as a comma-separated string
        ];

        // Insert the news data into the database
        if ($newsModel->insert($data)) {
            $this->session->setFlashdata('success', 'News created successfully!');
            return redirect()->to('newscreate');
        } else {
            // Handle the error if the news data could not be inserted
            return redirect()->back()->with('error', 'Failed to save the news data.');
        }
    } else {
        // Handle validation errors
        $data['validation'] = $this->validator;
        return view('ACOMPONENTS/NEWS/newsmaincontent', $data);
    }
}


    public function edit($news_id)
    {
        $newsModel = new NewsModel();
    
        $news = $newsModel->find($news_id);
    
        if (empty($news)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the news item with ID: ' . $news_id);
        }
    
        $data = [
            'news' => $news,
            'selected_news_id' => $news_id,
        ];
    
        return view('ACOMPONENTS/NEWS/newsmaincontent', $data); 
    }

public function update()
{
    helper(['form', 'url', 'session']);
    
    $rules = [
        'title' => 'required|max_length[255]',
        'content' => 'required',
        'image' => 'max_size[image,5000]|mime_in[image,image/jpeg,image/png,image/heic,image/jpg]|ext_in[image,png,jpg,jpeg,heic]',
    ];
    
    if ($this->validate($rules)) {
        $newsModel = new NewsModel();
    
        $news_id = $this->request->getPost('news_id'); // Get news_id from the form

        $newsData = [
            'title' => $this->request->getPost('title'),
            'slug' => url_title($this->request->getPost('title'), '-', true),
            'content' => $this->request->getPost('content'),
        ];

        // Handle multiple or single image uploads
        $images = $this->request->getFiles();
        $imagePaths = [];

        if (isset($images['image']) && $images['image'][0]->isValid()) {
            foreach ($images['image'] as $image) {
                if ($image->isValid() && !$image->hasMoved()) {
                    $imageFileName = $image->getRandomName();
                    $image->move(ROOTPATH . 'public/newsphoto', $imageFileName);
                    $imagePaths[] = $imageFileName;
                }
            }

            // If new images were uploaded, update the image field in the database
            if (!empty($imagePaths)) {
                $newsData['image'] = implode(',', $imagePaths);
            }
        }

        // Update the news
        $newsModel->update($news_id, $newsData);
    
        $this->session->setFlashdata('success', 'News updated successfully!');
    
        return redirect()->to('newscreate'); 
    } else {
        $data['validation'] = $this->validator;
        return view('ACOMPONENTS/NEWS/newsmaincontent', $data); 
    }
}


    public function delete($news_id)
    {
        $newsModel = new NewsModel();

        // Check if the news item exists
        $news = $newsModel->find($news_id);

        if (empty($news)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the news item with ID: ' . $news_id);
        }

        // Delete the news item
        $newsModel->delete($news_id);

        $this->session->setFlashdata('success', 'News deleted successfully!');

        return redirect()->to(base_url('newscreate')); // Redirect to the appropriate route
    }
}
