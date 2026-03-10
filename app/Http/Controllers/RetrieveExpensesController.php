<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class RetrieveExpensesController extends Controller{
    public function retrieveUserExpenses(Request $request){
        if($request->isMethod("post")){
            $user_email=$request->input("user_email");
            try{
                $getUserExpenses=DB::table("expenses")
                                ->select("expense_date","expense_description","amount","category","id")
                                ->where("user_email",$user_email)
                                ->get();
                $expenses=$getUserExpenses;
                return response()->json([
                    "message"=>"Successfully retrieved the expenses",
                    "expenses"=>$expenses
                ]);
            }catch(QueryException $e){
                return response()->json([
                    "error"=>"Database Error:",
                    "message"=>$e->getMessage()
                ]);
            }catch(\Exception $e){
                return response()->json([
                    "error"=>"General Error Message:",
                    "message"=>$e->getMessage()
                ]);
            }
        }
    }

}
