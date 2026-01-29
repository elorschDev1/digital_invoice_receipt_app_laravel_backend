<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MobileAPIController extends Controller{
    public function handleMobileData(Request $request){
        if($request->isMethod("post")){
            return response()->json(["message"=>"Hi we have received your request"]);
        }

    }

}
