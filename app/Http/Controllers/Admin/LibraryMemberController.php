<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LibraryMember;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LibraryMembersExport;
use App\Imports\LibraryMembersImport;
use Carbon\Carbon;

class LibraryMemberController extends Controller
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
			$adminSchoolId = auth()->user()->school_id;
			$query = LibraryMember::query()
				->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
				->when($request->filled('status'), fn($q) => $q->where('status', $request->input('status')))
				->when($request->filled('member_type'), fn($q) => $q->where('member_type', $request->input('member_type')))
				->when($request->filled('name'), function ($q) use ($request) {
					$name = trim($request->input('name'));
					$q->where('name', 'like', "%$name%");
				});

			return DataTables::of($query)
				->addIndexColumn()
				->addColumn('select', fn($m) => '<input type="checkbox" class="row-select" value="' . e($m->id) . '">')
				->addColumn('action', function ($m) {
					$buttons = '<div class="d-flex align-items-center gap-2">';
					$buttons .= '<a href="' . route('admin.library.members.show', $m->id) . '" class="text-info" title="View"><i class="bx bx-show"></i></a>';
					$buttons .= '<a href="' . route('admin.library.members.edit', $m->id) . '" class="text-primary" title="Edit"><i class="bx bxs-edit"></i></a>';
					$buttons .= '<form method="POST" action="' . route('admin.library.members.toggle-status', $m->id) . '" style="display:inline">' . csrf_field() . '<button class="btn btn-link p-0 ' . ($m->status === 'active' ? 'text-success' : 'text-secondary') . '" title="Toggle Status"><i class="bx bx-toggle-' . ($m->status === 'active' ? 'right' : 'left') . '"></i></button></form>';
					$buttons .= '<a href="javascript:void(0);" data-id="' . $m->id . '" class="text-danger delete-member-btn" title="Delete"><i class="bx bx-trash"></i></a>';
					$buttons .= '</div>';
					return $buttons;
				})
				->rawColumns(['select', 'action'])
				->make(true);
		}
		return view('admin.library.members.index');
	}

	public function create()
	{
		return view('admin.library.members.create');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'membership_no' => 'nullable|string|max:255|unique:library_members,membership_no',
			'name' => 'required|string|max:255',
			'email' => 'nullable|email',
			'phone' => 'nullable|string|max:50',
			'address' => 'nullable|string',
			'member_type' => 'required|in:student,teacher,staff,external',
			'joined_at' => 'required|date',
			'expiry_at' => 'nullable|date|after_or_equal:joined_at',
			'status' => 'required|in:active,inactive,expired',
			'notes' => 'nullable|string',
		]);
		$validated['membership_no'] = $validated['membership_no'] ?: strtoupper(Str::random(8));
		$validated['school_id'] = auth()->user()->school_id;
		LibraryMember::create($validated);
		return redirect()->route('admin.library.members.index')->with('success', 'Member created');
	}

	public function show(LibraryMember $member)
	{
		return view('admin.library.members.show', compact('member'));
	}

	public function edit(LibraryMember $member)
	{
		return view('admin.library.members.edit', compact('member'));
	}

	public function update(Request $request, LibraryMember $member)
	{
		$validated = $request->validate([
			'membership_no' => 'nullable|string|max:255|unique:library_members,membership_no,' . $member->id,
			'name' => 'required|string|max:255',
			'email' => 'nullable|email',
			'phone' => 'nullable|string|max:50',
			'address' => 'nullable|string',
			'member_type' => 'required|in:student,teacher,staff,external',
			'joined_at' => 'required|date',
			'expiry_at' => 'nullable|date|after_or_equal:joined_at',
			'status' => 'required|in:active,inactive,expired',
			'notes' => 'nullable|string',
		]);
		$validated['membership_no'] = $validated['membership_no'] ?: $member->membership_no;
		$member->update($validated);
		return redirect()->route('admin.library.members.index')->with('success', 'Member updated');
	}

	public function destroy(LibraryMember $member)
	{
		$member->delete();
		return redirect()->route('admin.library.members.index')->with('success', 'Member deleted');
	}

	public function toggleStatus(LibraryMember $member)
	{
		$member->status = $member->status === 'active' ? 'inactive' : 'active';
		$member->save();
		return redirect()->route('admin.library.members.index')->with('success', 'Status updated');
	}

	public function quickRenew(LibraryMember $member)
	{
		$member->expiry_at = Carbon::parse($member->expiry_at ?? now())->addYear();
		$member->status = 'active';
		$member->save();
		return redirect()->route('admin.library.members.index')->with('success', 'Membership renewed for 1 year');
	}

	public function export()
	{
		return Excel::download(new LibraryMembersExport(auth()->user()->school_id), 'library_members.xlsx');
	}

	public function import(Request $request)
	{
		$request->validate(['file' => 'required|file|mimes:xlsx,csv,txt']);
		Excel::import(new LibraryMembersImport(auth()->user()->school_id), $request->file('file'));
		return redirect()->route('admin.library.members.index')->with('success', 'Members imported successfully');
	}

	public function bulkDelete(Request $request)
	{
		$ids = $request->input('ids', []);
		if (!empty($ids)) { LibraryMember::whereIn('id', $ids)->delete(); }
		return redirect()->route('admin.library.members.index')->with('success', 'Selected members deleted');
	}

	public function bulkStatus(Request $request)
	{
		$validated = $request->validate([
			'ids' => 'required|array',
			'ids.*' => 'integer|exists:library_members,id',
			'status' => 'required|in:active,inactive,expired',
		]);
		LibraryMember::whereIn('id', $validated['ids'])->update(['status' => $validated['status']]);
		return redirect()->route('admin.library.members.index')->with('success', 'Status updated for selected members');
	}
}


