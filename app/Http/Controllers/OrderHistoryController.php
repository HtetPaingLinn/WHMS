<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\SalesInvoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index()
    {
        // Get all sales for the authenticated user's partner shop
        $sales = SalesInvoice::where('partner_shops_id', Auth::user()->partner_shops_id)
            ->with(['product', 'complaints']) // Include relationships
            ->orderBy('sale_date', 'desc')
            ->get();

        // Get all complaints for these sales
        $complaints = Complaint::whereIn('invoice_id', $sales->pluck('id'))
            ->orderBy('complain_date', 'desc')
            ->get();

        // Get all products involved in these sales
        $products = Product::whereIn('id', $sales->pluck('product_id'))
            ->get();

        return view('customerhistory', compact('sales', 'complaints', 'products'));
    }

    public function store(Request $request)
    {
        // Add debugging
        \Log::info('Form submitted with data:', $request->all());

        // Validate the request
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'invoice_id' => 'required|exists:sales_invoices,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'issue_type' => 'required|in:faulty_product,mismatch_order',
            'remarks' => 'nullable|string',
        ]);

        try {
            $invoice = SalesInvoice::findOrFail($request->invoice_id);
            $product = Product::findOrFail($request->product_id);

            // Check if complaint already exists
            $existingComplaint = Complaint::where('invoice_no', $invoice->invoice_no)
                ->where('product_id', $product->id)
                ->where('owner_id', Auth::user()->partner_shops_id)
                ->first();

            if ($existingComplaint) {
                return redirect()->back()
                    ->with('error', 'A complaint for this product has already been submitted.')
                    ->withInput();
            }

            Complaint::create([
                'invoice_no' => $invoice->invoice_no,
                'product_id' => $product->id,
                'product_name' => $product->item_name,
                'quantity' => $request->quantity,
                'issue_type' => $request->issue_type,
                'customer_phone' => $request->customer_phone,
                'remark' => $request->remarks,
                'status' => 'pending',
                'complain_date' => now(),
                'owner_id' => Auth::user()->partner_shops_id,
            ]);

            \Log::info('Complaint created successfully');
            return redirect()->back()->with('success', 'Thank you for your complaint. We will review it and get back to you as soon as possible.');
        } catch (\Exception $e) {
            \Log::error('Error creating complaint:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }

    public function show($id)
    {
        $complaint = Complaint::with(['product', 'salesInvoice'])
            ->findOrFail($id);

        if ($complaint->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['complaint' => $complaint]);
    }

    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        if ($complaint->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,processing,resolved',
            'remarks' => 'sometimes|string',
        ]);

        $complaint->update($validated);

        return response()->json(['message' => 'Complaint updated successfully', 'complaint' => $complaint]);
    }

    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);

        if ($complaint->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $complaint->delete();

        return response()->json(['message' => 'Complaint deleted successfully']);
    }
}
