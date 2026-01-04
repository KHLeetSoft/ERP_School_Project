<?php

namespace App\Exports;

use App\Models\LibraryMember;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LibraryMembersExport implements FromCollection, WithHeadings
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function collection()
	{
		return LibraryMember::select('id','school_id','membership_no','name','email','phone','address','member_type','joined_at','expiry_at','status','notes','created_at','updated_at')
			->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
			->get();
	}

	public function headings(): array
	{
		return ['ID','School ID','Membership No','Name','Email','Phone','Address','Member Type','Joined At','Expiry At','Status','Notes','Created At','Updated At'];
	}
}


