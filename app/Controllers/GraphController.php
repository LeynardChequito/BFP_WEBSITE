<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GraphModel;

class GraphController extends BaseController
{

    protected $session;

    public function graph(){
        return view ('ACOMPONENTS/GRAPH/graph');
    }
}
