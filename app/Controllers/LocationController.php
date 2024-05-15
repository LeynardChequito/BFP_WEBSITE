<?php

namespace App\Controllers;



class LocationController extends BaseController
{
    public function showUserLocation()
    {
        return view('COMPONENTS/contactus');
    }

    public function map()
    {
        return view('EMERGENCYCALL/Rescuemap');
    }

    
}
