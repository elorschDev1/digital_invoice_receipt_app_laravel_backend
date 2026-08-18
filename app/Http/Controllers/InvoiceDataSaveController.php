<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Validator;
class InvoiceDataSaveController extends Controller{
    public function saveInvoiceData(Request $request){
        if($request->isMethod("post")){
            $senderName=$request->input("senderName");
            $senderEmail=$request->input("senderEmail");
            $receiverName=$request->input("receiverName");
            $receiverEmail=$request->input("receiverEmail");
            $invoiceNumber=$request->input("invoiceNumber");
            $creationDate=$request->input("creationDate");
            $dueDate=$request->input("dueDate");
            $paymentTerms=$request->input("paymentTerms");
            $subTotal=$request->input("subtotal");
            $grandTotal=$request->input("grandTotal");
            $billedProducts=json_decode($request->input("billedProducts"),true);
            $productName=$productDescription=$unitPrice=$quantity=$tax="";
            $paymentStatus="Unpaid";
            $dataSaved=false;
            $invoiceExists=false;
            $infoSaved=false;
            $itemsSaved=false;
            try{
                $checkExistingInvoiceNumber=DB::select("SELECT * FROM created_invoices WHERE invoiceNumber=:invoiceNumber",[":invoiceNumber"=>$invoiceNumber]);
                if(count($checkExistingInvoiceNumber)>0)$invoiceExists=true;
                 else{
                      $insertInvoiceData=DB::insert("INSERT INTO created_invoices(invoiceNumber,senderName,senderEmail,receiverName,creationDate,dueDate,paymentTerms,subtotal,grandTotal,paymentStatus) 
                      VALUES(?,?,?,?,?,?,?,?,?,?)",[$invoiceNumber,$senderName,$senderEmail,$receiverName,$creationDate,$dueDate,$paymentTerms,$subTotal,$grandTotal,$paymentStatus]);
                      if($insertInvoiceData===true)$infoSaved=true;
                      foreach($billedProducts as $billedProduct){
                        $productName=htmlspecialchars($billedProduct["productName"]);
                        $productDescription=htmlspecialchars($billedProduct["productName"]);
                        $unitPrice=filter_var($billedProduct["unitPrice"],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                        $quantity=filter_var($billedProduct["quantity"],FILTER_SANITIZE_NUMBER_INT);
                        $tax=filter_var($billedProduct["tax"],FILTER_SANITIZE_NUMBER_INT);
                        $productID=htmlspecialchars($billedProduct["productID"]);
                        $discount=filter_var($billedProduct["discount"],FILTER_SANITIZE_NUMBER_INT);
                        $insertInvoiceItems=DB::insert("INSERT INTO created_invoice_items(productID,invoiceNumber,productName,productDescription,quantity,tax,discount,unitPrice)
                        VALUES(:productID,:invoiceNumber,:productName,:productDescription,:quantity,:tax,:discount,:unitPrice)",[
                         ":productID"=>$productID,
                         ":invoiceNumber"=>$invoiceNumber,
                         ":productName"=>$productName,
                         ":productDescription"=>$productDescription,
                         ":quantity"=>$quantity,
                         ":tax"=>$tax,
                         ":discount"=>$discount,
                         ":unitPrice"=>$unitPrice
                        ]);
                        if($insertInvoiceItems)$itemsSaved=true;
                    }
                    if($infoSaved===true&&$itemsSaved===true)$dataSaved=true;
                 }
                   if($dataSaved===true)return response()->json("Invoice created successfully.");         
            }
            catch(QueryException $e){
                return response()->json($e->getMessage());
            }
        }
    }  
}
