<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
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
  // User Controller
  Route::prefix('/user')->group(function() {
    Route::get('/{id}', [UserController::class, 'profile']);

    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/update', [UserController::class, 'update'])->middleware(ApiAuthMiddleware::class);
    Route::post('/checkToken', [UserController::class, 'checkToken']);
  });

  // Categories Controller
  Route::resource('/category', CategoryController::class);

  // Post Controller
  Route::resource('/post', PostController::class);
  Route::get('/post/category/{id}', [PostController::class, 'getPostsByCategory']);
  Route::get('/post/author/{id}', [PostController::class, 'getPostsByAuthor']);
});
