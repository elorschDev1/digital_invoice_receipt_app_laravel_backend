<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CustomerManagementController extends Controller{
    public function retrieveUserClients(Request $request){
        if($request->isMethod("post")){
            $user=$request->input("email");
            try{
              //   $clients=DB::select("SELECT * FROM registered_users_clients WHERE business_email=:business_email",["business_email"=>$user]);
            $clients=DB::table("registered_users_clients")
                    ->where("business_email",'=',$user)
                    ->get();

              return response()->json($clients);

            }catch(QueryException $e){
                return response()->json("Error: ".$e->getMessage());
            }
           
        }
    }
}
