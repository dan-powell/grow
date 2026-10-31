<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LogController;
use App\Models\Device;
use App\Models\Figure;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'show'])->name('dashboard.show')->breadcrumb('Dashboard');
Route::get('/log', [LogController::class, 'index'])->name('log.index')->breadcrumb('Log', 'dashboard.show');
Route::get('/devices/{device:id}', [DeviceController::class, 'show'])->name('device.show')->breadcrumb(fn (Device $device) => $device->name, 'dashboard.show');
Route::get('/devices/{device:id}/history/{figure:id}', [DeviceController::class, 'history'])
    ->name('device.figure.history')
    ->breadcrumb(fn (Device $device, Figure $figure) => $figure->name, 'device.show');
