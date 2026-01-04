<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeeHeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('checkrole:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feeHeads = FeeHead::orderBy('sort_order')->paginate(20);
        return view('admin.fees.fee-heads.index', compact('feeHeads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fees.fee-heads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fee_heads,code',
            'description' => 'nullable|string',
            'type' => 'required|in:mandatory,optional',
            'frequency' => 'required|in:monthly,quarterly,yearly,one_time',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        FeeHead::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'frequency' => $request->frequency,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('admin.fee-heads.index')
            ->with('success', 'Fee head created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeHead $feeHead)
    {
        $feeHead->load('feeStructures');
        return view('admin.fees.fee-heads.show', compact('feeHead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeHead $feeHead)
    {
        return view('admin.fees.fee-heads.edit', compact('feeHead'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeHead $feeHead)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fee_heads,code,' . $feeHead->id,
            'description' => 'nullable|string',
            'type' => 'required|in:mandatory,optional',
            'frequency' => 'required|in:monthly,quarterly,yearly,one_time',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $feeHead->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'frequency' => $request->frequency,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.fee-heads.index')
            ->with('success', 'Fee head updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeHead $feeHead)
    {
        // Check if fee head is being used in fee structures
        if ($feeHead->feeStructures()->count() > 0) {
            return redirect()->route('admin.fee-heads.index')
                ->with('error', 'Cannot delete fee head. It is being used in fee structures.');
        }

        $feeHead->delete();

        return redirect()->route('admin.fee-heads.index')
            ->with('success', 'Fee head deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(FeeHead $feeHead)
    {
        $feeHead->update(['is_active' => !$feeHead->is_active]);
        
        $status = $feeHead->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.fee-heads.index')
            ->with('success', "Fee head {$status} successfully.");
    }
}
