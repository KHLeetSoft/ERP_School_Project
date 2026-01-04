<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return Book::select('id', 'school_id', 'title', 'author', 'genre', 'published_year', 'isbn', 'description', 'stock_quantity', 'shelf_location', 'status', 'created_at', 'updated_at')
            ->when($this->schoolId, function ($q) {
                $q->where('school_id', $this->schoolId);
            })
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'School ID', 'Title', 'Author', 'Genre', 'Published Year', 'ISBN', 'Description', 'Stock Quantity', 'Shelf Location', 'Status', 'Created At', 'Updated At'];
    }
}


