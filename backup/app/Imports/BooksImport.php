<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['title']) || !isset($row['author'])) {
            return null;
        }

        return new Book([
            'school_id' => $this->schoolId,
            'title' => $row['title'] ?? null,
            'author' => $row['author'] ?? null,
            'genre' => $row['genre'] ?? null,
            'published_year' => isset($row['published_year']) ? (int) $row['published_year'] : null,
            'isbn' => $row['isbn'] ?? null,
            'description' => $row['description'] ?? null,
            'stock_quantity' => isset($row['stock_quantity']) ? (int) $row['stock_quantity'] : 0,
            'shelf_location' => $row['shelf_location'] ?? null,
            'status' => $row['status'] ?? 'available',
        ]);
    }
}


