<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class RetrievedInvoicesMiddleware{
        public function handle(Request $request, Closure $next): Response{
            if(!$request->isMethod("post")){
                return response()->json([
                    "error"=>'Unauthorised method',
                    "message"=>"Page access method disallowed",
                ]);
            }
            if(!$request->has("senderEmail")){
                return response()->json([
                    "error"=>"Required Input Field Absent",
                    "message"=>"The email key is missing from the request"
                ]);
            }
            $senderEmail=$request->input("senderEmail");
            if(empty($senderEmail)){
                return response()->json([
                    "error"=>"Empty required field",
                    "message"=>"The expected value for this field is empty."
                ]);
            }
            $senderEmailExists=DB::table("created_invoices")
                              ->where("senderEmail",$senderEmail)
                              ->exists();
            if(!$senderEmailExists){
                return response()->json([
                    "error"=>"Non-existent user",
                    "message"=>"The user making this request isn't a registered user"
                ]);
            }
        return $next($request);
    }
}
