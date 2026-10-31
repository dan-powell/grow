<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ReadingController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('reading', [ReadingController::class, 'store'])->middleware('auth.basic')->name('reading.store');
