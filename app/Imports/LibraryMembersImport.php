<?php

namespace App\Imports;

use App\Models\LibraryMember;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LibraryMembersImport implements ToModel, WithHeadingRow
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function model(array $row)
	{
		if (!isset($row['name'])) { return null; }
		return new LibraryMember([
			'school_id' => $this->schoolId,
			'membership_no' => $row['membership_no'] ?? strtoupper(Str::random(8)),
			'name' => $row['name'],
			'email' => $row['email'] ?? null,
			'phone' => $row['phone'] ?? null,
			'address' => $row['address'] ?? null,
			'member_type' => $row['member_type'] ?? 'student',
			'joined_at' => isset($row['joined_at']) ? Carbon::parse($row['joined_at']) : now(),
			'expiry_at' => isset($row['expiry_at']) ? Carbon::parse($row['expiry_at']) : null,
			'status' => $row['status'] ?? 'active',
			'notes' => $row['notes'] ?? null,
		]);
	}
}


