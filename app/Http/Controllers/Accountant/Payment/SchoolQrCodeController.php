<?php

namespace App\Http\Controllers\Accountant\Payment;

use App\Http\Controllers\Controller;
use App\Models\SchoolQrCode;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolQrCodeController extends Controller
{
    protected $accountantUser;
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:accountant');
        $this->accountantUser = auth()->guard('accountant')->user();
        $this->schoolId = $this->accountantUser ? $this->accountantUser->school_id : 1;
    }

    /**
     * Display school QR codes for accountant
     */
    public function index()
    {
        $school = School::find($this->schoolId);
        $schoolQrCodes = SchoolQrCode::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        if ($schoolQrCodes->isEmpty()) {
            return view('accountant.payment.school-qr-codes.not-found', compact('school'));
        }

        return view('accountant.payment.school-qr-codes.index', compact('school', 'schoolQrCodes'));
    }

    /**
     * Display school QR code details
     */
    public function show(SchoolQrCode $schoolQrCode)
    {
        // Check if QR code belongs to accountant's school
        if ($schoolQrCode->school_id != $this->schoolId) {
            return redirect()->route('accountant.payment.school-qr-codes.index')
                ->with('error', 'Access denied.');
        }

        $school = School::find($this->schoolId);
        return view('accountant.payment.school-qr-codes.show', compact('schoolQrCode', 'school'));
    }

    /**
     * Download school QR code
     */
    public function download(SchoolQrCode $schoolQrCode)
    {
        // Check if QR code belongs to accountant's school
        if ($schoolQrCode->school_id != $this->schoolId) {
            return redirect()->route('accountant.payment.school-qr-codes.index')
                ->with('error', 'Access denied.');
        }

        if (!$schoolQrCode->qr_code_image) {
            return redirect()->route('accountant.payment.school-qr-codes.index')
                ->with('error', 'School QR code image not found.');
        }

        $filePath = storage_path('app/public/' . $schoolQrCode->qr_code_image);
        
        if (!file_exists($filePath)) {
            return redirect()->route('accountant.payment.school-qr-codes.index')
                ->with('error', 'QR code image file not found.');
        }

        return response()->download($filePath, 'school-qr-code-' . $schoolQrCode->school_id . '-' . Str::slug($schoolQrCode->title) . '.png');
    }

    /**
     * Process QR code scan (increment usage count)
     */
    public function processScan(SchoolQrCode $schoolQrCode)
    {
        // Check if QR code belongs to accountant's school
        if ($schoolQrCode->school_id != $this->schoolId) {
            return response()->json(['success' => false, 'message' => 'Access denied.']);
        }

        if (!$schoolQrCode->is_active) {
            return response()->json(['success' => false, 'message' => 'School QR code is not active.']);
        }

        // Increment usage count
        $schoolQrCode->incrementUsage();

        return response()->json([
            'success' => true,
            'message' => 'QR code scan recorded successfully!',
            'usage_count' => $schoolQrCode->usage_count
        ]);
    }
}