<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
class BilledUserController extends Controller{
    public function billUser(Request $request){
    if($request->isMethod("post")){
        $email=$request->input("email");
        $client=$request->input("client");
        $request->merge([
            "email"=>filter_var($email,FILTER_SANITIZE_EMAIL),
            "client"=>htmlspecialchars($client)
        ]);
        try{
            $getBusinessContactQuery=DB::select("SELECT * FROM registered_users_clients WHERE business_email=:email AND client_name=:client",[":email"=>$email,":client"=>$client]);
            return response()->json($getBusinessContactQuery);
        }
        catch(QueryException $e){
            return response()->json("The following error has occured: ".$e->getMessage());
        }
    }
    }

}
