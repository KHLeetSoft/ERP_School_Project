<?php 

namespace App\Imports;

use App\Models\ComplaintBox;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ComplaintBoxImport implements ToCollection
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // skip header

            ComplaintBox::create([
                'user_id'     => $this->userId,
                'complain_by' => $row[0],
                'phone'       => $row[1],
                'purpose'     => $row[2],
                'date'        => $row[3],
                'note'        => $row[4] ?? null,
            ]);
        }
    }
}
