<?php

use App\Http\Controllers\Admin\Course\CategorieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;


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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group([
 
    'middleware' => 'api',
    'prefix' => 'auth'
 
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login_tienda', [AuthController::class, 'login_tienda'])->name('login_tienda');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});


Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::resource('/users',UserController::class);
     Route::post('/users/{id}',[UserController::class, "update"]);
  //
     Route::resource('/categorie',CategorieController::class);
     Route::post('/categorie/{id}',[CategorieController::class, "update"]);
 //
     Route::resource('/course',CourseGController::class);
     Route::post('/course/{id}',[CourseGController::class, "update"]);
   
});