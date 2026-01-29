<?php

use App\Http\Controllers\ClientEmailController;
use App\Http\Controllers\ClientLoginController;
use App\Http\Controllers\ClientSignUpController;
use App\Http\Controllers\CompanyInvoiceController;
use App\Http\Controllers\CreatedInvoiceItemsController;
use App\Http\Controllers\CustomerManagementController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\LoginPageController;
use App\Http\Controllers\RetrievedInvoicesController;
use App\Http\Controllers\SavedDraftsController;
use App\Http\Controllers\UserSignUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post("/clientManagement",[CustomerManagementController::class,"retrieveUserClients"]);

Route::post("/savedDrafts",[SavedDraftsController::class,"saveDrafts"]);

Route::post("/retrievedInvoices",[RetrievedInvoicesController::class,"retrievedInvoices"]);

Route::post("/createdInvoiceItems",[CreatedInvoiceItemsController::class,"createdInvoiceItems"]);

Route::post("/companyInvoice",[CompanyInvoiceController::class,"companyInvoice"]);

Route::post("/getClientEmail",[ClientEmailController::class,"getClientEmail"]);