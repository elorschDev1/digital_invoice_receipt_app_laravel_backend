<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExpensesController extends Controller{
    public function addExpense(Request $request){
        if($request->isMethod("post")){
            return response()->json("Request received.");
        }
        
    }
    
}
