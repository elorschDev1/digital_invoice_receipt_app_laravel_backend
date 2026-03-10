<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ExpenseAdditionController extends Controller{
    public function addExpense(Request $request){
        if($request->isMethod("post")){
            $user_email=$request->input("user_email");
            $company_name=$request->input("company_name");
            $amount=$request->input("amount");
            $receipt="";
            $hashedReceiptName="";
            $expense_description=$request->input("expense_description");
            if($request->hasFile("receipt")){
                $path=$request->file("receipt")->store("uploads","public");
                $hashedReceiptName=$request->file("receipt")->hashName();
                $receipt=$hashedReceiptName;
                
            }else $receipt="N/A";
            $category=$request->input("category"); 
            $expense_date=$request->input("expense_date"); 
            $request->merge([
             "expense_description"=>htmlspecialchars(trim($request->input("expense_description"))),
             "amount"=>(float)filter_var($amount,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION),
            ]);
            try{
                $formValidator=Validator::make($request->all(),[
                "expense_description"=>["required","string","max:500"],
                "amount"=>["required","numeric","min:0.01","max:10000000"],
                "expense_date"=>["required","date","before_or_equal:today","after:".now()->subYears(5)->toDateString()],
                'category' => 'required|string|in:Office Supplies,Software/Subscriptions,Marketing/Advertising,Travel,Meals & Entertainment,Professional Services,Utilities,Rent,Insurance,Other',
                "receipt"=>"mimes:jpg,jpeg,png,pdf"
            ]);
            if($formValidator->fails()){
                $expenseDescriptionErrors=$formValidator->errors()->get("expense_description");
                $amountErrors=$formValidator->errors()->get("amount");
                $expenseDateErrors=$formValidator->errors()->get("expense_date");
                $categoryErrors=$formValidator->errors()->get("category");
                $receiptErrors=$formValidator->errors()->get("receipt");
                return response()->json([
                    "expenseDescriptionErrors"=>$expenseDescriptionErrors,
                    "amountErrors"=>$amountErrors,
                    "expenseDateErrors"=>$expenseDateErrors,
                    "categoryErrors"=>$categoryErrors,
                    "receiptErrors"=>$receiptErrors
                ]);
            }
            if($formValidator->passes()&&$request->has("id")===true){
                    if($request->has("id")){
                        $updateUserExpense=DB::table("expenses")
                                            ->where("id",$request->input("id"))
                                            ->where("user_email",$user_email)
                                            ->update([
                                                  "expense_date"=>$expense_date,
                                                  "expense_description"=>$expense_description,
                                                  "amount"=>(float)$amount,
                                                  "category"=>$category,
                                                  "receipt_url"=>$receipt,

                                            ]);
                         try{
                            if($updateUserExpense){
                                return response()->json([
                                    "message"=>"Great, this expense has been updated."
                                ]);
                            }
                         }catch(QueryException $e){
                            return response()->json([
                                "message"=>"Database error ".$e->getMessage()
                            ]);
                         }catch(\Exception $e){
                            return response()->json([
                                "Error Message"=>$e->getMessage()
                            ]);
                         }
                                          

                        

                    }
            }
            if($formValidator->passes()&&$request->has("id")===false){
                $saveUserExpense=DB::table("expenses")->insert([
                                "user_email"=>$user_email,
                                "company_name"=>$company_name,
                                "expense_date"=>$expense_date,
                                "expense_description"=>$expense_description,
                                "amount"=>(float)$amount,
                                "category"=>$category,
                                "receipt_url"=>$receipt,
                                "created_at"=>now()
                                ]);
                                if($saveUserExpense){
                                    return response()->json([
                                        "success"=>true,
                                        "message"=>"Your expense has been saved."
                                    ]);
                                }
                
            }
            }catch(QueryException $e){
                return response()->json($e->getMessage());
            }
            catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }   
        }  
    } 
}
