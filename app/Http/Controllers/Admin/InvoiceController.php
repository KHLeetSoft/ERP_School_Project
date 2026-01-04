<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;
use App\Imports\InvoicesImport;

class InvoiceController extends Controller
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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Invoice::where('school_id', auth()->user()->school_id ?? 1)->orderByDesc('issue_date');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('issue_date', fn($r)=> Carbon::parse($r->issue_date)->format('d M Y'))
                ->editColumn('due_date', fn($r)=> $r->due_date ? Carbon::parse($r->due_date)->format('d M Y') : '-')
                ->editColumn('total', fn($r)=> number_format($r->total, 2))
                ->addColumn('actions', function ($r) { return view('admin.finance.invoice.partials.actions', compact('r'))->render(); })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.finance.invoice.index');
    }

    public function create()
    {
        return view('admin.finance.invoice.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|max:100|unique:invoices,invoice_number',
            'bill_to' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'items' => 'nullable',
            'subtotal' => 'required|numeric',
            'tax' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'total' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $items = $request->items;
        if (is_string($items) && trim($items) !== '') {
            $parsed = json_decode($items, true);
            if (json_last_error() === JSON_ERROR_NONE) $items = $parsed; else $items = [];
        }

        Invoice::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'invoice_number' => $request->invoice_number,
            'bill_to' => $request->bill_to,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'items' => $items ?? [],
            'subtotal' => $request->subtotal,
            'tax' => $request->tax ?? 0,
            'discount' => $request->discount ?? 0,
            'total' => $request->total,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.finance.invoice.index')->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice)
    {
        $this->authorizeSchool($invoice);
        return view('admin.finance.invoice.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $this->authorizeSchool($invoice);
        return view('admin.finance.invoice.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->authorizeSchool($invoice);
        $request->validate([
            'bill_to' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'items' => 'nullable',
            'subtotal' => 'required|numeric',
            'tax' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'total' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $items = $request->items;
        if (is_string($items) && trim($items) !== '') {
            $parsed = json_decode($items, true);
            if (json_last_error() === JSON_ERROR_NONE) $items = $parsed; else $items = [];
        }

        $invoice->update([
            'bill_to' => $request->bill_to,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'items' => $items ?? [],
            'subtotal' => $request->subtotal,
            'tax' => $request->tax ?? 0,
            'discount' => $request->discount ?? 0,
            'total' => $request->total,
            'notes' => $request->notes,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.finance.invoice.index')->with('success', 'Invoice updated.');
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorizeSchool($invoice);
        $invoice->delete();
        if (request()->wantsJson()) return response()->json(['success' => true]);
        return redirect()->route('admin.finance.invoice.index')->with('success', 'Invoice deleted.');
    }

    public function export()
    {
        return Excel::download(new InvoicesExport(auth()->user()->school_id ?? 1), 'invoices.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        Excel::import(new InvoicesImport(auth()->user()->school_id ?? 1), $request->file('file'));
        return back()->with('success', 'Invoices imported.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $labels = collect(range(5,0))->map(fn($i)=> Carbon::today()->subMonths($i)->format('M Y'));
        $ymKeys = collect(range(5,0))->map(fn($i)=> Carbon::today()->subMonths($i)->format('Y-m'));

        // Revenue totals per month
        $totals = [];
        foreach ($ymKeys as $ym) {
            $totals[] = (float) Invoice::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(issue_date, "%Y-%m") = ?', [$ym])
                ->sum('total');
        }

        // Counts per status per month (stacked series)
        $allStatuses = ['draft','sent','paid','overdue','cancelled'];
        $statusSeries = [];
        foreach ($allStatuses as $status) {
            $series = [];
            foreach ($ymKeys as $ym) {
                $series[] = (int) Invoice::where('school_id', $schoolId)
                    ->where('status', $status)
                    ->whereRaw('DATE_FORMAT(issue_date, "%Y-%m") = ?', [$ym])
                    ->count();
            }
            $statusSeries[$status] = $series;
        }

        // Average invoice value per month
        $avgSeries = [];
        foreach ($ymKeys as $ym) {
            $sum = (float) Invoice::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(issue_date, "%Y-%m") = ?', [$ym])
                ->sum('total');
            $count = (int) Invoice::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(issue_date, "%Y-%m") = ?', [$ym])
                ->count();
            $avgSeries[] = $count > 0 ? round($sum / max(1, $count), 2) : 0;
        }

        // Paid vs Unpaid revenue per month
        $paidSeries = [];
        $unpaidSeries = [];
        foreach ($ymKeys as $ym) {
            $paid = (float) Invoice::where('school_id', $schoolId)
                ->where('status', 'paid')
                ->whereRaw('DATE_FORMAT(issue_date, "%Y-%m") = ?', [$ym])
                ->sum('total');
            $total = (float) Invoice::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(issue_date, "%Y-%m") = ?', [$ym])
                ->sum('total');
            $paidSeries[] = $paid;
            $unpaidSeries[] = max(0, $total - $paid);
        }

        // Cumulative revenue
        $cumulativeSeries = [];
        $running = 0.0;
        foreach ($totals as $t) {
            $running += (float)$t;
            $cumulativeSeries[] = round($running, 2);
        }

        // Tax vs Discount per month
        $taxSeries = [];
        $discountSeries = [];
        foreach ($ymKeys as $ym) {
            $taxSeries[] = (float) Invoice::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(issue_date, "%Y-%m") = ?', [$ym])
                ->sum('tax');
            $discountSeries[] = (float) Invoice::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(issue_date, "%Y-%m") = ?', [$ym])
                ->sum('discount');
        }

        // Overall status distribution
        $statusCounts = Invoice::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')->pluck('cnt','status');

        // Top customers by revenue
        $top = Invoice::where('school_id', $schoolId)
            ->selectRaw('bill_to, SUM(total) as amount')
            ->groupBy('bill_to')
            ->orderByDesc('amount')
            ->limit(5)
            ->get();
        $topCustomerLabels = $top->pluck('bill_to');
        $topCustomerValues = $top->pluck('amount')->map(fn($v)=> (float)$v);

        return view('admin.finance.invoice.dashboard', compact(
            'labels','totals','statusCounts','statusSeries','avgSeries','topCustomerLabels','topCustomerValues',
            'paidSeries','unpaidSeries','cumulativeSeries','taxSeries','discountSeries'
        ));
    }

    private function authorizeSchool(Invoice $invoice): void
    {
        if (($invoice->school_id ?? null) !== (auth()->user()->school_id ?? 1)) abort(403);
    }
}


