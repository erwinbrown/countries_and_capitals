<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;


Route::get('/', [MainController::class, 'startGame'])->name('start_game');
Route::post('/', [MainController::class, 'prepareGame'])->name('preprare_game');


Route::get('/game', [MainController::class, 'game'])->name('game');

Route::get('/answer/{answer}', [MainController::class, 'answer'])->name('respuesta');

Route::get('/next_question', [MainController::class, 'nextQuestion'])->name('next_question');

Route::get('/show_results', [MainController::class, 'showResults'])->name('show_results');