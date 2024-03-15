<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AhomeController extends BaseController
{

    public function adminManage()
    {
        return view('ADMIN/manage');
    }

    public function adminBanner()
    {
        return view('ADMIN/adminbanner'); 
    }

}
