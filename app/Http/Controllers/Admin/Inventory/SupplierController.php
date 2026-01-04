<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    protected $adminUser;
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $this->adminUser = auth()->guard('admin')->user();
            $this->schoolId = $this->adminUser ? $this->adminUser->school_id : null;
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by verification status
        if ($request->filled('verification_status')) {
            if ($request->verification_status === 'verified') {
                $query->verified();
            } elseif ($request->verification_status === 'unverified') {
                $query->unverified();
            }
        }

        $suppliers = $query->orderBy('name')->paginate(20);

        // Statistics
        $stats = [
            'total_suppliers' => Supplier::count(),
            'active_suppliers' => Supplier::active()->count(),
            'verified_suppliers' => Supplier::verified()->count(),
            'total_credit_limit' => Supplier::sum('credit_limit'),
        ];

        return view('admin.inventory.suppliers.index', compact('suppliers', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = Supplier::STATUSES;
        return view('admin.inventory.suppliers.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms_days' => 'nullable|integer|min:0|max:365',
            'status' => 'required|in:' . implode(',', array_keys(Supplier::STATUSES)),
            'notes' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = Str::slug($request->name) . '_logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('suppliers/logos', $logoName, 'public');
            $data['logo'] = $logoPath;
        }

        // Handle document uploads
        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $document) {
                $docName = Str::slug($request->name) . '_doc_' . time() . '_' . $document->getClientOriginalName();
                $docPath = $document->storeAs('suppliers/documents', $docName, 'public');
                $documents[] = $docPath;
            }
            $data['documents'] = $documents;
        }

        Supplier::create($data);

        return redirect()->route('admin.inventory.suppliers.index')
                        ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('inventoryItems');
        return view('admin.inventory.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $statuses = Supplier::STATUSES;
        return view('admin.inventory.suppliers.edit', compact('supplier', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms_days' => 'nullable|integer|min:0|max:365',
            'status' => 'required|in:' . implode(',', array_keys(Supplier::STATUSES)),
            'notes' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($supplier->logo && Storage::disk('public')->exists($supplier->logo)) {
                Storage::disk('public')->delete($supplier->logo);
            }

            $logo = $request->file('logo');
            $logoName = Str::slug($request->name) . '_logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('suppliers/logos', $logoName, 'public');
            $data['logo'] = $logoPath;
        }

        // Handle document uploads
        if ($request->hasFile('documents')) {
            $existingDocs = $supplier->documents ?? [];
            $newDocs = [];
            
            foreach ($request->file('documents') as $document) {
                $docName = Str::slug($request->name) . '_doc_' . time() . '_' . $document->getClientOriginalName();
                $docPath = $document->storeAs('suppliers/documents', $docName, 'public');
                $newDocs[] = $docPath;
            }
            
            $data['documents'] = array_merge($existingDocs, $newDocs);
        }

        $supplier->update($data);

        return redirect()->route('admin.inventory.suppliers.index')
                        ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        // Delete logo if exists
        if ($supplier->logo && Storage::disk('public')->exists($supplier->logo)) {
            Storage::disk('public')->delete($supplier->logo);
        }

        // Delete documents if exist
        if ($supplier->documents) {
            foreach ($supplier->documents as $document) {
                if (Storage::disk('public')->exists($document)) {
                    Storage::disk('public')->delete($document);
                }
            }
        }

        $supplier->delete();

        return redirect()->route('admin.inventory.suppliers.index')
                        ->with('success', 'Supplier deleted successfully.');
    }

    /**
     * Toggle supplier verification status
     */
    public function toggleVerification(Supplier $supplier)
    {
        if ($supplier->is_verified) {
            $supplier->unverify();
            $message = 'Supplier verification removed.';
        } else {
            $supplier->verify($this->adminUser->name ?? 'Admin');
            $message = 'Supplier verified successfully.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_verified' => $supplier->is_verified
        ]);
    }

    /**
     * Toggle supplier status
     */
    public function toggleStatus(Supplier $supplier)
    {
        $newStatus = $supplier->status === 'active' ? 'inactive' : 'active';
        $supplier->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier status updated successfully.',
            'status' => $newStatus
        ]);
    }
}
