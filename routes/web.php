<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [FoodController::class, 'index']);
Route::post('/search', [FoodController::class, 'search'])->name('search');
Route::post('/nutrition-advice', [FoodController::class, 'getNutritionAdvice'])->name('nutrition.advice');
