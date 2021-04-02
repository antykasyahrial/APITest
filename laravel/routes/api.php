<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\ProductApiController;
use App\Http\Controllers\TransactionApiController;
use App\Http\Controllers\LoginController;

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


Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/register', [LoginController::class,'register']);
    Route::post('/awas_ini_sangat_rahasia',[LoginController::class, 'register']);

    Route::get('unauthorized',function(){
        return response()->json([
            "message" => "unauthorized"], 401);
    })->name('unauthorized');

    Route::group(['middleware' =>'auth:api'],function(){
        Route::get('/authenticate', [LoginCOntroller::class, 'getAuthenticatedUser'])->name('getAuthenticated');
        
        //all
        Route::get('/product', [ProductApiController::class, 'index']);
        Route::get('/product/{id}', [ProductApiController::class, 'show']);
        Route::post('/transaction', [TransactionApiController::class, 'store']);
        Route::get('/history', [TransactionApiController::class, 'idShow']);
        
        //user
        Route::put('/profile', [UserApiController::class, 'updateProfile']);
        Route::get('/profile', [UserApiController::class, 'profile']);
        
    
        //admin
        Route::group(['middleware' => 'role','prefix' => 'admin'],function(){
            Route::get('/alluser', [UserApiController::class, 'index']);
            Route::get('/user/{id}', [UserApiController::class, 'show']);
            Route::put('/user/{id}', [UserApiController::class, 'update']);
            Route::get('/transaction', [TransactionApiController::class, 'index']);
            Route::post('/product', [ProductApiController::class, 'store']);
            Route::put('/product/{id}', [ProductApiController::class, 'update']);
            Route::get('/transaction', [TransactionApiController::class, 'index']);
            Route::get('/transaction/{id}', [TransactionApiController::class, 'show']);
        });
        
        
        
//https://awalmula.hitrashindonesia.id
    });

});

