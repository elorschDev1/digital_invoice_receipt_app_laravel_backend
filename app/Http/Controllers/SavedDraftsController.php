<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SavedDraftsController extends Controller{
    public function saveDrafts(Request $request){
        if($request->isMethod("post")){
              $infoSaved=false;
                $itemsSaved=false;
                $draftExists=false;
                $draftCreationDate=$request->input("draftCreationDate");
            try{
                $request->merge([
                    "draftCreator"=>htmlspecialchars($request->input("draftCreator")),
                    "draftReceiver"=>htmlspecialchars($request->input("draftReceiver")),
                    "draftInvoiceNumber"=>htmlspecialchars($request->input("draftInvoiceNumber")),
                    "grandTotal"=>$request->input("grandTotal"),
                    "paymentTerms"=>htmlspecialchars($request->input("paymentTerms")),
                    "businessNote"=>htmlspecialchars($request->input("businessNote"))
                ]);
                $checkForExistingDraftNumber=DB::select("SELECT * FROM created_drafts WHERE draftInvoiceNumber=:draftInvoiceNumber",["draftInvoiceNumber"=>$request->input("draftInvoiceNumber")]);
                 if(count($checkForExistingDraftNumber)>0)return response()->json("This draft had already been created.");
                 else{
                    $insertSampleDraft=DB::insert("INSERT INTO created_drafts(draftInvoiceNumber,draftCreator,draftReceiver,grandTotal,paymentTerms,businessNote,creationDate)
                    VALUES(:draftInvoiceNumber,:draftCreator,:draftReceiver,:grandTotal,:paymentTerms,:businessNote,:creationDate)",[
                        "draftInvoiceNumber"=>$request->input("draftInvoiceNumber"),
                        "draftCreator"=>$request->input("draftCreator"),
                        "draftReceiver"=>$request->input("draftReceiver"),
                        "grandTotal"=>$request->input("grandTotal"),
                        "paymentTerms"=>$request->input("paymentTerms"),
                        "businessNote"=>$request->input("businessNote"),
                        ":creationDate"=>$draftCreationDate
                    ]);
                    if($insertSampleDraft===true)$infoSaved=true;
                    if($request->has("billedProducts")){
                        $billedProducts=json_decode($request->input("billedProducts"),true);
                        foreach($billedProducts as $billedProduct){
                            $productName=$billedProduct["productName"];
                            $productDescription=$billedProduct["productDescription"];
                            $unitPrice=$billedProduct["unitPrice"];
                            $quantity=$billedProduct["quantity"];
                            $tax=$billedProduct["tax"];
                            $productID=$billedProduct["productID"];
                            $discount=$billedProduct["discount"];
                            $request->merge([
                                $productName=>htmlspecialchars($billedProduct["productName"]),
                                $productDescription=>htmlspecialchars($billedProduct["productDescription"]),
                                $unitPrice=>filter_var($billedProduct["unitPrice"],FILTER_SANITIZE_NUMBER_FLOAT),
                                $quantity=>filter_var($billedProduct["quantity"],FILTER_SANITIZE_NUMBER_INT),
                                $tax=>filter_var($billedProduct["tax"],FILTER_SANITIZE_NUMBER_INT),
                                $productID=>htmlspecialchars($billedProduct["productID"]),
                                $discount=>filter_var($billedProduct["discount"],FILTER_SANITIZE_NUMBER_INT)
                            ]);
                            $insertDraftItems=DB::insert("INSERT INTO created_drafts_items(productID,draftInvoiceNumber,productName,productDescription,unitPrice,quantity,taxRate,discount)
                            VALUES(:productID,:draftInvoiceNumber,:productName,:productDescription,:unitPrice,:quantity,:taxRate,:discount)
                               ",[
                                ":productID"=>$productID,
                                ":draftInvoiceNumber"=>$request->input("draftInvoiceNumber"),
                                ":productName"=>$productName,
                                ":productDescription"=>$productDescription,
                                ":unitPrice"=>$unitPrice,
                                ":quantity"=>$quantity,
                                ":taxRate"=>$tax,
                                ":discount"=>$discount
                               ]);
                               if($insertDraftItems===true)$itemsSaved=true;
                        }
                    }
                    if($request->has("emptyProducts")){
                         $productName= $productDescription="";
                         $unitPrice=$quantity=$tax=$discount=0;
                         $productID=$request->input("emptyProducts");
                              $insertDraftItems=DB::insert("INSERT INTO created_drafts_items(productID,draftInvoiceNumber,productName,productDescription,unitPrice,quantity,taxRate,discount)
                            VALUES(:productID,:draftInvoiceNumber,:productName,:productDescription,:unitPrice,:quantity,:taxRate,:discount)
                               ",[
                                ":productID"=>$productID,
                                ":draftInvoiceNumber"=>$request->input("draftInvoiceNumber"),
                                ":productName"=>$productName,
                                ":productDescription"=>$productDescription,
                                ":unitPrice"=>$unitPrice,
                                ":quantity"=>$quantity,
                                ":taxRate"=>$tax,
                                ":discount"=>$discount
                               ]);
                               if($insertDraftItems===true)$itemsSaved=true;
                        }
                        if($infoSaved===true&&$itemsSaved===true)return response()->json("Great, this draft has been saved.");
                 }

            }catch(QueryException $e){
                return response()->json(["Error"=>$e->getMessage()]);
            }
        }
    }
}
