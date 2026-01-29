<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomePageMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if(!$request->isMethod("post")){
            return response()->json([
                "error"=>"Invalid request method",
                "message"=>"You have accessed this page in an unauthorised manner, redirecting you to the login page.",
                "source"=>"Home Page Err"
            ]);
        }

        if(!$request->has("email")){
            return response()->json([
                "error"=>"Missing required field",
                "message"=>"The appropriate field required to access this field is absent.",
                "source"=>"Home Page Err"
            ]);
        }
        $email=$request->input("email");
        if(empty($email))return response()->json(["error"=>"Certain value is absent","message"=>"Missing certain information to allow login.","source"=>"Home Page Err"]);
        return $next($request);
    }
}
