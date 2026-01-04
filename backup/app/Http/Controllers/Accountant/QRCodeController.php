<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\QRCode;
use App\Models\Student;
use App\Models\Fee;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QRCodeGenerator;
use Yajra\DataTables\Facades\DataTables;

class QRCodeController extends Controller
{
    public function index()
    {
        return view('accountant.qr-codes.index');
    }

    public function datatable(Request $request)
    {
        $query = QRCode::where('created_by', auth()->id())
                      ->orderByDesc('created_at');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('qr_image', function ($qr) {
                if ($qr->qr_image_path) {
                    return '<img src="' . $qr->qr_image_url . '" alt="QR Code" style="width: 50px; height: 50px;">';
                }
                return '<span class="text-muted">Not Generated</span>';
            })
            ->addColumn('type_badge', function ($qr) {
                $badges = [
                    'payment' => 'success',
                    'student' => 'primary',
                    'fee' => 'warning',
                    'general' => 'secondary',
                    'link' => 'info',
                    'document' => 'dark'
                ];
                $badge = $badges[$qr->type] ?? 'secondary';
                return '<span class="badge bg-' . $badge . '">' . ucfirst($qr->type) . '</span>';
            })
            ->addColumn('status', function ($qr) {
                if (!$qr->is_active) {
                    return '<span class="badge bg-danger">Inactive</span>';
                }
                if ($qr->isExpired()) {
                    return '<span class="badge bg-warning">Expired</span>';
                }
                return '<span class="badge bg-success">Active</span>';
            })
            ->addColumn('actions', function ($qr) {
                $actions = '<div class="btn-group">';
                $actions .= '<a href="' . route('accountant.qr-codes.show', $qr->id) . '" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>';
                $actions .= '<a href="' . route('accountant.qr-codes.download', $qr->id) . '" class="btn btn-sm btn-outline-success" title="Download"><i class="fas fa-download"></i></a>';
                $actions .= '<a href="' . route('accountant.qr-codes.edit', $qr->id) . '" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>';
                $actions .= '<button class="btn btn-sm btn-outline-danger" onclick="deleteQRCode(' . $qr->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['qr_image', 'type_badge', 'status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $students = Student::where('status', 'active')->get(['id', 'first_name', 'last_name', 'admission_no']);
        return view('accountant.qr-codes.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:payment,student,fee,general,link,document',
            'data' => 'nullable|array',
            'expires_at' => 'nullable|date|after:now',
            'student_id' => 'nullable|exists:students,id',
            'fee_id' => 'nullable|exists:students_fees,id',
            'url' => 'nullable|url',
        ]);

        $qrData = [];
        $url = null;

        // Generate data based on type
        switch ($request->type) {
            case 'student':
                if ($request->student_id) {
                    $student = Student::find($request->student_id);
                    $qrData = [
                        'student_id' => $student->id,
                        'student_name' => $student->name,
                        'admission_number' => $student->admission_number,
                        'class' => $student->class_name ?? 'N/A',
                        'section' => $student->class_section_id ?? 'N/A',
                    ];
                    $url = route('accountant.students.show', $student->id);
                }
                break;

            case 'payment':
                $qrData = [
                    'payment_type' => 'fee_payment',
                    'amount' => $request->amount ?? 0,
                    'payment_method' => $request->payment_method ?? 'any',
                    'description' => $request->description ?? 'Fee Payment',
                ];
                $url = route('accountant.payments.create');
                break;

            case 'fee':
                if ($request->fee_id) {
                    $fee = Fee::find($request->fee_id);
                    $qrData = [
                        'fee_id' => $fee->id,
                        'student_id' => $fee->student_id,
                        'amount' => $fee->amount,
                        'due_date' => $fee->fee_date,
                        'description' => $fee->remarks,
                    ];
                    $url = route('accountant.fees.show', $fee->id);
                }
                break;

            case 'link':
                $url = $request->url;
                $qrData = ['url' => $url];
                break;

            case 'document':
                $qrData = [
                    'document_type' => $request->document_type ?? 'general',
                    'title' => $request->title,
                    'description' => $request->description,
                ];
                break;

            default:
                $qrData = $request->data ?? [];
        }

        $qrCode = QRCode::create([
            'code' => QRCode::generateUniqueCode(),
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'data' => $qrData,
            'url' => $url,
            'expires_at' => $request->expires_at,
            'created_by' => auth()->id(),
            'school_id' => auth()->user()->school_id ?? null,
        ]);

        // Generate QR code image
        $qrCode->generateQRImage();

        return redirect()->route('accountant.qr-codes.index')
                        ->with('success', 'QR Code generated successfully!');
    }

    public function show(QRCode $qrCode)
    {
        $this->authorize('view', $qrCode);
        return view('accountant.qr-codes.show', compact('qrCode'));
    }

    public function edit(QRCode $qrCode)
    {
        $this->authorize('update', $qrCode);
        $students = Student::where('status', 'active')->get(['id', 'first_name', 'last_name', 'admission_no']);
        return view('accountant.qr-codes.edit', compact('qrCode', 'students'));
    }

    public function update(Request $request, QRCode $qrCode)
    {
        $this->authorize('update', $qrCode);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $qrCode->update($request->only(['title', 'description', 'is_active', 'expires_at']));

        return redirect()->route('accountant.qr-codes.index')
                        ->with('success', 'QR Code updated successfully!');
    }

    public function destroy(QRCode $qrCode)
    {
        $this->authorize('delete', $qrCode);

        // Delete QR code image
        if ($qrCode->qr_image_path) {
            Storage::delete($qrCode->qr_image_path);
        }

        $qrCode->delete();

        return response()->json(['success' => 'QR Code deleted successfully!']);
    }

    public function download(QRCode $qrCode)
    {
        $this->authorize('view', $qrCode);

        if (!$qrCode->qr_image_path || !Storage::exists($qrCode->qr_image_path)) {
            $qrCode->generateQRImage();
        }

        return Storage::download($qrCode->qr_image_path, 'qr_code_' . $qrCode->code . '.png');
    }

    public function access($code)
    {
        $qrCode = QRCode::where('code', $code)->active()->first();

        if (!$qrCode) {
            return view('accountant.qr-codes.access', [
                'error' => 'QR Code not found or expired',
                'qrCode' => null
            ]);
        }

        // Increment scan count
        $qrCode->incrementScanCount();

        return view('accountant.qr-codes.access', compact('qrCode'));
    }

    public function processAccess(Request $request, $code)
    {
        $qrCode = QRCode::where('code', $code)->active()->first();

        if (!$qrCode) {
            return response()->json(['error' => 'QR Code not found or expired'], 404);
        }

        // Process based on QR code type
        switch ($qrCode->type) {
            case 'payment':
                return $this->processPayment($request, $qrCode);
            case 'student':
                return redirect()->route('accountant.students.show', $qrCode->data['student_id']);
            case 'fee':
                return redirect()->route('accountant.fees.show', $qrCode->data['fee_id']);
            case 'link':
                return redirect($qrCode->url);
            default:
                return view('accountant.qr-codes.access', compact('qrCode'));
        }
    }

    private function processPayment(Request $request, QRCode $qrCode)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
        ]);

        // Create payment record
        $payment = Payment::create([
            'student_id' => $qrCode->data['student_id'] ?? null,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'notes' => 'Payment via QR Code: ' . $qrCode->title,
            'status' => 'completed',
            'processed_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => 'Payment processed successfully!',
            'payment_id' => $payment->id
        ]);
    }

    public function generateBulk(Request $request)
    {
        $request->validate([
            'type' => 'required|in:student,payment,fee',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'title_prefix' => 'required|string|max:100',
        ]);

        $generated = 0;
        $errors = [];

        foreach ($request->student_ids as $studentId) {
            try {
                $student = Student::find($studentId);
                
                $qrCode = QRCode::create([
                    'code' => QRCode::generateUniqueCode(),
                    'title' => $request->title_prefix . ' - ' . $student->name,
                    'type' => $request->type,
                    'data' => [
                        'student_id' => $student->id,
                        'student_name' => $student->name,
                        'admission_number' => $student->admission_number,
                    ],
                    'url' => route('accountant.students.show', $student->id),
                    'created_by' => auth()->id(),
                    'school_id' => auth()->user()->school_id ?? null,
                ]);

                $qrCode->generateQRImage();
                $generated++;
            } catch (\Exception $e) {
                $errors[] = "Failed to generate QR code for student {$student->name}: " . $e->getMessage();
            }
        }

        $message = "Generated {$generated} QR codes successfully!";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->route('accountant.qr-codes.index')
                        ->with('success', $message);
    }
}