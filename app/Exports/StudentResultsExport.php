<?php

namespace App\Exports;

use App\Models\StudentResult;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentResultsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return StudentResult::all();
    }
}
