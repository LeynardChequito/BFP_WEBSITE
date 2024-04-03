<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ANavigationController extends BaseController
{

    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function adminManage()
    {
        return view('ADMIN/manage');
    }

    public function adminHome()
    {
        return view('ADMIN/adminhome');
    }

    public function adminLogout()
    {
        $this->session->setFlashdata('logout_success', 'Admin Logout successful!');
        return view('ALOGIN/adminlogin');
    }
}
