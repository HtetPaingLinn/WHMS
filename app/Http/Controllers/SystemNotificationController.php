<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockRecord;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SystemNotificationController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::today()->toDateString();

        // Get the latest stock records for each product
        $latestRecords = StockRecord::select('stock_records.*')
            ->whereIn('id', function ($query) use ($currentDate) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('stock_records')
                    ->where('record_date', '<=', $currentDate)
                    ->groupBy('product_id');
            })
            ->where('closing_balance', '<', 2)
            ->with('product')
            ->get();

        return view('adm_system_notification', ['lowStockItems' => $latestRecords]);
    }
}
