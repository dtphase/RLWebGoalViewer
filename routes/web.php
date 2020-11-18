<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ReplayController;
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

Route::get('/players', [PlayerController::class, 'displayPlayerList']);

Route::get('/replays/{replayId}', [PlayerController::class, 'displayPlayerReplayList']);

Route::get('/api/replays/{steamId}', [PlayerController::class, 'getReplaysBySteamId']);

Route::get('/api/replays/{replayId}/download', [ReplayController::class, 'downloadReplayById']);

Route::get('/api/replays/{replayId}/store', [ReplayController::class, 'storeReplay']);

Route::get('/api/replays/{replayId}/analyze', [ReplayController::class, 'analyzeReplay']);

Route::get('/api/replays/active/{playerId}', [ReplayController::class, 'getActiveReplay']);

Route::get('/api/replays/active/{playerId}/next', [ReplayController::class, 'nextActiveReplay']);