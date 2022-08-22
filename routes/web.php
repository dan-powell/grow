<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Models\Device;
use App\Models\DeviceDataconfig;

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
Route::get('/devices', [DeviceController::class, 'index'])->name('device.index')->breadcrumb('Devices', 'dashboard.show');
Route::get('/devices/{device:id}', [DeviceController::class, 'show'])->name('device.show')->breadcrumb(fn(Device $device) => $device->name, 'device.index');
Route::get('/devices/{device:id}/readings/{dataconfig:id}', [DeviceController::class, 'reading'])->name('device.reading')->breadcrumb(fn(Device $device, DeviceDataconfig $dataconfig) => $dataconfig->name, 'device.show');;