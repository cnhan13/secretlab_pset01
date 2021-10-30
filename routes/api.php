<?php

use App\Http\Controllers\EntryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/object', [EntryController::class, 'store']);
Route::get('/object/get_all_records', [EntryController::class, 'allRecords']);
Route::get('/object/{key}', [EntryController::class, 'show']);
