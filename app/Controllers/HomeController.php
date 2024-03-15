<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }
    public function home()
    {
        return view('WEBSITE\home');
    }

    public function contactUs()
    {
        return view('COMPONENTS/contactus');
    }

    public function banner()
    {
        return view('COMPONENTS/banner');
    }

    public function achievements()
    {
        return view('WEBSITE/achievements');
    }
    public function contacts()
    {
        return view('WEBSITE/contacts');
    }
    public function activities()
    {
        return view('WEBSITE/activities');
    }



    public function logout()
    {
       $this->session->setFlashdata('logout_success', 'Logout successful!');

            return view('LOGIN/login');
        
    }
}
