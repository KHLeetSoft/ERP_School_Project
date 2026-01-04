<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class LibrariansImport implements ToModel, WithHeadingRow
{
	public function model(array $row)
	{
		if (!isset($row['name']) || !isset($row['email'])) {
			return null;
		}
		return new User([
			'name' => $row['name'],
			'email' => $row['email'],
			'password' => Hash::make($row['password'] ?? 'password123'),
			'role_id' => 4,
			'admin_id' => auth()->id(),
			'status' => isset($row['status']) ? (int) $row['status'] : 1,
		]);
	}
}


