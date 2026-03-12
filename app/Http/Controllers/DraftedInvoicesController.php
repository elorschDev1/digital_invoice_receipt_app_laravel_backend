<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
class DraftedInvoicesController extends Controller{
    public function handleDraftedInvoices(Request $request){
        if($request->isMethod("post")){
            try{
                $companyName=$request->input("companyName");
                  $checkDraftedItems=DB::table("created_drafts")
                                    ->join("created_drafts_items","created_drafts.draftInvoiceNumber","=","created_drafts_items.draftInvoiceNumber")
                                    ->select(
                                        "created_drafts.draftInvoiceNumber",
                                        "created_drafts.draftCreator",
                                        "created_drafts.draftReceiver",
                                        "created_drafts.grandTotal",
                                        "created_drafts.paymentTerms",
                                        "created_drafts.businessNote",
                                        "created_drafts_items.draftInvoiceNumber",
                                        "created_drafts_items.productName",
                                        "created_drafts_items.productDescription",
                                        "created_drafts_items.unitPrice",
                                        "created_drafts_items.quantity",
                                        "created_drafts_items.taxRate",
                                        "created_drafts_items.discount"
                                    )
                                    ->where("draftCreator",$companyName)
                                    ->distinct()
                                    ->get();
                return response()->json($checkDraftedItems);
            }catch(QueryException $e){
                return response()->json(["An error has occured :",$e->getMessage()]);
            }
        }
    }
}
