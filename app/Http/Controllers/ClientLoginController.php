<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
class ClientLoginController extends Controller{
    public function handleUserLogin(Request $request){
        if($request->isMethod("post")){
         try{
               $request->merge([
                "email"=>filter_var($request->input("email"),FILTER_SANITIZE_EMAIL),
                "password"=>htmlspecialchars($request->input("password"))
            ]);
            $formValidator=Validator::make($request->all(),[
                "email"=>["required","email:rfc"],
                "password"=>["required"]
            ]);
            if($formValidator->fails()){
                $emailErrors=$formValidator->errors()->get("email");
                $passwordErrors=$formValidator->errors()->get("password");
                return response()->json([
                    "emailErrors"=>$emailErrors,
                    "passwordErrors"=>$passwordErrors
                ]);
            };
            if($formValidator->passes()){
                $email=$request->input("email");
                $providedPassword=$request->input("password");
                $usersActualPassword=DB::table("registered_users")
                                     ->where("business_email",$email)
                                     ->value("business_password");
                if(!$usersActualPassword){
                    return response()->json("Account not found.");
                }
                if(Hash::check($providedPassword,$usersActualPassword)){
                    return response()->json("Correct password for this account.");
                }
                else{
                    return response()->json("Passwords do not match.");
                }
            }
         }catch(QueryException $e){
            return response()->json(["Error:"=>"Unexpected Error: ".$e->getMessage()]);
         }
        }
    }   
}
