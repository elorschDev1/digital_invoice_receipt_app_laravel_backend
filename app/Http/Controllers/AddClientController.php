<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class AddClientController extends Controller{
    public function addClient(Request $request){
      if($request->isMethod("post")){
        $useremail=$request->input("useremail");
        $email=$request->input("email");
        $phone=$request->input("phone");
        $businessname=$request->input("businessname");
        $address=$request->input("address");
        $request->merge([
            "businessname"=>htmlspecialchars(trim($request->input("businessname"))),
            "email"=>filter_var($request->input("email"),FILTER_SANITIZE_EMAIL),
            "phone"=>htmlspecialchars(trim($request->input("phone"))),
            "address"=>htmlspecialchars(trim($request->input("address")))
        ]);
        $formValidator=Validator::make($request->all(),[
            "businessname"=>["required","max:255"],
            "email"=>["required"],
            "phone"=>["required"],
            "address"=>["required"]
        ]);
        try{
            if($formValidator->fails()){
                $businessnameError=$formValidator->errors()->get("businessname");
                $emailError=$formValidator->errors()->get("email");
                $phoneError=$formValidator->errors()->get("phone");
                $addressError=$formValidator->errors()->get("address");
                return response()->json([
                    "businessnameError"=>$businessnameError,
                    "emailError"=>$emailError,
                    "phoneError"=>$phoneError,
                    "addressError"=>$addressError
                ]);
            }
            if($formValidator->passes()){
                try{
                    $checkExistingBusinessData=DB::select("SELECT * FROM registered_users_clients WHERE business_email=:business_email AND client_email=:client_email AND client_phone=:client_phone",[":business_email"=>$useremail,":client_email"=>$email,":client_phone"=>$phone]);
                    if(count($checkExistingBusinessData)>0)return response()->json("Client exists.");
                    else{
                        $insertBusinessData=DB::insert("INSERT INTO registered_users_clients(business_email,client_name,client_email,client_phone,client_address)
                        VALUES(:business_email,:client_name,:client_email,:client_phone,:client_address)",[
                              ":business_email"=>$useremail,
                              ":client_name"=>$businessname,
                              ":client_email"=>$email,
                              ":client_phone"=>$phone,
                              ":client_address"=>$address
                        ]);
                    }
                    if($insertBusinessData)return response()->json("Saved successfully.");
                }
                catch(QueryException $e){
                    return response()->json($e->getMessage());
                }
            }
        }
        catch(QueryException $e){
            return response()->json("The following db error has occured: ",$e->getMessage());
        }
      }
    }
}
