<?php

namespace App\Imports;

use App\Models\StudentResult;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentResultsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new StudentResult([
            //
        ]);
    }
}
