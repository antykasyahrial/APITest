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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });





Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/register', [UserApiController::class, 'store']);

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

        
        //user
        Route::group(['middleware' => 'user','prefix' => 'user'],function(){

        });
        // Route::get('/transaction', [TransactionApiController::class, 'index']);

        //admin
        Route::group(['middleware' => 'admin','prefix' => 'admin'],function(){

        });
        Route::get('/alluser', [UserApiController::class, 'index']);
        Route::get('/user/{id}', [UserApiController::class, 'show']);
        Route::put('/user/{id}', [UserApiController::class, 'update']);
        //Route::post('/product', [ProductApiController::class, 'store']);
        //Route::put('/product/{id}', [ProductApiController::class, 'update']);
        //Route::get('/transaction', [TransactionApiController::class, 'index']);
        // Route::get('/transaction/{id}', [TransactionApiController::class, 'show']);
        


    });

});
