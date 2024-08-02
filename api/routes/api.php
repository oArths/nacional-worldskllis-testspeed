<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/media/{id?}', [MovieController::class, 'getMedia']);


Route::post('/auth/signup', [UserController::class, 'sing']);
Route::delete('/auth/signout', [UserController::class, 'delete'])->middleware('jwt');
Route::post('/signin', [UserController::class, 'signin']);
Route::get('/movies/{id?}', [MovieController::class, 'getIdMovies'])->middleware('jwt');
Route::get('/artist/{id}', [MovieController::class, 'getIdArtist'])->middleware('jwt');
Route::get('/movies/{page?}/{pageSize?}/{sortDir?}/{sortBy?}', [MovieController::class, 'getMovies'])->middleware('jwt');
Route::get('/artists/{page?}/{pageSize?}/{sortDir?}', [MovieController::class, 'getArtist'])->middleware('jwt');
Route::get('/genres/{page?}/{pageSize?}/{sortDir?}', [MovieController::class, 'getGenere'])->middleware('jwt');
Route::get('/genres/{page?}/{pageSize?}/{sortDir?}', [MovieController::class, 'getGenere'])->middleware('jwt');
Route::post('/reviews/evaluations/{reviewId?}/{positive?}', [MovieController::class, 'CreateReviweEvalatiuon'])->middleware('jwt');
Route::post('/reviews/{movieId?}/{stars?}/{content?}', [MovieController::class, 'CreateReviwe'])->middleware('jwt');
Route::delete('/reviews/{movieId}', [MovieController::class, 'DeleteReviwe'])->middleware('jwt');
Route::delete('/reviews/evaluations/{reviewId?}', [MovieController::class, 'DeleteReviweevaluations'])->middleware('jwt');
Route::get('/reviews/{movieId?}/{page?}/{pageSize?}/{sortDir?}/{sortBy?}', [MovieController::class, 'ListReviews'])->middleware('jwt');

Route::any('/{any}', function () {
    return res([], 404);
});
