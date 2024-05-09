<?php

use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [GroupController::class,'index'])
    ->name('group.index');

Route::post('/store-answer', [GroupController::class,'storeAnswer'])
    ->name('answer.create');

Route::delete('/start/over/{groupId}', [GroupController::class,'startOver'])
    ->name('group.start-over');

