<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SignUpController extends Controller{
    public function saveUserData(Request $request){
            if($request->isMethod("post")){
            $userName=$email="";
            $validator=Validator::make($request->all(),
            [
              "email"=>"required|email:rfc"
            ]);
            if($validator->fails()){
              $emailErrors=$validator->errors()->get("email");
              return response()->json([
                "success"=>false,
                "email_errors"=>$emailErrors
              ]);
            }
            if($validator->passes()){
              $email=$request->input("email");
              return response()->json("An email has been sent to the address $email.");
            }




    }
}
}
