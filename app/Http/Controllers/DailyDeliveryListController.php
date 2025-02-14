<?php

namespace App\Http\Controllers;


use App\Models\Delivery;
use App\Models\PartnerShop; // Assuming you have a ShopPartner model
use Illuminate\Http\Request;
use App\Models\DailyDeliveryList;
use Illuminate\Support\Facades\Log;

class DailyDeliveryListController extends Controller
{
    public function index()
    {
// Fetch daily delivery lists along with driver details and shop partner details
        $deliveryLists = DailyDeliveryList::with(['salesInvoice.partnerShop', 'driver']) // Eager load relationships
        ->where('delivery_status', 'Pending')
            ->limit(4)
            ->get();

        return view('delivery_dashboard', compact('deliveryLists'));
    }
    public function updateDeliveryStatus(Request $request)
    {
        try {
            // Retrieve the delivery ID from the request
            $deliveryId = $request->input('id');

            // Find the delivery record by ID (will throw a ModelNotFoundException if not found)
            $delivery = DailyDeliveryList::findOrFail($deliveryId);

            // Update delivery status if found
            $delivery->delivery_status = 'In Progress';
            $delivery->save();

            // Return success response
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // If the record is not found, return 404 response
            return response()->json(['success' => false, 'message' => 'Delivery not found'], 404);
        } catch (\Exception $e) {
            // Catch any other exceptions and log the error
            \Log::error('Error updating delivery status: ' . $e->getMessage());

            // Return a generic error message
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the status. Please try again later.'], 500);
        }
    }
}
