<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DailyDeliveryListController;
use App\Http\Controllers\DeliveryAuthController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockDataController;
use App\Http\Controllers\SystemNotificationController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::get('/adm-logout', function() {
    return view('admwelcome');
});

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

    Route::get('/customer_message', function () {
        return view('customer_message');
    });

    Route::get('/map', function(){
        return view('map');
    });
});

Route::get('/products/filter', function (Request $request) {
    $category = $request->query('category');

    $query = Product::whereIn('id', function($query) {
        $query->select('product_id')
            ->from('stock_records as sr1')
            ->whereRaw('sr1.record_date = (
                SELECT MAX(record_date)
                FROM stock_records as sr2
                WHERE sr2.product_id = sr1.product_id
            )')
            ->where('closing_balance', '>', 0);
    });

    if ($category !== 'all') {
        $query->where('category', $category);
    }

    $products = $query->get();

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

Route::put('/sales/{sale}/cancel', [OrderHistoryController::class, 'cancelSale'])->name('sales.cancel');

Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');



//Admin Routes
// Login Br Nyar!
Route::get('/adm-login', function () {
    return view('admwelcome');
});


Route::post('/adm-login', [AuthenticatedSessionController::class, 'systemUserLogin'])->name('login.win_p');


// Routes For ChartController
use App\Http\Controllers\ChartController;
Route::get('/adm-dsh', [ChartController::class, 'index'])->name('admin.chart');


//Admin-Customer
Route::get('/customers', [ShopController::class, 'showCustomers']) -> name('customers.show');
Route::delete('/customers/{id}', [ShopController::class, 'destroy']) -> name('customers.destroy');
Route::patch('/customers/update', [ShopController::class, 'update']) -> name('customers.update');
Route::post('/customers', [ShopController::class, 'store']) -> name('customers.store');

//Admin-Product
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::patch('/products/update', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::post('/products', [ProductController::class, 'store'])->name('product.store');

//Adm-Inventory
Route::get('/inventory', [InventoryController::class, 'data_show']) -> name('inventory.show');
Route::patch('/inventory/update', [InventoryController::class, 'data_update']) -> name('inventory.update');

//adm-sale-invoice
Route::get('/invoice/{invoiceNo}', [SaleController::class, 'getInvoiceDetails']);
Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
Route::get('invoice/details/{invoice_no}', [SaleController::class, 'showInvoiceDetails'])->name('invoice.details');



//Adm-Complaint
Route::get('/adminComplaint', [ComplaintController::class, 'index'])->name('admin.complaints');

Route::post('/complaints/{complaint}/assign-service-center', [ComplaintController::class, 'assignServiceCenter'])
    ->name('complaints.assign-service-center');

Route::post('/complaints/{complaint}/assign-warehouse', [ComplaintController::class, 'assignWarehouse'])
    ->name('complaints.assign-warehouse');

Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])
    ->name('complaints.update');


//Routes For CSV.DOWNLOAD
Route::get('/download-stock-csv', [StockController::class, 'downloadCSV'])->name('stock.downloadCSV');


// Driver Dashboard Blade
Route::get('/del-dsh', function () {
    return view('delivery_dashboard');
})->name('del-dsh');


// Show login form
Route::get('del-login', [DeliveryAuthController::class, 'showLoginForm'])->name('del-login');
Route::post('del-login', [DeliveryAuthController::class, 'login'])->name('driver.login.submit'); // Or keep 'del-login'
Route::post('del-logout', [DeliveryAuthController::class, 'logout'])->name('driver.logout'); // Or keep 'del-logout'

// Delivery Man Pages

Route::post('/delivery/{id}/complete', [DeliveryController::class, 'markAsComplete'])->name('delivery.complete');

// Define a route for fetching the stock data

Route::get('/get-stock-data', [StockDataController::class, 'getStockData']);

Route::get('/system_notification', [SystemNotificationController::class, 'index'])->name('system.notification');


// Route for tech Trender
Route::get('/trender', function () {
    return view('adm_techtrender');
});

// Route for tech Facult Detection
Route::get('/detector', function () {
    return view('adm_faultdetect');
});

//Tracking
Route::get('/track_deliveries', function () {
    return view('track_deliveries');
});



Route::post('/sales/{sale}/toggle-delivery', [SaleController::class, 'toggleDelivery'])
    ->name('sales.toggle-delivery');
