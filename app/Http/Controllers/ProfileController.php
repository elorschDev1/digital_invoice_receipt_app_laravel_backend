<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller{
    function profilePage(){
        return view("pages.profile");
    }
}
