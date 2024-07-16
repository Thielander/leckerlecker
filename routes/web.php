<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;

// Startseite mit dem Suchformular
Route::get('/', [FoodController::class, 'index'])->name('home');

// Route für die Suchanfrage
Route::post('/search', [FoodController::class, 'search'])->name('search');

// Route für die Gesundheitsbewertung
Route::post('/getHealthAssessment', [FoodController::class, 'getHealthAssessment'])->name('getHealthAssessment');
