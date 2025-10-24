<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfessionalDeveloperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;//This line imports the Route facade

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/hello",function(){
    return ("Hello world.");
});

Route::get("/laravelBasic",function(){
    return ("The basics of Laravel.");
});

Route::get("/testpage",function(){
    return view("testpage");
});

Route::get("/about",function(){
    return view("pages.about");
});

Route::get("/greeting",function(){
    return "
    <div>
    <h1>Hi Guys</h1>
    <p>Welcome to this Laravel Series</p>  
    </div> 
    ";
});
Route::get("/clients/{number}",function($number){
    return "This is user number $number.";
});
Route::get("ID/{id}",function($id){
    echo "ID: $id";
});
Route::get("user/{name?}",function($name="Tutorials Point"){
    return $name;
});
//basic example of how to pass data to a certain url as an associative array
Route::get("/samplePage",function(){
    return view("pages.samplePage",["name"=>"Edgar Lorsch","age"=>23]);
});



//Passing data using the compact() method
Route::get("/foods",function(){
    $food="Beans";
    $category="proteins";
    return view("pages.foods",compact("food","category"));
});

//Passing an array of users

Route::get("/users",function(){
    $users=["Alice","Brian","Cynthia","David"];
    return view("pages.users",compact("users"));
});

//Let me try passing in an associative array
Route::get("/capitals",function(){
    $capitals=[
        "Japan"=>"Tokyo",
        "France"=>"Paris",
        "Germany"=>"Berlin",
        "United Kingdom"=>"London",
        "United States"=>"Washington DC"
    ];
    return view("pages.capitals",compact("capitals"));
});

Route::view("/techstack","pages.techstack");
Route::get("/sports-page-news",function(){
    $sportsPageURL=route("sports");
   // dd($sportsPageURL);
   echo $sportsPageURL;
    return "<body><p>My best sport is football</p></body>";
})->name("sports");

Route::get("/show",[UserProfileController::class,"show"])->name("show");
Route::get("/developer",[ProfessionalDeveloperController::class,"developerInformation"])->name("developer");
Route::middleware(["auth"])->group(function(){
Route::get("/dashboard",[DashboardController::class,"dashboardPage"])->name("dashboard");
Route::get("/settings",[SettingsController::class,"settingsPage"])->name("settings");
Route::get("/profile",[ProfileController::class,"profilePage"])->name("profile");
});

Route::get("/login",[LoginController::class,"login"])->name("login");

Route::controller(OrderController::class)->group(function(){
    Route::get("/orders/{id}","show");
    Route::get("/orders/store","store");
});

Route::controller(VehicleController::class)->group(function(){
    Route::get("/audi","audiVehicle")->name("audi");
    Route::get("/rangerover","rangeRoverVehicle")->name("rangerover");
});


Route::get("/sum/{firstNumber}/{secondNumber}",function(float $firstNumber,float $secondNumber){
    return $firstNumber+$secondNumber;
})->whereNumber(["firstNumber","secondNumber"]);