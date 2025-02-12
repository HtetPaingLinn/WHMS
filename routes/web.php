<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

    // Admin Complaint Form handling route later add middleware and authorization
    Route::get('/adminComplaint', [ComplaintController::class, 'index'])->name('admin.complaints');

    Route::post('/complaints/{complaint}/assign-service-center', [ComplaintController::class, 'assignServiceCenter'])
        ->name('complaints.assign-service-center');

    Route::post('/complaints/{complaint}/assign-warehouse', [ComplaintController::class, 'assignWarehouse'])
        ->name('complaints.assign-warehouse');

    Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])
        ->name('complaints.update');


    //  Complaint History for client side
    Route::get('/complaintHistory', [ComplaintController::class, 'complaintHistory'])->name('complaintHistory');

    Route::get('/orderhistory', [OrderHistoryController::class, 'index'])->name('orderhistory.index');
    Route::post('/complaints', [OrderHistoryController::class, 'store'])->name('complaints.store');

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


Route::post('/customer/checkout', [CheckoutController::class, 'checkout'])->name('customer.checkout');

