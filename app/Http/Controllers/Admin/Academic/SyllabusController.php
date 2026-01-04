<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\AcademicSyllabus;
use App\Models\AcademicSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class SyllabusController extends Controller
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
        $adminSchoolId = auth()->user()->school_id ?? null;
        $query = AcademicSyllabus::query()->with('subject')->forSchool($adminSchoolId);

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('select', fn($row) => '<input type="checkbox" class="row-select" value="' . e($row->id) . '">')
                ->addColumn('subject', fn($row) => e(optional($row->subject)->name))
                ->addColumn('progress', fn($row) => $row->progress_percent . '%')
                ->editColumn('status', fn($row) => $row->status ? 'Active' : 'Inactive')
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.academic.syllabus.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.academic.syllabus.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-syllabus-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['select', 'action'])
                ->make(true);
        }

        return view('admin.academic.syllabus.index');
    }

    public function create()
    {
        $subjects = AcademicSubject::query()->forSchool(auth()->user()->school_id)->orderBy('name')->get();
        return view('admin.academic.syllabus.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:academic_subjects,id'],
            'term' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'total_units' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'completed_units' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'boolean'],
        ]);
        $validated['school_id'] = auth()->user()->school_id;
        $validated['status'] = (bool) ($validated['status'] ?? true);
        AcademicSyllabus::create($validated);
        return redirect()->route('admin.academic.syllabus.index')->with('success', 'Syllabus created successfully');
    }

    public function show(AcademicSyllabus $syllabus)
    {
        $syllabus->load('subject');
        return view('admin.academic.syllabus.show', compact('syllabus'));
    }

    public function edit(AcademicSyllabus $syllabus)
    {
        $subjects = AcademicSubject::query()->forSchool(auth()->user()->school_id)->orderBy('name')->get();
        return view('admin.academic.syllabus.edit', compact('syllabus', 'subjects'));
    }

    public function update(Request $request, AcademicSyllabus $syllabus)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:academic_subjects,id'],
            'term' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'total_units' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'completed_units' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'boolean'],
        ]);
        $syllabus->update($validated);
        return redirect()->route('admin.academic.syllabus.index')->with('success', 'Syllabus updated successfully');
    }

    public function destroy(AcademicSyllabus $syllabus)
    {
        $syllabus->delete();
        return redirect()->route('admin.academic.syllabus.index')->with('success', 'Syllabus deleted successfully');
    }

    public function export(Request $request): StreamedResponse
    {
        $fileName = 'syllabus_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $adminSchoolId = auth()->user()->school_id ?? null;
        $query = AcademicSyllabus::query()->forSchool($adminSchoolId)->with('subject')->orderBy('title');

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['subject_code', 'term', 'title', 'description', 'total_units', 'completed_units', 'start_date', 'end_date', 'status']);
            $query->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        optional($row->subject)->code,
                        $row->term,
                        $row->title,
                        $row->description,
                        $row->total_units,
                        $row->completed_units,
                        optional($row->start_date)->format('Y-m-d'),
                        optional($row->end_date)->format('Y-m-d'),
                        $row->status ? 1 : 0,
                    ]);
                }
            });
            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);
        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return back()->withErrors(['file' => 'Unable to read the file.']);
        }

        $header = fgetcsv($handle);
        $expected = ['subject_code', 'term', 'title', 'description', 'total_units', 'completed_units', 'start_date', 'end_date', 'status'];
        if (!$header || array_map('strtolower', $header) !== $expected) {
            fclose($handle);
            return back()->withErrors(['file' => 'Invalid CSV header. Expected: ' . implode(',', $expected)]);
        }

        DB::beginTransaction();
        try {
            $schoolId = auth()->user()->school_id ?? null;
            $subjectsByCode = AcademicSubject::forSchool($schoolId)->pluck('id', 'code');
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 9) { continue; }
                [$subjectCode, $term, $title, $description, $total, $completed, $start, $end, $status] = $row;
                $subjectId = $subjectsByCode[trim($subjectCode)] ?? null;
                if (!$subjectId) { continue; }

                AcademicSyllabus::updateOrCreate(
                    [ 'school_id' => $schoolId, 'subject_id' => $subjectId, 'title' => trim($title) ],
                    [
                        'term' => trim($term) ?: null,
                        'description' => $description,
                        'total_units' => is_numeric($total) ? (int) $total : null,
                        'completed_units' => is_numeric($completed) ? (int) $completed : 0,
                        'start_date' => $start ? date('Y-m-d', strtotime($start)) : null,
                        'end_date' => $end ? date('Y-m-d', strtotime($end)) : null,
                        'status' => (int) $status === 1,
                    ]
                );
            }
            fclose($handle);
            DB::commit();
        } catch (\Throwable $e) {
            if (is_resource($handle)) fclose($handle);
            DB::rollBack();
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Syllabus imported successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $schoolId = auth()->user()->school_id ?? null;
        AcademicSyllabus::whereIn('id', $ids)->forSchool($schoolId)->delete();
        return back()->with('success', 'Selected syllabus entries deleted.');
    }

    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = (bool) $request->input('status', true);
        $schoolId = auth()->user()->school_id ?? null;
        AcademicSyllabus::whereIn('id', $ids)->forSchool($schoolId)->update(['status' => $status]);
        return back()->with('success', 'Status updated for selected entries.');
    }

    public function toggleStatus(AcademicSyllabus $syllabus)
    {
        $syllabus->update(['status' => !$syllabus->status]);
        return back()->with('success', 'Syllabus status updated.');
    }
}


