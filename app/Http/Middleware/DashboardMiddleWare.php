<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class DashboardMiddleWare{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response{
        //I'll first verify if the dashboard is being accessed via a post method
        if(!$request->isMethod("post")){
            return response()->json([
                "error"=>"Invalid request method",
                "message"=>"Only post requests are allowed"
            ],405);
        }

        //I'll now check if the required parameters are present
        if(!$request->has("loggedInUser")||!$request->has("companyName")){
            return response()->json([
                "error"=>"Missing required parameters",
                "message"=>"loggedInUser and companyName are missing."
            ],400);
        }

        //Validating that none of the parameters are empty
        $loggedInUser=$request->input("loggedInUser");
        $companyName=$request->input("companyName");

        if(empty($loggedInUser)||empty($companyName)){
            return response()->json([
                "error"=>"Invalid parameters",
                "message"=>"loggedInUser and companyName cannot be empty."
            ],400);
        }

        //Authenticate the user, verify that customer records exist in the database
        try{
            $userExists=DB::table("registered_business_users")
                         ->where("business_email",$loggedInUser)
                         ->where("companyName",$companyName)
                         ->exists();
            if(!$userExists){
                return response()->json([
                    "error"=>"Unauthorised",
                    "message"=>"User not authorised or invalid credentials"
                ],401);
            }

            //Log Dashboard access for monitoring
            Log::info("Dashboard accessed",[
                "user"=>$loggedInUser,
                "company"=>$companyName,
                "ip"=>$request->ip(),
                "timestamp"=>now()
            ]);

            //Add user data to request for use in controller
            $request->merge([
                "authenticated"=>true,
                "access_time"=>now()
            ]);
        }catch(\Exception $e){
            Log::error("Dashboard middleware error",[
                "error"=>$e->getMessage(),
                "user"=>$loggedInUser
            ]);
        } 
        return $next($request);
    }
}
