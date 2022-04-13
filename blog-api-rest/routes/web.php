<?php

use App\Http\Controllers\UserController;
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

Route::get('/', function () {
  $data = array(
    'message' => 'CONGRATS! If you view this message the API REST is working.',
    'status' => 200
  );

  return response()->json($data, 200);
});

Route::prefix('/api/v1/')->group(function() {
  // UserController
  Route::prefix('/user')->group(function() {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/checkToken', [UserController::class, 'checkToken']);
  });
});
