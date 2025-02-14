<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function markAsComplete($id)
{
    try {
        $delivery = SalesInvoice::findOrFail($id);
        $delivery->update(['completed' => 1]);
        $delivery->update(['payment' => 'Paid']);

        return response()->json([
            'success' => true,
            'message' => 'Delivery marked as complete'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating delivery status'
        ], 500);
    }
}
}
