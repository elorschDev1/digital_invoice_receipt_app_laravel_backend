<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class CreatedInvoiceItemsController extends Controller{
 public function createdInvoiceItems(Request $request){
    if($request->isMethod("post")){
        try{
            $getInvoiceItemsQuery=DB::select("SELECT * FROM created_invoice_items WHERE invoiceNumber=:invoiceNumber",[":invoiceNumber"=>$request->input("invoiceNumber")]);
            return response()->json($getInvoiceItemsQuery);
        }catch(QueryException $e){
         return response()->json("Sorry, the following error has occured:",$e->getMessage());
        }
    }
 }
}
