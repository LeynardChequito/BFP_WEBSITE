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
        $newsModel = new NewsModel(); // Assuming you have a NewsModel
        $perPage = 5; // Number of news items per page

        $data = [
            'news' => $newsModel->paginate($perPage, 'default'),
            'pager' => $newsModel->pager,
        ];

        $data['news'] = $newsModel->findAll();
    
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

    $messages = [
        'title' => [
            'required' => 'News Title is required.',
            'max_length' => 'News Title should not exceed 255 characters.',
        ],
        'content' => [
            'required' => 'News Content is required.',
        ],
        'image' => [
            'uploaded' => 'News Image is required.',
            'max_size' => 'News Image size should not exceed 5MB.',
            'mime_in' => 'Invalid file type for News Image. Please upload a valid image file.',
        ],
    ];

    if ($this->validate($rules, $messages)) {
        $newsModel = new NewsModel();

        // Generate a unique slug
        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true);
        $existingSlug = $newsModel->where('slug', $slug)->first();
        if ($existingSlug) {
            $slug .= '-' . uniqid(); // Append a unique identifier
        }

        $imageFile = $this->request->getFile('image');
        $imageFileName = $imageFile->getRandomName();
        $imageFile->move(ROOTPATH . 'public/newsphoto', $imageFileName);

        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $this->request->getPost('content'),
            'image' => $imageFileName,
        ];

        $newsModel->insert($data);

        $this->session->setFlashdata('success', 'News created successfully!');

        return redirect()->to('newscreate');
    } else {
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
        
        $messages = [
            'title' => [
                'required' => 'News Title is required.',
                'max_length' => 'News Title should not exceed 255 characters.',
            ],
            'content' => [
                'required' => 'News Content is required.',
            ],
            'image' => [
                'max_size' => 'News Image size should not exceed 5MB.',
                'mime_in' => 'Invalid file type for News Image. Please upload a valid image file.',
            ],
        ];
        
        if ($this->validate($rules, $messages)) {
            $newsModel = new NewsModel();
        
            $news_id = $this->request->getPost('news_id'); // Get news_id from the form

            $newsData = [
                'title' => $this->request->getPost('title'),
                'slug' => url_title($this->request->getPost('title'), '-', true),
                'content' => $this->request->getPost('content'),
            ];

            // Check if a new image is uploaded
            $newImage = $this->request->getFile('image');
            if ($newImage->isValid()) {
                $imageFileName = $newImage->getRandomName();
                $newImage->move(ROOTPATH . 'public/newsphoto', $imageFileName);
                $newsData['image'] = $imageFileName;
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
