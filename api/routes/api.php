<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/auth/signup', [UserController::class, 'sing']);
Route::delete('/auth/signout', [UserController::class, 'delete'])->middleware('jwt');
Route::post('/signin', [UserController::class, 'signin']);
Route::get('/movies/{id}', [MovieController::class, 'getIdMovies']);
Route::get('/artist/{id}', [MovieController::class, 'getIdArtist']);
Route::get('/movies/{page?}/{pageSize?}/{sortDir?}/{sortBy?}', [MovieController::class, 'getMovies']);
Route::get('/artists/{page?}/{pageSize?}/{sortDir?}', [MovieController::class, 'getArtist']);
Route::get('/genres/{page?}/{pageSize?}/{sortDir?}', [MovieController::class, 'getGenere']);
Route::get('/media/{id?}', [MovieController::class, 'getMedia']);

Route::any('/{any}', function(){
    return res([], 404);
});