<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RecentTransactionsController extends Controller{
    public function getRecentTransactions(Request $request){
     if($request->isMethod("post")){
        $email=$request->input("email");
        try{
            $transactions=DB::table("created_invoices")
                          ->join("created_invoice_items","created_invoices.invoiceNumber","=","created_invoice_items.invoiceNumber")
                          ->select(
                            "created_invoices.creationDate",
                            "created_invoices.receiverName",
                            "created_invoice_items.productDescription",
                            "created_invoices.paymentStatus",
                            "created_invoices.grandTotal"
                            )
                            ->where("senderEmail",$email)
                            ->get();
            
            return response()->json($transactions);
        }catch(QueryException $e){
            return response()->json($e->getMessage());
        }
     }
    }
}
