<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PointsController extends Controller
{
    public function deductPoints(Request $request)
    {
        // Validate the request
        $request->validate([
            'points_used' => 'required|integer|min:1',
            'partner_shops_id' => 'required|integer|exists:partner_shops,partner_shops_id'
        ]);

        // Get the partner shop's current points
        $partnerShop = DB::table('partner_shops')
            ->where('partner_shops_id', $request->input('partner_shops_id'))
            ->first();

        if (!$partnerShop) {
            return response()->json(['success' => false, 'message' => 'Partner shop not found.']);
        }

        $currentPoints = $partnerShop->points;
        $pointsUsed = $request->input('points_used');

        // Check if the partner shop has enough points
        if ($currentPoints < $pointsUsed) {
            return response()->json(['success' => false, 'message' => 'Not enough points.']);
        }

        // Deduct the points
        $remainingPoints = $currentPoints - $pointsUsed;

        DB::table('partner_shops')
            ->where('partner_shops_id', $request->input('partner_shops_id'))
            ->update(['points' => $remainingPoints]);

        return response()->json([
            'success' => true,
            'remaining_points' => $remainingPoints
        ]);
    }

    public function increasePoints(Request $request)
    {
        // Validate request
        $request->validate([
            'cart_total' => 'required|integer|min:100',
            'partner_shops_id' => 'required|integer|exists:partner_shops,partner_shops_id'
        ]);

        // Fetch the authenticated partner shop
        $partnerShop = Auth::user();

        if (!$partnerShop) {
            return response()->json(['success' => false, 'message' => 'Partner shop not found.']);
        }

        $cartTotal = $request->input('cart_total');

        // Calculate earned points (1 point per 100 MMK)
        $earnedPoints = floor($cartTotal / 100);

        // Increase points in database
        $newPoints = $partnerShop->points + $earnedPoints;

        DB::table('partner_shops')
            ->where('partner_shops_id', $partnerShop->partner_shops_id)
            ->update(['points' => $newPoints]);

        return response()->json([
            'success' => true,
            'earned_points' => $earnedPoints,
            'total_points' => $newPoints
        ]);
    }

}
