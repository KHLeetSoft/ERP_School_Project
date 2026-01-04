<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Academic\StoreSubjectRequest;
use App\Http\Requests\Admin\Academic\UpdateSubjectRequest;
use App\Models\AcademicSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class SubjectController extends Controller
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
            
            $adminSchoolId = auth()->user()->school_id ?? null;
            $query = AcademicSubject::query()->forSchool($adminSchoolId);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" class="row-select" value="' . e($row->id) . '">';
                })
                ->editColumn('type', function ($row) {
                    return ucfirst($row->type);
                })
                ->editColumn('status', function ($row) {
                    return $row->status ? 'Active' : 'Inactive';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.academic.subjects.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.academic.subjects.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-subject-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['select', 'action'])
                ->make(true);
        }

        return view('admin.academic.subjects.index');
    }

    public function serverSideDataTable(Request $request)
    {
        if ($request->ajax()) {
            $adminSchoolId = auth()->user()->school_id ?? null;
            $query = AcademicSubject::query()->forSchool($adminSchoolId);

            if ($request->filled('q')) {
                $query->search($request->string('q'));
            }
            if ($request->filled('status')) {
                $query->where('status', (bool) $request->input('status'));
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-check" value="' . $row->id . '" />';
                })
                ->editColumn('type', function ($row) {
                    return ucfirst($row->type);
                })
                ->editColumn('status', function ($row) {
                    $btnClass = $row->status ? 'btn-success' : 'btn-outline-secondary';
                    $label = $row->status ? 'Active' : 'Inactive';
                    $action = route('admin.academic.subjects.toggle-status', $row->id);
                    return '<form method="POST" action="' . $action . '">' . csrf_field() . '<button class="btn btn-sm ' . $btnClass . '">' . $label . '</button></form>';
                })
                ->addColumn('actions', function ($row) {
                    $edit = route('admin.academic.subjects.edit', $row->id);
                    $destroy = route('admin.academic.subjects.destroy', $row->id);
                    $show = route('admin.academic.subjects.show', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Actions">
                        <a href="' . $show . '" class="btn btn-sm btn-outline-info" title="View"><i class="bx bx-show"></i></a>
                        <a href="' . $edit . '" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bx bx-edit-alt"></i></a>
                        <form method="POST" action="' . $destroy . '" onsubmit="return confirm(\'Delete this subject?\')" style="display:inline-block">'
                        . csrf_field() . method_field('DELETE') .
                        '<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bx bx-trash"></i></button></form>
                    </div>';
                })
                ->rawColumns(['checkbox', 'status', 'actions'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('admin.academic.subjects.create');
    }

    public function store(StoreSubjectRequest $request)
    {
        $data = $request->validated();
        $data['school_id'] = auth()->user()->school_id ?? null;
        $data['status'] = (bool) ($data['status'] ?? true);
        AcademicSubject::create($data);

        return redirect()->route('admin.academic.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(AcademicSubject $subject)
    {
        return view('admin.academic.subjects.show', compact('subject'));
    }

    public function edit(AcademicSubject $subject)
    {
        return view('admin.academic.subjects.edit', compact('subject'));
    }

    public function update(UpdateSubjectRequest $request, AcademicSubject $subject)
    {
        $data = $request->validated();
        if (array_key_exists('status', $data)) {
            $data['status'] = (bool) $data['status'];
        }
        $subject->update($data);
        return redirect()->route('admin.academic.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(AcademicSubject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.academic.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $fileName = 'subjects_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['name', 'code', 'type', 'credit_hours', 'description', 'status']);

            $adminSchoolId = auth()->user()->school_id ?? null;
            AcademicSubject::query()
                ->forSchool($adminSchoolId)
                ->search($request->get('q'))
                ->orderBy('name')
                ->chunk(200, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->name,
                            $row->code,
                            $row->type,
                            $row->credit_hours,
                            $row->description,
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
        $expected = ['name', 'code', 'type', 'credit_hours', 'description', 'status'];
        if (!$header || array_map('strtolower', $header) !== $expected) {
            fclose($handle);
            return back()->withErrors(['file' => 'Invalid CSV header. Expected: ' . implode(',', $expected)]);
        }

        DB::beginTransaction();
        try {
            $rowNumber = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                if (count($row) < 6) {
                    continue;
                }
                [$name, $code, $type, $credit, $description, $status] = $row;
                if (!in_array($type, ['theory', 'practical', 'lab'])) {
                    $type = 'theory';
                }
                $credit = is_numeric($credit) ? (int) $credit : null;
                $status = (int) $status === 1;

                AcademicSubject::updateOrCreate(
                    ['school_id' => auth()->user()->school_id ?? null, 'code' => trim($code)],
                    [
                        'name' => trim($name),
                        'type' => $type,
                        'credit_hours' => $credit,
                        'description' => $description,
                        'status' => $status,
                    ]
                );
            }
            fclose($handle);
            DB::commit();
        } catch (\Throwable $e) {
            if (is_resource($handle)) {
                fclose($handle);
            }
            DB::rollBack();
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Subjects imported successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $schoolId = auth()->user()->school_id ?? null;
        AcademicSubject::whereIn('id', $ids)->forSchool($schoolId)->delete();
        return back()->with('success', 'Selected subjects deleted.');
    }

    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = (bool) $request->input('status', true);
        $schoolId = auth()->user()->school_id ?? null;
        AcademicSubject::whereIn('id', $ids)->forSchool($schoolId)->update(['status' => $status]);
        return back()->with('success', 'Status updated for selected subjects.');
    }

    public function toggleStatus(AcademicSubject $subject)
    {
        $subject->update(['status' => !$subject->status]);
        return back()->with('success', 'Subject status updated.');
    }
}


