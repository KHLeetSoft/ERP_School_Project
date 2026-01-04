<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LibrariansExport implements FromCollection, WithHeadings
{
	public function collection()
	{
		return User::where('role_id', 4)->select('id','name','email','status','created_at','updated_at')->get();
	}

	public function headings(): array
	{
		return ['ID','Name','Email','Status','Created At','Updated At'];
	}
}


