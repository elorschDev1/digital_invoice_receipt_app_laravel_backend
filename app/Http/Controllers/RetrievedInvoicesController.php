<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class RetrievedInvoicesController extends Controller{
    public function retrievedInvoices(Request $request){
        if($request->isMethod("post")){
            try{
           //   $retrievedInvoices=DB::select("SELECT * FROM created_invoices WHERE senderEmail=:senderEmail",[":senderEmail"=>$request->input("senderEmail")]);
           $retrievedInvoices=DB::table("created_invoices")
                               ->where("senderEmail",$request->input("senderEmail"))
                               ->get();
           return response()->json($retrievedInvoices);
            }catch(QueryException $e){
                return response()->json("Detected error:",$e->getMessage());
            }
        }
    }
}
