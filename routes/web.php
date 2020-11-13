<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/replays/{steamId}', [PlayerController::class, 'getReplaysBySteamId']);

Route::get('/api/replays/{replayId}/download', [PlayerController::class, 'downloadReplayById']);

Route::get('/api/replays/{replayId}/store', [PlayerController::class, 'storeReplay']);