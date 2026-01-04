<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\ExamSms;
use App\Models\ExamSmsRecipient;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExamSmsRecipientController extends Controller
{
    public function index(Request $request, ExamSms $sms)
    {
        if ($request->ajax()) {
            $query = ExamSmsRecipient::where('exam_sms_id', $sms->id)->latest();
            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }
            if ($request->filled('type')) {
                $query->where('recipient_type', $request->get('type'));
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('status', function ($r) {
                    return strtoupper($r->status);
                })
                ->make(true);
        }
        return view('admin.exams.sms.recipients.index', compact('sms'));
    }
}


