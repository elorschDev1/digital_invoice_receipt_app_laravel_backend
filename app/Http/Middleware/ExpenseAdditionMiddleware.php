<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpenseAdditionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response{
        if(!$request->isMethod("post")){
            return response()->json([
                "error"=>"Unauthorized access method",
                "message"=>"Method not allowed"
            ]);
        }
        if($request->has("user_email")===false){
            return response()->json([
                   "error"=>"Missing required field",
                   "message"=>"The appropriate field required to access this field is absent.",
                   "source"=>"Expense Middlware"
            ]);
        }
        $user_email=$request->input("user_email");
         if(empty($user_email))return response()->json(["error"=>"Certain value is absent","message"=>"Missing certain information to allow login.","source"=>"Expense Middleware"]);
        return $next($request);
    }
}
