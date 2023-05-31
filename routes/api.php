<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Drewlabs Generated MVC Route Defnitions, Please Do not delete to avoid duplicates route definitions

// Route definitions for posts
Route::get('/posts', [\App\Http\Controllers\PostsController::class, 'index']);
Route::get('/posts/{id}', [\App\Http\Controllers\PostsController::class, 'show']);
Route::post('/posts', [\App\Http\Controllers\PostsController::class, 'store']);
Route::put('/posts/{id}', [\App\Http\Controllers\PostsController::class, 'update']);
Route::delete('/posts/{id}', [\App\Http\Controllers\PostsController::class, 'destroy']);
// !End Route definitions for posts

// Route definitions for post-types
Route::get('/post-types', [\App\Http\Controllers\PostTypesController::class, 'index']);
Route::get('/post-types/{id}', [\App\Http\Controllers\PostTypesController::class, 'show']);
Route::post('/post-types', [\App\Http\Controllers\PostTypesController::class, 'store']);
Route::put('/post-types/{id}', [\App\Http\Controllers\PostTypesController::class, 'update']);
Route::delete('/post-types/{id}', [\App\Http\Controllers\PostTypesController::class, 'destroy']);
// !End Route definitions for post-types

// Route definitions for post-tags
Route::get('/post-tags', [\App\Http\Controllers\PostTagsController::class, 'index']);
Route::get('/post-tags/{id}', [\App\Http\Controllers\PostTagsController::class, 'show']);
Route::post('/post-tags', [\App\Http\Controllers\PostTagsController::class, 'store']);
Route::put('/post-tags/{id}', [\App\Http\Controllers\PostTagsController::class, 'update']);
Route::delete('/post-tags/{id}', [\App\Http\Controllers\PostTagsController::class, 'destroy']);
// !End Route definitions for post-tags

// Route definitions for tags
Route::get('/tags', [\App\Http\Controllers\TagsController::class, 'index']);
Route::get('/tags/{id}', [\App\Http\Controllers\TagsController::class, 'show']);
Route::post('/tags', [\App\Http\Controllers\TagsController::class, 'store']);
Route::put('/tags/{id}', [\App\Http\Controllers\TagsController::class, 'update']);
Route::delete('/tags/{id}', [\App\Http\Controllers\TagsController::class, 'destroy']);
// !End Route definitions for tags

// Route definitions for comments
Route::get('/comments', [\App\Http\Controllers\CommentsController::class, 'index']);
Route::get('/comments/{id}', [\App\Http\Controllers\CommentsController::class, 'show']);
Route::post('/comments', [\App\Http\Controllers\CommentsController::class, 'store']);
Route::put('/comments/{id}', [\App\Http\Controllers\CommentsController::class, 'update']);
Route::delete('/comments/{id}', [\App\Http\Controllers\CommentsController::class, 'destroy']);
// !End Route definitions for comments

// !End Drewlabs Generated MVC Route Defnitions, Please Do not delete to avoid duplicates route definitions
