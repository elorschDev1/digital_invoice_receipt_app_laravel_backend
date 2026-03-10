<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
class RevenueExpensesComparisonController extends Controller{
    public function getRevenueVsExpenses(Request $request){
        $userEmail=$request->input("userEmail");
        $months=6;
        $data=[];
        try{
            for($i=$months-1;$i>=0;$i--){
            $date=now()->subMonths($i);
            $month=$date->format("M");
            $year=$date->year;
            $monthNum=$date->month;

            $revenue=DB::table("created_invoices")
                    ->where("senderEmail",$userEmail)
                    ->where("paymentStatus","Paid")
                    ->whereRaw("YEAR(STR_TO_DATE(creationDate, '%a %b %d %Y')) = ?", [$year])
                    ->whereRaw("MONTH(STR_TO_DATE(creationDate, '%a %b %d %Y')) = ?", [$monthNum])
                    ->sum("grandTotal");

            $expenses=DB::table("expenses")
                     ->where("user_email",$userEmail)
                     ->whereYear("expense_date",$year)
                     ->whereMonth("expense_date",$monthNum)
                     ->sum("amount");

            $data[]=[
                "month"=>$month,
                "revenue"=>(float)$revenue,
                "expenses"=>(float)$expenses,
                "profit"=>(float)($revenue-$expenses)
            ];   
        }
        return response()->json([
            "data"=>$data,
            "success"=>true,
            "status"=>200
        ]);

    }catch(QueryException $e){
        return response()->json([
            "message"=>"Database related Error:",
            "error"=>$e->getMessage()
        ]);
    }catch(\Exception $e){
        return response()->json([
            "message"=>"Server Error:",
            "error"=>$e->getMessage()
        ]);
    }

        }
}
