<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/auth/signup', [UserController::class, 'sing']);
Route::delete('/auth/signout', [UserController::class, 'delete'])->middleware('jwt');
Route::post('/signin', [UserController::class, 'signin']);

Route::any('/{any}', function(){
    return res([], 404);
});