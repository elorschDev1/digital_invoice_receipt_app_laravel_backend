<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class UpdatePaymentStatusController extends Controller{
    public function updatePaymentStatus(Request $request){
        if($request->isMethod("post")){
            $invoiceNumber=$request->input("invoiceNumber");
            try{
                $updatePaymentStatusQuery=DB::statement("UPDATE created_invoices SET paymentStatus='Paid' WHERE invoiceNumber=$invoiceNumber");
                if($updatePaymentStatusQuery===true)return response()->json("The payment status has been updated.");
           
            }catch(QueryException $e){
                return response()->json($e->getMessage());

            }

        }

    }
    
}
