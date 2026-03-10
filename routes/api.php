<?php

use App\Http\Controllers\AddClientController;
use App\Http\Controllers\BilledUserController;
use App\Http\Controllers\ClientEmailController;
use App\Http\Controllers\ClientLoginController;
use App\Http\Controllers\ClientSearchMenuController;
use App\Http\Controllers\ClientSignUpController;
use App\Http\Controllers\CompanyInvoiceController;
use App\Http\Controllers\CreatedInvoiceItemsController;
use App\Http\Controllers\CustomerManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeletedDraftsController;
use App\Http\Controllers\DraftedInvoicesController;
use App\Http\Controllers\InvoiceDataSaveController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\LoginPageController;
use App\Http\Controllers\MobileAPIController;
use App\Http\Controllers\MoneyRevenueController;
use App\Http\Controllers\RetrievedInvoicesController;
use App\Http\Controllers\SavedDraftsController;
use App\Http\Controllers\UpdatePaymentStatusController;
use App\Http\Controllers\UserSignUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\DashboardMiddleWare;
use App\Http\Middleware\HomePageMiddleWare;
use App\Http\Middleware\RetrievedInvoicesMiddleware;
use App\Http\Controllers\RecentTransactionsController;
use App\Http\Controllers\ExpenseAdditionController;
use App\Http\Middleware\ExpenseAdditionMiddleware;
use App\Http\Controllers\RetrieveExpensesController;
use App\Http\Controllers\DeleteExpenseController;
use App\Http\Controllers\RevenueExpensesComparisonController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/signup",[SignUpController::class,"saveUserData"]);

Route::post("/login",[LoginPageController::class,"saveLoginData"]);

Route::post("/saveSignUpData",[UserSignUpController::class,"saveSignUpData"]);

Route::post("/clientSignUp",[ClientSignUpController::class,"saveClientData"]);

Route::post("/clientLogin",[ClientLoginController::class,"handleUserLogin"]);



Route::post("/savedDrafts",[SavedDraftsController::class,"saveDrafts"]);

Route::post("/retrievedInvoices",[RetrievedInvoicesController::class,"retrievedInvoices"])->middleware([RetrievedInvoicesMiddleware::class]);
Route::post("/createdInvoiceItems",[CreatedInvoiceItemsController::class,"createdInvoiceItems"]);
Route::post("/getClientEmail",[ClientEmailController::class,"getClientEmail"]);
Route::post("/handleDraftedInvoices",[DraftedInvoicesController::class,"handleDraftedInvoices"]);

Route::post("/deleteDraftItem",[DeletedDraftsController::class,"deleteDraftItem"]);

Route::post("/clientSearchMenu",[ClientSearchMenuController::class,"clientSearch"]);

Route::post("/addClient",[AddClientController::class,"addClient"]);

Route::post("/billedUser",[BilledUserController::class,"billUser"]);

Route::post("/invoiceDataSave",[InvoiceDataSaveController::class,"saveInvoiceData"]);

Route::post("/updatePaymentStatus",[UpdatePaymentStatusController::class,"updatePaymentStatus"]);

Route::post("/mobileRoute",[MobileAPIController::class,"handleMobileData"]);
Route::post("/getDashboardData",[DashboardController::class,"getDashboardData"])->middleware(DashboardMiddleWare::class);


Route::middleware([HomePageMiddleWare::class])->group(function(){
    Route::post("/companyInvoice",[CompanyInvoiceController::class,"companyInvoice"]);
    Route::post("/getTotalRevenue",[MoneyRevenueController::class,"getTotalRevenue"]);
    Route::post("/clientManagement",[CustomerManagementController::class,"retrieveUserClients"]);
});

Route::post("/recentTransactions",[RecentTransactionsController::class,"getRecentTransactions"]);
Route::post("/addExpense",[ExpenseAdditionController::class,"addExpense"])->middleware(ExpenseAdditionMiddleware::class);
Route::post("/getUserExpenses",[RetrieveExpensesController::class,"retrieveUserExpenses"]);
Route::post("/deleteUserExpenses",[DeleteExpenseController::class,"deleteUserExpenses"]);
Route::post("/revenueExpensesComparison",[RevenueExpensesComparisonController::class,"getRevenueVsExpenses"]);