<?php

namespace App\Exports;

use App\Models\ComplaintBox;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ComplaintBoxExport implements FromView
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function view(): View
    {
        return view('exports.complaints', [
            'complaints' => ComplaintBox::where('user_id', $this->userId)->get()
        ]);
    }
}
