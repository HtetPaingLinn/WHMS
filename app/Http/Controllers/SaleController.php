<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        // Get filter dates from request or default to today
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        // Fetch Monthly Sales (Last 12 Months)
        $monthlySales = DB::table('sales_invoices')
            ->selectRaw("strftime('%Y-%m', sale_date) AS month, SUM(total_mmk) AS total_sales_mmk, COUNT(id) AS total_invoices, SUM(quantity) AS total_quantity")
            ->where('sale_date', '>=', Carbon::now()->subMonths(12)->startOfMonth()->toDateString())
            ->groupBy('month')
            ->orderBy('month', 'DESC')
            ->get();

        $dailySales = DB::table('sales_invoices')
            ->whereDate('sale_date', $selectedDate)
            ->select('id', 'sale_date', 'invoice_no', 'partner_shops_id', 'brand', 'category', 'total_mmk', 'quantity', 'payment', 'delivered')
            ->orderBy('delivered', 'ASC')  // Sort by delivered status (0 first, 1 last)
            ->orderBy('sale_date', 'DESC') // Then sort by latest sale date
            ->get();

        return view('adm_invoices', compact('monthlySales', 'dailySales', 'selectedDate'));
    }

    public function show($id)
    {
        // Fetch the sale invoice details by id
        $saleInvoice = DB::table('sales_invoices')
            ->where('id', $id)
            ->first();

        // If you want to fetch more details (e.g. associated product info, shop info)
        $product = DB::table('products')
            ->where('id', $saleInvoice->product_id)
            ->first();

        $partnerShop = DB::table('partner_shops')
            ->where('partner_shops_id', $saleInvoice->partner_shops_id)
            ->first();

        // You can also include other related data as needed, e.g., payment status or other fields.

        return view('invoice_details', compact('saleInvoice', 'product', 'partnerShop'));
    }

    public function getInvoiceDetails($invoiceNo)
    {
        // Fetch the sale invoice with the related product and partner shop in one query
        $invoiceDetails = DB::table('sales_invoices as si')
            ->join('products as p', 'p.id', '=', 'si.product_id')
            ->join('partner_shops as ps', 'ps.partner_shops_id', '=', 'si.partner_shops_id')
            ->where('si.invoice_no', $invoiceNo)  // Query by invoice_no
            ->select(
                'si.invoice_no',
                'si.sale_date',
                'si.total_mmk',
                'si.quantity',
                'p.item_name as product_name',
                'ps.partner_shops_name as shop_name'  // Updated column name
            )
            ->first();

        if (!$invoiceDetails) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        return response()->json([
            'invoice_no' => $invoiceDetails->invoice_no,
            'sale_date' => \Carbon\Carbon::parse($invoiceDetails->sale_date)->format('d/m/Y'),
            'total_mmk' => $invoiceDetails->total_mmk,
            'quantity' => $invoiceDetails->quantity,
            'product_name' => $invoiceDetails->product_name,
            'shop_name' => $invoiceDetails->shop_name,  // Corrected here
        ]);
    }

    public function getMonthlySales()
    {
        $salesData = DB::table('sales_invoices')
            ->selectRaw('strftime("%Y-%m", sale_date) AS sale_date, SUM(total_mmk) AS total_sales')
            ->groupBy(DB::raw('strftime("%Y", sale_date)'), DB::raw('strftime("%m", sale_date)'))
            ->orderBy('sale_date', 'desc')
            ->get();

        return view('adm_dashboard', compact('salesData'));
    }
}
