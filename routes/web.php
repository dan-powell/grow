<?php

use App\Http\Controllers\{DashboardController, DeviceController};
use App\Models\{Device, Figure};
use Illuminate\Support\Facades\Route;

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

Route::get('/', [DashboardController::class, 'show'])->name('dashboard.show')->breadcrumb('Dashboard');
Route::get('/devices/{device:id}', [DeviceController::class, 'show'])->name('device.show')->breadcrumb(fn (Device $device) => $device->name, 'dashboard.show');
Route::get('/devices/{device:id}/history/{figure:id}', [DeviceController::class, 'history'])
    ->name('device.figure.history')
    ->breadcrumb(fn (Device $device, Figure $figure) => $figure->name, 'device.show');
