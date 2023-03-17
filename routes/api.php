<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::get('assujettissements',[App\Http\Controllers\Assujettissement\MainController::class,'List']);
Route::get('assujettissement/{id}',[App\Http\Controllers\Assujettissement\MainController::class,'View']);

Route::post('assujettissement',[App\Http\Controllers\Assujettissement\MainController::class,'Create']);
Route::put('assujettissement/{id}',[App\Http\Controllers\Assujettissement\MainController::class,'Update']);


Route::get('/', function () {
    
    //return view('landing');
});


Route::post('cloture/{id}',[App\Http\Controllers\Assujettissement\MainController::class,'Terminate']);

Route::get('ping',function(){
        echo "MS ASSUJETTISSEMENTS ";
        echo date("Y-m-d h:i:s");
    });

//Route::get('assujettis',[App\Http\Controllers\RAPPORT\MainController::class,'Assujettis_List']);

//Route::get('impot',[App\Http\Controllers\Assujettissement\MainController::class,'Impot_List']);

//Route::get('ipr_iers',[App\Http\Controllers\Assujettissement\Ipr_ierController::class,'List']);

// STANDARD SERVICE API CALLS
// API CLIENTS
/*
Route::post('client_register',[AuthController::class,'register']);
Route::post('client_login',[AuthController::class,'login']);

Route::group(['middleware' => ['auth:api']], function () {

    Route::post('products',function(){
        echo "Obel: I did not received your data! 2";
        print_r($_POST);
    });
  
    Route::post('client_logout', [AuthController::class, 'logout']);
});
*/


