<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [FoodController::class, 'index'])->name('home');
Route::post('/search', [FoodController::class, 'search'])->name('search');
Route::post('/getHealthAssessment', [FoodController::class, 'getHealthAssessment'])->name('getHealthAssessment');
