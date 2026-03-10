<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class DeleteExpenseController extends Controller{
    public function deleteUserExpenses(Request $request){
        if($request->isMethod("post")){
            $user_email=$request->input("user_email");
              try{
              if($request->has("id")){
                $deleteExpense=DB::table("expenses")
                              ->where("user_email",$user_email)
                              ->where("id",$request->input("id"))
                              ->delete();

                if($deleteExpense){
                    return response()->json([
                        "message"=>"Great,this expense has been deleted."
                    ]);
                }
        
              }
          }catch(QueryException $e){
                return response()->json([
                    "message"=>"Database Error: ".$e->getMessage()
                ]);
            }catch(\Exception $e){
                return response()->json([
                    "message"=>"Error That Occured:".$e->getMessage()
                ]);
            }
            
        }
    }

}
