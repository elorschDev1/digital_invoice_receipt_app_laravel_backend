<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class DeletedDraftsController extends Controller{
    public function deleteDraftItem(Request $request){
        if($request->isMethod("post")){
         DB::beginTransaction();
         try{
            $draftInvoiceNumber=$request->input("draftInvoiceNumber");
            $deleteFromCreatedDraftsItems=DB::delete("DELETE FROM created_drafts_items WHERE draftInvoiceNumber=:draftInvoiceNumber",[":draftInvoiceNumber"=>$draftInvoiceNumber]);
            $deleteFromCreatedDrafts=DB::delete("DELETE FROM created_drafts WHERE draftInvoiceNumber=:draftInvoiceNumber",[":draftInvoiceNumber"=>$draftInvoiceNumber]);
            DB::commit();
            return response()->json("The draft has been deleted successfully.");
         }catch(QueryException $e){
            return response()->json(["Error"=>"Database Error","Message"=>$e->getMessage()]);
         }
        }
    }
    
}
