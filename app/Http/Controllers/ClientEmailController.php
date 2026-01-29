<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
class ClientEmailController extends Controller{
    public function getClientEmail(Request $request){
        if($request->isMethod("post")){
            try{
                $recepientName=$request->input("recepientName");
                $getClientEmail=DB::select("SELECT client_email FROM registered_users_clients WHERE client_name=:clientName",[":clientName"=>$recepientName]);
                return response()->json($getClientEmail[0]);

            }catch(QueryException $e){
                return response()->json(["The followig error occured:",$e->getMessage()]);
            }
        }

    }
}
