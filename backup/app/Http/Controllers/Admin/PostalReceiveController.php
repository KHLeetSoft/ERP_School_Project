<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PostalReceive;
use Maatwebsite\Excel\Facades\Excel;

class PostalReceiveController extends Controller
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
            $query = PostalReceive::where('user_id',$adminUser->id)
                    ->where('school_id',$adminUser->school_id)
                    ->latest();
            if($request->filled('from_title')){
                $query->where('from_title','like','%'.$request->from_title.'%');
            }
            if($request->filled('date_from') && $request->filled('date_to')){
                $query->whereBetween('date',[$request->date_from,$request->date_to]);
            }
            
            $rows = $query->get();
            
            // Debug: Log the data
            \Log::info('PostalReceive Data:', [
                'count' => $rows->count(),
                'admin_user' => $adminUser ? $adminUser->id : 'null',
                'school_id' => $adminUser ? $adminUser->school_id : 'null',
                'query_sql' => $query->toSql(),
                'query_bindings' => $query->getBindings()
            ]);
            
            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('from_title', fn($row)=>'<a href="'.route('admin.office.receive.show',$row->id).'" class="link">'.e($row->from_title).'</a>')
                ->addColumn('reference_no', fn($row)=>e($row->reference_no))
                ->addColumn('address', fn($row)=>e($row->address))
                ->addColumn('to_title', fn($row)=>e($row->to_title))
                ->addColumn('date', fn($row)=>$row->date)
                ->addColumn('note', fn($row)=>e($row->note))
                ->addColumn('action', function($row){
                    $html='<div class="d-flex">';
                    $html.='<a href="'.route('admin.office.receive.show',$row->id).'" class="text-info me-2"><i class="bx bx-show"></i></a>';
                    $html.='<a href="'.route('admin.office.receive.edit',$row->id).'" class="text-primary me-2"><i class="bx bxs-edit"></i></a>';
                    $html.='<a href="javascript:void(0);" data-id="'.$row->id.'" class="text-danger delete-receive-btn"><i class="bx bx-trash"></i></a>';
                    $html.='</div>';
                    return $html;
                })
                ->rawColumns(['from_title','action'])
                ->make(true);
        }
        return view('admin.office.receive.index');
    }

    public function create(){
        return view('admin.office.receive.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'from_title'=>'required|string|max:255',
            'reference_no'=>'nullable|string',
            'address'=>'nullable|string',
            'to_title'=>'nullable|string',
            'date'=>'nullable|date',
            'note'=>'nullable|string',
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput();
        $data=$validator->validated();
        $admin=auth()->guard('admin')->user();
        $data['user_id']=$admin->id;
        $data['school_id']=$admin->school_id;
        PostalReceive::create($data);
        return redirect()->route('admin.office.receive.index')->with('success','Postal Receive added');
    }

    public function show($id){
        $item=PostalReceive::where('user_id',auth()->guard('admin')->id())->findOrFail($id);
        return view('admin.office.receive.show',compact('item'));
    }

    public function edit($id){
        $item=PostalReceive::where('user_id',auth()->guard('admin')->id())->findOrFail($id);
        return view('admin.office.receive.edit',compact('item'));
    }

    public function update(Request $request,$id){
        $item=PostalReceive::where('user_id',auth()->guard('admin')->id())->findOrFail($id);
        $validator = Validator::make($request->all(),[
            'from_title'=>'required|string|max:255',
            'reference_no'=>'nullable|string',
            'address'=>'nullable|string',
            'to_title'=>'nullable|string',
            'date'=>'nullable|date',
            'note'=>'nullable|string',
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput();
        $item->update($validator->validated());
        return redirect()->route('admin.office.receive.index')->with('success','Updated');
    }

    public function destroy($id){
        $item=PostalReceive::where('user_id',auth()->guard('admin')->id())->findOrFail($id);
        $item->delete();
        return response()->json(['message'=>'Deleted']);
    }

    public function export(){
        $userId=auth()->guard('admin')->id();
        $file='postal_receive_'.now()->format('Ymd_His').'.xlsx';
        return Excel::download(new \App\Exports\PostalReceiveExport($userId),$file);
    }

    public function import(Request $request){
        $request->validate(['file'=>'required|file|mimes:xlsx,csv']);
        $userId=auth()->guard('admin')->id();
        Excel::import(new \App\Imports\PostalReceiveImport($userId),$request->file('file'));
        return back()->with('success','Import completed');
    }
} 