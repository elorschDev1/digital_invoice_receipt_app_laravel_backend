<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ClientSignUpController extends Controller{
    public function saveClientData(Request $request){
        if($request->isMethod("post")){
            $paymentStatus="Unpaid";
            $businessWebsite=$request->input("businessWebsite");
            if($request->has("businessWebsite")&&$request->filled("businessWebsite")===false)$businessWebsite="N/A";
            $subscriptionPlan="None";  
            $request->merge([
                "username"=>htmlspecialchars(trim($request->input("username"))),
                "useremail"=>filter_var($request->input("useremail"),FILTER_SANITIZE_EMAIL),
                "phoneValue"=>htmlspecialchars(trim($request->input("phoneValue"))),
                "businessWebsite"=>filter_var($request->input("businessWebsite"),FILTER_SANITIZE_URL),
                "userpassword"=>Hash::make($request->input("userpassword")),
            ]);
            $formValidator=Validator::make($request->all(),[
                "username"=>["required","max:255"],
                "useremail"=>["required","unique:registered_users,business_email"],
                "phoneValue"=>["required"],
                "businessWebsite"=>["url","active_url"],
                "userpassword"=>["required"]
            ]);
            try{
            if($formValidator->fails()){
                $usernameErrors=$formValidator->errors()->get("username");
                $userEmailErrors=$formValidator->errors()->get("useremail");
                $phoneValueErrors=$formValidator->errors()->get("phoneValue");
                $businessWebsiteErrors=$formValidator->errors()->get("businessWebsite");
                $userpasswordErrors=$formValidator->errors()->get("userpassword");
                return response()->json([
                    "userNameErrors"=>$usernameErrors,
                    "userEmailErrors"=>$userEmailErrors,
                    "phoneValueErrors"=>$phoneValueErrors,
                    "businessWebsiteErrors"=>$businessWebsiteErrors,
                    "userpasswordErrors"=>$userpasswordErrors
                ]);
            }
            if($formValidator->passes()){
                $username=$request->input("username");
                $useremail=$request->input("useremail");
                $phoneValue=$request->input("phoneValue");
                $businessWebsite=$request->input("businessWebsite");
                $userpassword=$request->input("userpassword");
                $userDataExists=DB::table("registered_users")
                                ->where("business_email",$useremail)
                                ->exists();
                if($userDataExists){
                    return response()->json([
                            "userEmailErrors"=>"The useremail has already been taken."
                        ]);
                }
                else{
                $saveUserDataQuery=DB::table("registered_users")->insert([
                    "business_name"=>$username,
                    "business_email"=>$useremail,
                    "business_phone"=>$phoneValue,
                    "business_website"=>$businessWebsite,
                    "business_password"=>$userpassword,
                    "subscription_plan"=>$subscriptionPlan,
                    "payment_status"=>$paymentStatus
                ]);
                 if($saveUserDataQuery)return response()->json("Data saved.");
                 } 
            }
        }catch(QueryException $e){
            return response()->json(["Error: "=>"Unexpected error".$e->getMessage()]);
        }
    }
    }  
}
