<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\QrLimitRequest;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrLimitRequestController extends Controller
{
    /**
     * Display QR limit request form
     */
    public function index()
    {
        $school = Auth::user()->managedSchool;
        
        if (!$school) {
            return redirect()->route('admin.dashboard')->with('error', 'No school assigned to your account.');
        }

        $currentRequest = QrLimitRequest::where('school_id', $school->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        return view('admin.payment.qr-limit-request.index', compact('school', 'currentRequest'));
    }

    /**
     * Create a new QR limit request
     */
    public function store(Request $request)
    {
        $school = Auth::user()->managedSchool;
        
        if (!$school) {
            return response()->json(['error' => 'No school assigned to your account.'], 400);
        }

        $request->validate([
            'requested_limit' => 'required|integer|min:' . ($school->qr_code_limit + 1) . '|max:100',
            'reason' => 'required|string|max:500'
        ]);

        // Check if there's already a pending request
        $existingRequest = QrLimitRequest::where('school_id', $school->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return response()->json(['error' => 'You already have a pending request. Please wait for it to be processed.'], 400);
        }

        $qrLimitRequest = QrLimitRequest::create([
            'school_id' => $school->id,
            'admin_id' => Auth::id(),
            'current_limit' => $school->qr_code_limit,
            'requested_limit' => $request->requested_limit,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QR limit increase request submitted successfully. You will be notified once it\'s processed.',
            'request' => $qrLimitRequest
        ]);
    }

    /**
     * Get request history
     */
    public function history()
    {
        $school = Auth::user()->managedSchool;
        
        if (!$school) {
            return redirect()->route('admin.dashboard')->with('error', 'No school assigned to your account.');
        }

        $requests = QrLimitRequest::where('school_id', $school->id)
            ->with('processedBy')
            ->latest()
            ->paginate(10);

        return view('admin.payment.qr-limit-request.history', compact('school', 'requests'));
    }

    /**
     * Cancel a pending request
     */
    public function cancel($requestId)
    {
        $school = Auth::user()->managedSchool;
        
        if (!$school) {
            return response()->json(['error' => 'No school assigned to your account.'], 400);
        }

        $qrLimitRequest = QrLimitRequest::where('id', $requestId)
            ->where('school_id', $school->id)
            ->where('admin_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$qrLimitRequest) {
            return response()->json(['error' => 'Request not found or cannot be cancelled.'], 404);
        }

        $qrLimitRequest->update([
            'status' => 'rejected',
            'admin_notes' => 'Cancelled by admin',
            'processed_by' => Auth::id(),
            'processed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Request cancelled successfully.'
        ]);
    }

    /**
     * Get current QR code status
     */
    public function status()
    {
        $school = Auth::user()->managedSchool;
        
        if (!$school) {
            return response()->json(['error' => 'No school assigned to your account.'], 400);
        }

        return response()->json([
            'current_limit' => $school->qr_code_limit,
            'generated_count' => $school->qr_codes_generated,
            'remaining' => $school->getRemainingQrCodes(),
            'can_generate' => $school->canGenerateQrCode(),
            'needs_payment' => $school->needsPaymentForQrCodes(),
            'payment_status' => $school->qr_limit_paid ? 'paid' : 'unpaid'
        ]);
    }
}