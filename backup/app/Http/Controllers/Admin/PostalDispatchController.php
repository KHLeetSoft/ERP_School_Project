<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PostalDispatch;
use Maatwebsite\Excel\Facades\Excel;

class PostalDispatchController extends Controller
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
        if($request->ajax()){
            $adminUser = auth()->guard('admin')->user();
            if(!$adminUser || $adminUser->role_id != 2){
                return DataTables::of(collect([]))->make(true);
            }
            $userId = $adminUser->id;
            $query = PostalDispatch::where('user_id',$userId)->latest();
            if($request->filled('to_title')){
                $query->where('to_title','like','%'.$request->to_title.'%');
            }
            if($request->filled('date_from') && $request->filled('date_to')){
                $query->whereBetween('date',[$request->date_from,$request->date_to]);
            }
            
            $rows = $query->get();
            
            // Debug: Log the data
            \Log::info('PostalDispatch Data:', [
                'count' => $rows->count(),
                'admin_user' => $adminUser ? $adminUser->id : 'null',
                'user_id' => $userId,
                'query_sql' => $query->toSql(),
                'query_bindings' => $query->getBindings()
            ]);
            
            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('to_title', fn($row) => '<a href="'.route('admin.office.dispatch.show',$row->id).'" class="link">'.e($row->to_title).'</a>')
                ->addColumn('reference_no', fn($row)=>e($row->reference_no))
                ->addColumn('address', fn($row)=>e($row->address))
                ->addColumn('from_title', fn($row)=>e($row->from_title))
                ->addColumn('date', fn($row)=>$row->date)
                ->addColumn('note', fn($row)=>e($row->note))
                ->addColumn('action', function($row){
                    $btns='<div class="d-flex">';
                    $btns.='<a href="'.route('admin.office.dispatch.show',$row->id).'" class="text-info me-2"><i class="bx bx-show"></i></a>';
                    $btns.='<a href="'.route('admin.office.dispatch.edit',$row->id).'" class="text-primary me-2"><i class="bx bxs-edit"></i></a>';
                    $btns.='<a href="javascript:void(0);" data-id="'.$row->id.'" class="text-danger delete-dispatch-btn"><i class="bx bx-trash"></i></a>';
                    $btns.='</div>';
                    return $btns;
                })
                ->rawColumns(['to_title','action'])
                ->make(true);
        }
        return view('admin.office.dispatch.index');
    }

    public function create()
    {
        return view('admin.office.dispatch.create');
    }

    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'to_title'=>'required|string|max:255',
            'reference_no'=>'nullable|string|max:255',
            'address'=>'nullable|string',
            'from_title'=>'nullable|string|max:255',
            'date'=>'nullable|date',
            'note'=>'nullable|string',
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput();
        $data=$validator->validated();
        $data['user_id']=auth()->guard('admin')->id();
        PostalDispatch::create($data);
        return redirect()->route('admin.office.dispatch.index')->with('success','Postal Dispatch added');
    }

    public function show($id)
    {
        $item=PostalDispatch::where('user_id',auth()->guard('admin')->id())->findOrFail($id);
        return view('admin.office.dispatch.show',compact('item'));
    }

    public function edit($id)
    {
        $item=PostalDispatch::where('user_id',auth()->guard('admin')->id())->findOrFail($id);
        return view('admin.office.dispatch.edit',compact('item'));
    }

    public function update(Request $request,$id)
    {
        $item=PostalDispatch::where('user_id',auth()->guard('admin')->id())->findOrFail($id);
        $validator=Validator::make($request->all(),[
            'to_title'=>'required|string|max:255',
            'reference_no'=>'nullable|string|max:255',
            'address'=>'nullable|string',
            'from_title'=>'nullable|string|max:255',
            'date'=>'nullable|date',
            'note'=>'nullable|string',
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput();
        $item->update($validator->validated());
        return redirect()->route('admin.office.dispatch.index')->with('success','Updated');
    }

    public function destroy($id)
    {
        $item=PostalDispatch::where('user_id',auth()->guard('admin')->id())->findOrFail($id);
        $item->delete();
        return response()->json(['message'=>'Deleted']);
    }

    public function export()
    {
        $userId=auth()->guard('admin')->id();
        $file='postal_dispatch_'.now()->format('Ymd_His').'.xlsx';
        return Excel::download(new \App\Exports\PostalDispatchExport($userId),$file);
    }

    public function import(Request $request)
    {
        $request->validate(['file'=>'required|file|mimes:xlsx,csv']);
        $userId=auth()->guard('admin')->id();
        Excel::import(new \App\Imports\PostalDispatchImport($userId),$request->file('file'));
        return back()->with('success','Import completed');
    }
} 