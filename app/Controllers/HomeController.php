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
    public function album()
    {
        return view('WEBSITE/rank');
    }
    public function intern()
    {
        return view('WEBSITE/intern');
    }
    public function pfv()
    {
        return view('WEBSITE/pfv');
    }
    public function fdas()
    {
        return view('WEBSITE/fdas');
    }
    public function inspection()
    {
        return view('WEBSITE/inspection');
    }
}
