<?php

use App\Http\Controllers\API\AboutController;
use Illuminate\Http\Request;
use App\Http\Middleware\clientToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\AudioController;
use App\Http\Controllers\API\UserController;


Route::any('/banner', [CategoryController::class, "bannerList"]);
Route::any('/categories', [CategoryController::class, "categoryList"]);
Route::any('/genre', [GenreController::class, "genreList"]);
Route::any('/audio', [AudioController::class, "audioList"]);
Route::any('/audioByCategory', [AudioController::class, "audioByCategory"]);
Route::any('/audioByCategoryId', [AudioController::class, "audioByCategoryId"]);
Route::any('/audioByGenre', [AudioController::class, "audioByGenre"]);
Route::any('/audioDetails', [AudioController::class, "audioDetails"]);
Route::any('/search', [AudioController::class, "searchAudios"]);
Route::any('/about-us', [AboutController::class, "about"]);

Route::get('/.well-known/assetlinks.json', function () {
    return response()->file(public_path('.well-known/assetlinks.json'));
});

Route::get('/check-version', [AudioController::class, 'checkApp']);

Route::post('/user-login', [UserController::class, 'login']);
Route::post('/audio-rating', [UserController::class, 'rating']);
Route::post('/view-rating', [UserController::class, 'getRating']);
Route::post('/get-saved-audio', [UserController::class, 'getSavedAudios']);
Route::post('/saved-audio', [UserController::class, 'saveAudio']);
