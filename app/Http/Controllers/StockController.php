<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class StockController extends Controller
{
    public function downloadCSV(Request $request)
    {
        // Get start and end dates from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch data from the database
        $query = DB::table('stock_records')
            ->join('products', 'stock_records.product_id', '=', 'products.id')
            ->select('products.category AS Category', 'products.item_name AS Model', 'stock_records.opening_balance AS OpeningBalance', 'stock_records.received AS In', 'stock_records.dispatched AS Out', 'stock_records.closing_balance AS ClosingBalance');

        // Apply date range filter if dates are provided
        if ($startDate && $endDate) {
            $query->whereBetween('stock_records.record_date', [$startDate, $endDate]);
        }

        // Execute the query
        $results = $query->get();

        // Prepare CSV data
        $csvData = "No.,Category,Model,Opening Balance,In,Out,Closing Balance\n";
        $no = 1;
        foreach ($results as $row) {
            $csvData .= "{$no},{$row->Category},{$row->Model},{$row->OpeningBalance},{$row->In},{$row->Out},{$row->ClosingBalance}\n";
            $no++;
        }

        // Return the CSV as a downloadable file
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="stock_report.csv"');
    }

}
