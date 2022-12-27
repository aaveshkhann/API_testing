<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TestController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(["prefix"=>"V1"], function(){
  Route::group(["prefix"=>"test"],function(){
       Route::get('get',[TestController::class,'index']);
       Route::post('gets',[TestController::class,'store']);
       Route::put('update/{id}',[TestController::class,'update']);
       Route::delete('delete/{id}',[TestController::class,'destroy']);
    });
});
