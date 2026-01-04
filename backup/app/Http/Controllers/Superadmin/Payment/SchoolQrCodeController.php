<?php

namespace App\Http\Controllers\Superadmin\Payment;

use App\Http\Controllers\Controller;
use App\Models\SchoolQrCode;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SchoolQrCodeController extends Controller
{
    protected $superAdminUser;

    public function __construct()
    {
        $this->middleware('auth');
        $this->superAdminUser = auth()->user();
    }

    /**
     * Display a listing of school QR codes
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Handle statistics request
            if ($request->has('stats_only') && $request->stats_only) {
                $query = SchoolQrCode::query();
                
                // Apply filters
                if ($request->has('school_id') && $request->school_id) {
                    $query->where('school_id', $request->school_id);
                }
                if ($request->has('status') && $request->status !== '') {
                    $query->where('is_active', $request->status);
                }
                
                return response()->json([
                    'total' => $query->count(),
                    'active' => $query->where('is_active', true)->count(),
                    'inactive' => $query->where('is_active', false)->count(),
                    'schools' => $query->distinct('school_id')->count('school_id')
                ]);
            }

            $query = SchoolQrCode::with(['school', 'createdBy', 'updatedBy'])
                ->orderBy('created_at', 'desc');

            // Filter by school if provided
            if ($request->has('school_id') && $request->school_id) {
                $query->where('school_id', $request->school_id);
            }

            // Filter by status if provided
            if ($request->has('status') && $request->status !== '') {
                $query->where('is_active', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('school_info', function ($data) {
                    return '<div class="d-flex align-items-center">
                                <div class="me-3">
                                    <h6 class="mb-0">' . e($data->school->name) . '</h6>
                                    <small class="text-muted">' . e($data->school->address) . '</small>
                                </div>
                            </div>';
                })
                ->addColumn('qr_info', function ($data) {
                    $statusBadge = $data->is_active ? 
                        '<span class="badge badge-pill badge-light-success">Active</span>' : 
                        '<span class="badge badge-pill badge-light-danger">Inactive</span>';
                    
                    return '<div class="d-flex align-items-center">
                                <div class="me-3">
                                    <h6 class="mb-0">' . e($data->title) . '</h6>
                                    <small class="text-muted">' . e($data->description) . '</small>
                                </div>
                                <div class="ms-auto">
                                    ' . $statusBadge . '
                                </div>
                            </div>';
                })
                ->addColumn('qr_code', function ($data) {
                    if ($data->qr_code_image) {
                        return '<img src="' . asset('storage/' . $data->qr_code_image) . '" alt="QR Code" style="width: 50px; height: 50px;" class="img-thumbnail">';
                    }
                    return '<span class="text-muted">No QR Code</span>';
                })
                ->addColumn('amount_info', function ($data) {
                    if ($data->amount) {
                        return '<strong>₹' . number_format($data->amount, 2) . '</strong><br><small class="text-muted">Fixed Amount</small>';
                    }
                    return '<span class="text-muted">Variable Amount</span>';
                })
                ->addColumn('usage_stats', function ($data) {
                    return '<div class="text-center">
                                <h6 class="mb-0">' . $data->usage_count . '</h6>
                                <small class="text-muted">Scans</small>
                            </div>';
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->format('M d, Y');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="d-flex justify-content-center">';

                    $buttons .= '<a href="' . route('superadmin.payment.school-qr-codes.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    $buttons .= '<a href="' . route('superadmin.payment.school-qr-codes.edit', $data->id) . '" class="text-primary me-2" title="Edit">
                                    <i class="bx bxs-edit"></i>
                                </a>';

                    $buttons .= '<a href="' . route('superadmin.payment.school-qr-codes.download', $data->id) . '" class="text-success me-2" title="Download QR Code">
                                    <i class="bx bx-download"></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-warning toggle-status-btn me-2" title="Toggle Status">
                                    <i class="bx bx-' . ($data->is_active ? 'pause' : 'play') . '"></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-danger delete-qr-btn" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </a>';

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['school_info', 'qr_info', 'qr_code', 'amount_info', 'usage_stats', 'created_at', 'action'])
                ->make(true);
        }

        $schools = School::select('id', 'name')->get();
        return view('superadmin.payment.school-qr-codes.index', compact('schools'));
    }

    /**
     * Show the form for creating a new school QR code
     */
    public function create()
    {
        $schools = School::select('id', 'name')->get();
        return view('superadmin.payment.school-qr-codes.create', compact('schools'));
    }

    /**
     * Store a newly created school QR code
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'upi_id' => 'required|string|max:255',
            'merchant_name' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        // Check if title already exists for this school
        $existingQrCode = SchoolQrCode::where('school_id', $request->school_id)
            ->where('title', $request->title)
            ->first();

        if ($existingQrCode) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A QR code with this title already exists for this school.');
        }

        // Generate QR code data
        $qrData = $this->generateUPIString($request->upi_id, $request->amount, $request->merchant_name);
        
        // Generate QR code image
        $qrCodeImage = $this->generateQrCodeImage($qrData, $request->title, $request->school_id);

        $schoolQrCode = SchoolQrCode::create([
            'school_id' => $request->school_id,
            'qr_type' => 'school_payment',
            'title' => $request->title,
            'description' => $request->description,
            'upi_id' => $request->upi_id,
            'merchant_name' => $request->merchant_name,
            'amount' => $request->amount,
            'qr_code_data' => $qrData,
            'qr_code_image' => $qrCodeImage,
            'is_active' => $request->has('is_active'),
            'created_by' => $this->superAdminUser->id
        ]);

        return redirect()->route('superadmin.payment.school-qr-codes.index')
            ->with('success', 'School QR code created successfully!');
    }

    /**
     * Display the specified school QR code
     */
    public function show(SchoolQrCode $schoolQrCode)
    {
        $school = $schoolQrCode->school;
        return view('superadmin.payment.school-qr-codes.show', compact('schoolQrCode', 'school'));
    }

    /**
     * Show the form for editing the school QR code
     */
    public function edit(SchoolQrCode $schoolQrCode)
    {
        $schools = School::select('id', 'name')->get();
        return view('superadmin.payment.school-qr-codes.edit', compact('schoolQrCode', 'schools'));
    }

    /**
     * Update the school QR code
     */
    public function update(Request $request, SchoolQrCode $schoolQrCode)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'upi_id' => 'required|string|max:255',
            'merchant_name' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        // Check if title already exists for this school (excluding current QR code)
        $existingQrCode = SchoolQrCode::where('school_id', $request->school_id)
            ->where('title', $request->title)
            ->where('id', '!=', $schoolQrCode->id)
            ->first();

        if ($existingQrCode) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A QR code with this title already exists for this school.');
        }

        // Regenerate QR code if UPI details changed
        if ($schoolQrCode->upi_id !== $request->upi_id || 
            $schoolQrCode->merchant_name !== $request->merchant_name || 
            $schoolQrCode->amount != $request->amount) {
            
            $qrData = $this->generateUPIString($request->upi_id, $request->amount, $request->merchant_name);
            $qrCodeImage = $this->generateQrCodeImage($qrData, $request->title, $request->school_id);
            
            // Delete old QR code image
            if ($schoolQrCode->qr_code_image && Storage::exists('public/' . $schoolQrCode->qr_code_image)) {
                Storage::delete('public/' . $schoolQrCode->qr_code_image);
            }
        } else {
            $qrData = $schoolQrCode->qr_code_data;
            $qrCodeImage = $schoolQrCode->qr_code_image;
        }

        $schoolQrCode->update([
            'school_id' => $request->school_id,
            'title' => $request->title,
            'description' => $request->description,
            'upi_id' => $request->upi_id,
            'merchant_name' => $request->merchant_name,
            'amount' => $request->amount,
            'qr_code_data' => $qrData,
            'qr_code_image' => $qrCodeImage,
            'is_active' => $request->has('is_active'),
            'updated_by' => $this->superAdminUser->id
        ]);

        return redirect()->route('superadmin.payment.school-qr-codes.index')
            ->with('success', 'School QR code updated successfully!');
    }

    /**
     * Toggle school QR code status
     */
    public function toggleStatus(SchoolQrCode $schoolQrCode)
    {
        $schoolQrCode->update([
            'is_active' => !$schoolQrCode->is_active,
            'updated_by' => $this->superAdminUser->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'School QR code status updated successfully!',
            'is_active' => $schoolQrCode->is_active
        ]);
    }

    /**
     * Download school QR code
     */
    public function download(SchoolQrCode $schoolQrCode)
    {
        if (!$schoolQrCode->qr_code_image) {
            return redirect()->route('superadmin.payment.school-qr-codes.index')
                ->with('error', 'School QR code image not found.');
        }

        $filePath = storage_path('app/public/' . $schoolQrCode->qr_code_image);
        
        if (!file_exists($filePath)) {
            return redirect()->route('superadmin.payment.school-qr-codes.index')
                ->with('error', 'QR code image file not found.');
        }

        return response()->download($filePath, 'school-qr-code-' . $schoolQrCode->school_id . '-' . Str::slug($schoolQrCode->title) . '.png');
    }

    /**
     * Delete school QR code
     */
    public function destroy(SchoolQrCode $schoolQrCode)
    {
        // Delete QR code image
        if ($schoolQrCode->qr_code_image && Storage::exists('public/' . $schoolQrCode->qr_code_image)) {
            Storage::delete('public/' . $schoolQrCode->qr_code_image);
        }

        $schoolQrCode->delete();

        return response()->json([
            'success' => true,
            'message' => 'School QR code deleted successfully!'
        ]);
    }

    /**
     * Generate UPI string
     */
    private function generateUPIString($upiId, $amount, $merchantName)
    {
        $upiString = 'upi://pay?pa=' . urlencode($upiId);
        $upiString .= '&pn=' . urlencode($merchantName);
        if ($amount) {
            $upiString .= '&am=' . $amount;
        }
        $upiString .= '&cu=INR';
        return $upiString;
    }

    /**
     * Generate QR code image
     */
    private function generateQrCodeImage($qrData, $title, $schoolId)
    {
        $filename = 'school_qr_' . $schoolId . '_' . Str::slug($title) . '_' . time() . '.png';
        $path = 'qr-codes/school/' . $filename;
        
        // Generate QR code
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($qrData);
        
        // Store the QR code image
        Storage::put('public/' . $path, $qrCode);
        
        return $path;
    }
}