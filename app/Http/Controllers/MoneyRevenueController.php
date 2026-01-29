<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
class MoneyRevenueController extends Controller{
    public function getTotalRevenue(Request $request){
        if($request->isMethod("post")){
            $email=$request->input("email");
            try{
                $payments=DB::select("SELECT grandTotal,paymentStatus FROM created_invoices WHERE senderEmail=:email",[":email"=>$email]);
                return response()->json($payments);
            }catch(QueryException $e){
                return response()->json($e->getMessage());
            }


        }

    }

}
