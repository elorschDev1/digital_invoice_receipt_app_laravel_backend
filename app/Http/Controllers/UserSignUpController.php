<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserSignUpController extends Controller{

    public function saveSignUpData(Request $request){
        if($request->isMethod("post")){
            $request->merge([
                "username"=>htmlspecialchars(trim($request->input("username"))),
                "useremail"=>filter_var($request->input("useremail"),FILTER_SANITIZE_EMAIL)
            ]);
            $formDataValidator=Validator::make($request->all(),[
                "username"=>["required","max:255"],
                "useremail"=>["required","email:rfc"]
            ]);
            if($formDataValidator->fails()){
                $usernameErrors=$formDataValidator->errors()->get("username");
                $useremailErrors=$formDataValidator->errors()->get("useremail");
                return response()->json([
                    "nameFieldErrors"=>$usernameErrors,
                    "emailFieldErrors"=>$useremailErrors
                ]);
            }
            else{
                $username=$request->input("username");
                $useremail=$request->input("useremail");

             $insertUserDataQuery= DB::insert("INSERT INTO registered_users(username,useremail) VALUES(?,?)",[$username,$useremail]);
              if($insertUserDataQuery)return response()->json("Great, your data has been saved.");
            }
        }

    }




}
