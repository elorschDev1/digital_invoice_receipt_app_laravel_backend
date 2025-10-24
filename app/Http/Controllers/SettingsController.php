<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller{
    public function settingsPage(){
        return view("pages.settings");
    }
}
