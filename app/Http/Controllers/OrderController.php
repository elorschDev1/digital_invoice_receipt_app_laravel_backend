<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller{
    public function show($id){
        return "<p>Hi, this user has the id $id</p>";
    }
    public function store(){
        return "<p>Hi there, welcome to my store.</p>";
    }
}
