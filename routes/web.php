<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return redirect()->route('orders.index');
});

Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
Route::resource('employees', EmployeeController::class);
Route::resource('materials', MaterialController::class);
Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);
Route::resource('payments', PaymentController::class);
Route::put('/productions/{production}', [ProductionController::class, 'update'])->name('productions.update');
// Route::resource('suppliers', SupplierController::class); // removed - suppliers managed via materials view
Route::get('/stocks/create', [StockController::class, 'create'])->name('stocks.create');
Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
