<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ServiceCenter;
use App\Models\StockRecord;
use App\Models\PartnerShop;
use Database\Seeders\ServiceCentersSeeder;
use Illuminate\Http\Request;

class StockDataController extends Controller
{
    public function getStockData()
    {
        // Count all stock records (models)
        $modelsCount = Product::count();  // Count all rows in the stock_records table

        // Count all partner shops (customers)
        $customersCount = PartnerShop::count();  // Count all rows in the partner_shops table

        // Count all service centers (just count warehouse branches)
        $serviceCentersCount = ServiceCenter::count();  // Count all rows in stock_records

        // Count warehouse branches - distinct by 'warehouse_branch'
        $warehouseBranchesCount = Product::distinct('warehouse_branch')->count();

        // Return data as JSON response
        return response()->json([
            'modelsCount' => $modelsCount,
            'customersCount' => $customersCount,
            'serviceCentersCount' => $serviceCentersCount,
            'warehouseBranchesCount' => $warehouseBranchesCount,
        ]);
    }
}
