<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ClientSearchMenuController extends Controller{
  public function clientSearch(Request $request){
    if($request->isMethod("post")){
        try{
            $email=$request->input("email");
            $clientFetch=DB::select("SELECT * FROM registered_users_clients WHERE business_email=:email",[":email"=>$email]);
            if(count($clientFetch)<=0)$clients=["message"=>"No registered businesses."];
            else $clients=["message"=>"Found registered businesses.","clients"=>$clientFetch];
            return response()->json($clients);
        }
        catch(QueryException $e){
            return response()->json("The following error has occured: ".$e->getMessage());
        }
    }
  }
}
