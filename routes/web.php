<?php

use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PointsController;


Route::post('/deduct-points', [PointsController::class, 'deductPoints']);
Route::post('/increase-points', [PointsController::class, 'increasePoints'])->middleware('auth');


Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('customerdashboard');
    })->name('dashboard');

    Route::get('/orderlists', function () {
        return view('customerorderlist');
    });

    Route::get('/customerhistory', function () {
        return view('customerhistory');
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::get('/products/filter', function (Request $request) {
    $category = $request->query('category');

    $products = $category === 'all'
        ? Product::all()
        : Product::where('category', $category)->get();

    return response()->json(['products' => $products]);
});

Route::get('/products/details/{id}', function ($id) {
    $product = Product::with('stockRecords')->find($id);

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    // Get latest closing balance
    $latestStockRecord = $product->stockRecords()->latest('record_date')->first();

    return response()->json([
        'product' => $product,
        'latest_closing_balance' => $latestStockRecord ? $latestStockRecord->closing_balance : 'N/A'
    ]);
});
