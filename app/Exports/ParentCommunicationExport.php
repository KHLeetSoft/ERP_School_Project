<?php

namespace App\Exports;

use App\Models\ParentCommunication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParentCommunicationExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = ParentCommunication::with(['parentDetail.user', 'student.user', 'admin']);

        // Apply filters if provided
        if ($this->request) {
            if ($this->request->filled('communication_type')) {
                $query->byType($this->request->communication_type);
            }
            if ($this->request->filled('status')) {
                $query->byStatus($this->request->status);
            }
            if ($this->request->filled('priority')) {
                $query->byPriority($this->request->priority);
            }
            if ($this->request->filled('category')) {
                $query->byCategory($this->request->category);
            }
            if ($this->request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $this->request->date_from);
            }
            if ($this->request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $this->request->date_to);
            }
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Parent Name',
            'Student Name',
            'Admin Name',
            'Communication Type',
            'Subject',
            'Message',
            'Status',
            'Priority',
            'Category',
            'Sent Date',
            'Delivered Date',
            'Read Date',
            'Response',
            'Response Date',
            'Communication Channel',
            'Cost',
            'Notes',
            'Created Date',
        ];
    }

    public function map($communication): array
    {
        return [
            $communication->id,
            $communication->parentDetail->primary_contact_name ?? $communication->parentDetail->user->name ?? 'N/A',
            $communication->student ? ($communication->student->first_name . ' ' . $communication->student->last_name) : 'N/A',
            $communication->admin ? $communication->admin->name : 'N/A',
            ucfirst($communication->communication_type),
            $communication->subject ?? 'N/A',
            $communication->message,
            ucfirst($communication->status),
            ucfirst($communication->priority),
            $communication->category ? ucfirst($communication->category) : 'N/A',
            $communication->sent_at ? $communication->sent_at->format('Y-m-d H:i:s') : 'N/A',
            $communication->delivered_at ? $communication->delivered_at->format('Y-m-d H:i:s') : 'N/A',
            $communication->read_at ? $communication->read_at->format('Y-m-d H:i:s') : 'N/A',
            $communication->response ?? 'N/A',
            $communication->response_at ? $communication->response_at->format('Y-m-d H:i:s') : 'N/A',
            $communication->communication_channel ?? 'N/A',
            $communication->cost ?? '0.00',
            $communication->notes ?? 'N/A',
            $communication->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 25,  // Parent Name
            'C' => 25,  // Student Name
            'D' => 20,  // Admin Name
            'E' => 20,  // Communication Type
            'F' => 30,  // Subject
            'G' => 40,  // Message
            'H' => 15,  // Status
            'I' => 15,  // Priority
            'J' => 15,  // Category
            'K' => 20,  // Sent Date
            'L' => 20,  // Delivered Date
            'M' => 20,  // Read Date
            'N' => 40,  // Response
            'O' => 20,  // Response Date
            'P' => 25,  // Communication Channel
            'Q' => 15,  // Cost
            'R' => 30,  // Notes
            'S' => 20,  // Created Date
        ];
    }
}
