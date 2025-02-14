<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController
{
    public static function data_show(Request $request): View|Factory|Application
    {
        // Start SQL for today's records
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        // Fetch distinct product IDs
        $productIds = DB::table('stock_records')
            ->distinct()
            ->pluck('product_id');

        // Get today's stock records
        $todayRecords = DB::table('stock_records')
            ->join('products', 'stock_records.product_id', '=', 'products.id')
            ->whereDate('record_date', $selectedDate)
            ->select('stock_records.*', 'products.item_name')
            ->get()
            ->keyBy('product_id');

        $missingProducts = $productIds->diff($todayRecords->keys());

        $pastRecords = collect();

        if ($missingProducts->isNotEmpty()) {
            $pastRecords = DB::table('stock_records')
                ->join('products', 'stock_records.product_id', '=', 'products.id')
                ->whereIn('stock_records.product_id', $missingProducts)
                ->whereDate('record_date', '<', $selectedDate)
                ->orderBy('record_date', 'desc')
                ->select('stock_records.*', 'products.item_name')
                ->get()
                ->groupBy('product_id')
                ->map(function ($records) {
                    $latestRecord = $records->first(); // Get the most recent past record
                    return (object)[
                        'id' => $latestRecord->id,
                        'record_date' => $latestRecord->record_date,
                        'product_id' => $latestRecord->product_id,
                        'warehouse_branch' => $latestRecord->warehouse_branch,
                        'item_name' => $latestRecord->item_name,
                        'opening_balance' => $latestRecord->closing_balance, // Set closing balance as opening balance
                        'received' => 0, // Set received to 0
                        'dispatched' => 0, // Set dispatched to 0
                        'closing_balance' => $latestRecord->closing_balance,
                        'system_users_id' => $latestRecord->system_users_id,
                        'created_at' => $latestRecord->created_at,
                        'updated_at' => $latestRecord->updated_at,
                    ];
                });
        }

        // Merge both today's and past records
        $finalRecords = $todayRecords->merge($pastRecords)->sortBy('product_id')->values();

        // end sql for today's record

        // start sql for empty record
        $zeroBalanceRecords = DB::table('stock_records')
            ->join('products', 'stock_records.product_id', '=', 'products.id')
            ->where('closing_balance', 0)
            ->where(function ($query) use ($selectedDate) {
                $query->whereDate('record_date', $selectedDate)
                    ->orWhere('record_date', function ($subQuery) use ($selectedDate) {
                        $subQuery->selectRaw('MAX(record_date)')
                            ->from('stock_records as sr')
                            ->where('sr.product_id', 'stock_records.product_id')
                            ->where('record_date', '<', $selectedDate);
                    });
            })
            ->select('stock_records.*', 'products.item_name')
            ->get();

        // end sql for empty record

        // start sql for hot items

        $hotRecords = $todayRecords->merge($pastRecords)->sortByDesc('dispatched')->values();

        return \view('adm_inventory', [
            'finalRecords' => $finalRecords,
            'zeroRecords' => $zeroBalanceRecords,
            'hotRecords' => $hotRecords,
        ]);
    }

    public static function data_update(Request $request)
    {
        $input = $request->validate([
            'amount' => 'required|integer',
            'id' => 'required|integer',
        ]);

        $todayDate = now()->toDateString(); // Get today's date

        // Retrieve the record with the given 'id'
        $record = DB::table('stock_records')
            ->where('id', $input['id'])
            ->select('id', 'record_date', 'received', 'closing_balance', 'dispatched', 'product_id', 'system_users_id')
            ->first();

        if ($record) {
            if ($record->record_date !== $todayDate) {
                // If the record's date is not today, create a new record
                $openingBalance = $record->closing_balance; // Use the previous day's closing_balance
                $received = $input['amount'];
                $dispatched = 0; // No dispatched items for a new day
                $closingBalance = $openingBalance + $received - $dispatched;
                $product_id = $record -> product_id;
                $system_user_id = $record -> system_users_id;

                DB::table('stock_records')->insert([
                    'record_date' => $todayDate,
                    'opening_balance' => $openingBalance,
                    'received' => $received,
                    'dispatched' => $dispatched,
                    'closing_balance' => $closingBalance,
                    'warehouse_branch' => 'Dawbon',
                    'product_id' => $product_id,
                    'system_users_id' => $system_user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                notify()->success('Stock updated successfully.');

                return redirect()->back()->with('success', 'Stock updated successfully.');
            } else {
                // If the record's date is today, update the 'received' value
                $newReceived = $record->received + $input['amount'];
                $closingBalance = $record->closing_balance + $input['amount']; // Update closing_balance as well

                DB::table('stock_records')
                    ->where('id', $input['id'])
                    ->update([
                        'received' => $newReceived,
                        'closing_balance' => $closingBalance,
                        'updated_at' => now(),
                    ]);

                notify()->success('Stock updated successfully.');

                return redirect()->back()->with('success', 'Stock updated successfully.');
            }
        } else {
            // Handle the case where the record doesn't exist
            return response()->json(['error' => 'Record not found'], 404);
        }
    }


}
