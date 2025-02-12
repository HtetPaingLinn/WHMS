<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Models\ServiceCenter;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    public function index()
    {
        $serviceCenters = ServiceCenter::all();

        // Debug log to verify service centers data
        \Log::info('Service Centers loaded:', [
            'count' => $serviceCenters->count(),
            'data' => $serviceCenters->toArray()
        ]);

        if ($serviceCenters->isEmpty()) {
            \Log::warning('No service centers found in database');
        }

        return view('admin_complaint', [
            'complaints' => [
                'pending' => Complaint::where('status', 'pending')->get(),
                'processing' => Complaint::where('status', 'processing')->get(),
                'resolved' => Complaint::where('status', 'resolved')->get(),
                'rejected' => Complaint::where('status', 'rejected')->get(),
            ],
            'serviceCenters' => $serviceCenters
        ]);
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,processing,resolved,rejected'
        ]);

        $complaint->update([
            'status' => $validatedData['status']
        ]);

        return redirect()->back()->with('success', 'Complaint status updated successfully');
    }

    public function assignServiceCenter(Complaint $complaint, Request $request)
    {
        try {
            // Detailed logging
            \Log::info('Service Center Assignment - Request Data:', [
                'complaint_id' => $complaint->id,
                'request_all' => $request->all(),
                'service_center_id' => $request->service_center_id,
                'current_complaint_data' => $complaint->toArray()
            ]);

            $validated = $request->validate([
                'service_center_id' => 'required|exists:service_centers,center_id'
            ]);

            $serviceCenter = ServiceCenter::where('center_id', $validated['service_center_id'])->firstOrFail();

            \Log::info('Service Center Found:', $serviceCenter->toArray());

            DB::beginTransaction();
            try {
                $complaint->service_center_id = $validated['service_center_id'];
                $complaint->warehouse_branch = null;
                $complaint->admin_response = 'Service Center: ' . $serviceCenter->service_center_name;
                $complaint->status = 'resolved';
                $complaint->save();

                DB::commit();

                \Log::info('Updated Complaint Data:', $complaint->fresh()->toArray());
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            return response()->json([
                'success' => true,
                'message' => 'Service center assigned successfully',
                'data' => $complaint->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Service Center Assignment Failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function assignWarehouse(Complaint $complaint, Request $request)
    {
        try {
            // Detailed logging
            \Log::info('Warehouse Assignment - Request Data:', [
                'complaint_id' => $complaint->id,
                'request_all' => $request->all(),
                'warehouse' => $request->warehouse,
                'current_complaint_data' => $complaint->toArray()
            ]);

            $validated = $request->validate([
                'warehouse' => 'required|in:Dawbon,Hlaing'
            ]);

            DB::beginTransaction();
            try {
                $complaint->warehouse_branch = $validated['warehouse'];
                $complaint->service_center_id = null;
                $complaint->admin_response = 'Warehouse: ' . $validated['warehouse'];
                $complaint->status = 'resolved';
                $complaint->save();

                DB::commit();

                \Log::info('Updated Complaint Data:', $complaint->fresh()->toArray());
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            return response()->json([
                'success' => true,
                'message' => 'Warehouse assigned successfully',
                'data' => $complaint->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Warehouse Assignment Failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function complaintHistory()
    {
        $complaints = Complaint::where('owner_id', auth()->user()->partner_shops_id)
            // Remove the join as we already have the invoice_no in complaints table
            ->select('complaints.*')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('complaintHistory', compact('complaints'));
    }
}
