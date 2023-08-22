<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\ArticleController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\CommentsController;
use App\Http\Controllers\TagController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{id}', [TagController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::patch('/user/{id}', [UserController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/articles', [ArticleController::class, 'store']);
    Route::patch('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
    Route::get('/articles.export', [ArticleController::class, 'export']);

    Route::post('/tags', [TagController::class, 'store']);
    Route::patch('/tags/{id}', [TagController::class, 'update']);
    Route::delete('/tags/{id}', [TagController::class, 'destroy']);
    Route::post('/articles/{article}/tag.attach', [ArticleController::class, 'attachTags']);
    Route::post('/articles/{article}/tag.detach', [TagController::class, 'detachTags']);
    Route::post('/articles/{article}/tag.sync', [TagController::class, 'syncTags']);

    // Categories
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::patch('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    // Comments
    Route::post('/comments', [CommentsController::class, 'store']);
    Route::patch('/comments/{comment}', [CommentsController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentsController::class, 'destroy']);

});
