<?php
namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanyInvoiceController extends Controller{
   public function companyInvoice(Request $request){
    if($request->isMethod("post")){
        try{
            $email = $request->input("email");
            
            // Update overdue invoices using Query Builder
            DB::table('created_invoices')
                ->where('senderEmail', $email)
                ->where('paymentStatus', 'Unpaid')
                ->where('dueDate', '<', now())
                ->update(['paymentStatus' => 'Overdue']);
            
            // Get company details
            $companyDetails = DB::table('registered_users')
                ->where('business_email', $email)
                ->get();
            
            return response()->json($companyDetails);
            
        } catch(QueryException $e){
            return response()->json(["error" => $e->getMessage()]);
        }
    }
}
}