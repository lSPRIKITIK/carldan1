<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductionController;

Route::get('/', function () {
    return redirect()->route('orders.index');
});

Route::resource('clients', ClientController::class);
Route::resource('employees', EmployeeController::class);
Route::resource('materials', MaterialController::class);
Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);
Route::resource('payments', PaymentController::class);
Route::put('/productions/{production}', [ProductionController::class, 'update'])->name('productions.update');
