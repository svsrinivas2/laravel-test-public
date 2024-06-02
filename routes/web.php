<?php

use App\Http\Controllers\SalesController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::redirect('/dashboard', '/sales');

Route::get('/sales', [SalesController::class, 'getSales'])->middleware(['auth'])->name('coffee.sales');
Route::post('sales/store', [SalesController::class, 'store'])->middleware(['auth'])->name('coffee.sales.store');

Route::get('/sales-with-multiple-products', [SalesController::class, 'getMultiProductSales'])->middleware(['auth'])->name('coffee.multisales');
Route::get('/get-product-details', [SalesController::class, 'getProductDetails'])->middleware(['auth'])->name('coffee.product.details');
Route::post('sales/multi-store', [SalesController::class, 'multiStore'])->middleware(['auth'])->name('coffee.sales.multistore');

Route::get('/shipping-partners', function () {
    return view('shipping_partners');
})->middleware(['auth'])->name('shipping.partners');

require __DIR__.'/auth.php';
