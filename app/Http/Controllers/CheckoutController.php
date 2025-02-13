<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesInvoice;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\StockRecord;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        \Log::info('Checkout Request:', [
            'cart' => $request->input('cart'),
            'usePoints' => $request->input('use_points')
        ]);

        $cart = $request->input('cart');
        $usePoints = $request->input('use_points', false);

        DB::beginTransaction();
        try {
            $invoiceNo = $this->generateInvoiceNo();
            $userId = Auth::id();

            \Log::info('Processing checkout:', [
                'invoiceNo' => $invoiceNo,
                'userId' => $userId
            ]);

            foreach ($cart as $item) {
                \Log::info('Processing item:', $item);

                $product = Product::find($item['id']);
                if (!$product) {
                    throw new \Exception("Product not found: {$item['id']}");
                }

                // Get the latest stock record for this product
                $latestStockRecord = StockRecord::where('product_id', $item['id'])
                    ->latest('record_date')
                    ->first();

                if (!$latestStockRecord) {
                    throw new \Exception("No stock record found for product: {$item['id']}");
                }

                // Check if we have enough stock
                $availableStock = $latestStockRecord->closing_balance;
                if ($availableStock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->item_name}");
                }

                $today = now()->toDateString();
                $recordDate = Carbon::parse($latestStockRecord->record_date)->toDateString();
                
                if ($recordDate === $today) {
                    // Update existing record for today
                    $newDispatchedQuantity = $latestStockRecord->dispatched + $item['quantity'];
                    $newClosingBalance = ($latestStockRecord->opening_balance + $latestStockRecord->received) - $newDispatchedQuantity;
                    
                    $latestStockRecord->dispatched = $newDispatchedQuantity;
                    $latestStockRecord->closing_balance = $newClosingBalance;
                    $latestStockRecord->save();
                } else {
                    // Create new record for today
                    $newStockRecord = new StockRecord([
                        'record_date' => $today,
                        'product_id' => $item['id'],
                        'warehouse_branch' => $latestStockRecord->warehouse_branch,
                        'opening_balance' => $latestStockRecord->closing_balance,
                        'received' => 0,
                        'dispatched' => $item['quantity'],
                        'closing_balance' => $latestStockRecord->closing_balance - $item['quantity'],
                        'system_users_id' => 1, // You might want to adjust this based on your system
                    ]);
                    $newStockRecord->save();
                }

                if ($usePoints) {
                    $totalAmount = collect($cart)->sum(function($item) {
                        return $item['price'] * $item['quantity'];
                    });
                    
                    $user = Auth::user();
                    
                    // Check if user has enough points
                    if ($user->points < $totalAmount) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Insufficient points for this purchase.'
                        ], 400);
                    }
                    
                    // Deduct points and set payment status
                    $user->points -= $totalAmount;
                    $user->save();
                    
                    // Set payment status based on points usage
                    $paymentStatus = 'Paid';
                    $successMessage = "Order placed successfully using points.";
                } else {
                    $paymentStatus = 'Pending';
                    $successMessage = "Order placed successfully. Payment pending.";
                }

                $salesInvoice = new SalesInvoice([
                    'sale_date' => now()->toDateString(),
                    'invoice_no' => $invoiceNo,
                    'partner_shops_id' => $userId,
                    'product_id' => $item['id'],
                    'cash_back_mmk' => 0,
                    'quantity' => $item['quantity'],
                    'total_mmk' => $item['price'] * $item['quantity'],
                    'delivered' => 0,
                    'payment' => $paymentStatus,
                    'completed' => 0,
                    'remarks' => null,
                ]);
                $salesInvoice->save();

                \Log::info('Created sales invoice:', [
                    'invoice' => $salesInvoice->toArray()
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true, 
                'invoice_no' => $invoiceNo,
                'message' => $successMessage
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function generateInvoiceNo()
    {
        $date = now();
        $milliseconds = $date->format('v');
        return 'INV' . $date->format('ymd') . $milliseconds;
    }
}