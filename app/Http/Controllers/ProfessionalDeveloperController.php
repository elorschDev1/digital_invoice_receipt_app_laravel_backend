<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfessionalDeveloperController extends Controller{
    public function developerInformation(){
        return view("pages.developer");
    }
}
