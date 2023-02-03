<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| 
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| 
*/

Route::get('assujettissements',[App\Http\Controllers\Assujettissement\MainController::class,'Assujettissement_List']);
Route::get('impots',[App\Http\Controllers\Assujettissement\MainController::class,'Impot_List']);


Route::get('/', function () {
    //echo base64_encode("test content");
    return view('landing');
});

Route::get('/doc/{id}', [App\Http\Controllers\DOC\ApiController::class,'View']); //home/{id}
Route::get('/doc', [App\Http\Controllers\DOC\ApiController::class,'Home']); //home/{id}


// RAPPORTS

Route::get('contribuables_assujettis_tva',[App\Http\Controllers\RAPPORT\MainController::class,'contribuables_assujettis_tva']);

