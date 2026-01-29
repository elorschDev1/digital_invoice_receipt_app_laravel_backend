<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class DashboardController extends Controller{
     public function getDashboardData(Request $request){
        if($request->isMethod("post")){
            $loggedInUser=$request->input("loggedInUser");
            $companyName=$request->input("companyName");
            try{
                $unpaid="Unpaid";
                $overdue="Overdue";
                $getTotalClients=DB::select("SELECT id FROM registered_users_clients WHERE business_email=:loggedInUser",[":loggedInUser"=>$loggedInUser]);
                $getUnpaidInvoices=DB::select("SELECT invoiceNumber FROM created_invoices WHERE (paymentStatus=:unpaid OR paymentStatus=:overdue) AND senderEmail=:senderEmail",[":unpaid"=>$unpaid,":overdue"=>$overdue,":senderEmail"=>$loggedInUser]);
                $topClientByInvoiceValue=DB::select("SELECT grandTotal,receiverName FROM created_invoices WHERE senderEmail=:loggedInUser",[":loggedInUser"=>$loggedInUser]);
                $draftsCount=DB::select("SELECT draftCreator FROM created_drafts WHERE draftCreator=:draftCreator",[":draftCreator"=>$companyName]);
                $draftCreationDates=DB::select("SELECT creationDate FROM created_drafts WHERE draftCreator=:draftCreator",[":draftCreator"=>$companyName]);
                return response()->json([
                    "totalClients"=>count($getTotalClients),
                    "unpaidInvoices"=>count($getUnpaidInvoices),
                    "topClientByInvoiceValue"=>$topClientByInvoiceValue,
                    "draftsCount"=>count($draftsCount),
                    "draftsCreationDates"=>$draftCreationDates
                ]);
            
            
            }catch(QueryException $e){
                return response()->json($e->getMessage());
            }


        }


     }
}
