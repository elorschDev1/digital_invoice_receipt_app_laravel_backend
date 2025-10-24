<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleController extends Controller{
    public function audiVehicle(){
        return view("pages.audi");
    }
    public function rangeRoverVehicle(){
        return view("pages.rangerover");
    }
}
