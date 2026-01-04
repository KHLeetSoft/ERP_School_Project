@extends('admin.layout.app')

@section('content')
@php
    $school = auth()->user()->school ?? null;
    $schoolName = $school->name ?? 'Your School Name';
    $schoolTagline = data_get($school, 'theme_settings.tagline', 'In Pursuit of Excellence');
    $logoPath = $school?->logo ? ('storage/'.$school->logo) : 'assets/images/logo/logo1.png';
    $logo = asset($logoPath);
@endphp

<style>
    .print-actions { text-align:center; margin: 12px 0; }
    .id-sheet { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; justify-content: center; align-items: start; }
    .id-card { width: 85.6mm; height: 54mm; border: 0.4mm solid #dcdcdc; border-radius: 3mm; overflow: hidden; background: #fff; position: relative; }
    .id-card.front .header { background: linear-gradient(135deg,#1553b1 0%,#2b7bff 100%); color:#fff; padding: 3mm 4mm; display:flex; align-items:center; gap:3mm; }
    .id-card.front .header img { width: 12mm; height: 12mm; object-fit: contain; filter: drop-shadow(0 0 2px rgba(0,0,0,.15)); background: rgba(255,255,255,.15); border-radius: 2mm; }
    .id-card.front .header .title { line-height: 1.15; }
    .id-card.front .header .title .name { font-weight: 700; font-size: 3.7mm; letter-spacing:.2mm; }
    .id-card.front .header .title .tag { font-size: 2.6mm; opacity:.9; }
    .id-card.front .body { display:grid; grid-template-columns: 22mm 1fr; gap:3mm; padding: 3.5mm 4mm; }
    .photo { width: 22mm; height: 28mm; background: #f2f5f9; border: .3mm dashed #c9d6e2; border-radius: 1.5mm; display:flex; align-items:center; justify-content:center; font-size:2.3mm; color:#7b8aa0; }
    .photo img { width:100%; height:100%; object-fit: cover; border-radius: 1.2mm; }
    .fields { display:grid; grid-template-columns: 1fr 1fr; column-gap: 4mm; row-gap: 1.8mm; font-size: 2.8mm; }
    .field { display:flex; flex-direction: column; }
    .label { font-size: 2.4mm; color:#728299; text-transform: uppercase; letter-spacing:.15mm; }
    .value { font-weight: 600; color:#1f2a44; }
    .footer { position:absolute; left:0; right:0; bottom:0; padding: 2.5mm 4mm; display:flex; justify-content: space-between; align-items:center; background: #f7f9fc; border-top: .3mm solid #eef2f7; font-size: 2.6mm; color:#3b4a66; }
    .badge { background:#eaf2ff; color:#1450aa; padding: .8mm 1.8mm; border-radius: 1.4mm; font-weight:700; letter-spacing:.2mm; text-transform: uppercase; }

    .id-card.back .header { background: #f1f5fb; padding: 3mm 4mm; font-weight:700; color:#2c3e64; border-bottom: .3mm solid #e5edf7; }
    .id-card.back .body { padding: 3.5mm 4mm; display:grid; gap:2mm; font-size: 2.8mm; }
    .qr { width: 18mm; height: 18mm; border: .3mm dashed #c9d6e2; display:flex; align-items:center; justify-content:center; background:#fff; }
    .row-line { display:flex; gap:3mm; }
    .hint { font-size: 2.4mm; color:#728299; }

    @media print {
        .print-actions { display:none; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .id-sheet { gap: 8mm; }
        .id-card { box-shadow: none !important; }
    }
</style>

<div class="print-actions">
    <button class="btn btn-primary" onclick="window.print()"><i class="bx bx-printer"></i> Print</button>
    <a class="btn btn-secondary" href="{{ route('admin.documents.idcard.index') }}">Back</a>
    <small class="text-muted d-block mt-1">Actual print size: 85.6mm × 54mm (CR-80)</small>
    </div>

<div class="container mt-2" id="print-area">
    <div class="id-sheet">
        <!-- Front Side -->
        <div class="id-card front">
            <div class="header">
                <img src="{{ $logo }}" alt="Logo">
                <div class="title">
                    <div class="name">{{ $schoolName }}</div>
                    <div class="tag">{{ $schoolTagline }}</div>
                </div>
            </div>
            <div class="body">
                <div class="photo">
                    @if($idcard->photo_path)
                        <img src="{{ asset($idcard->photo_path) }}" alt="Photo">
                    @else
                        Photo
                    @endif
                </div>
                <div class="fields">
                    <div class="field">
                        <div class="label">Student</div>
                        <div class="value">{{ $idcard->student_name }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Roll</div>
                        <div class="value">{{ $idcard->roll_number ?? '-' }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Class</div>
                        <div class="value">{{ $idcard->class_name ?? '-' }} {{ $idcard->section_name ?? '' }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Blood</div>
                        <div class="value">{{ $idcard->blood_group ?? '-' }}</div>
                    </div>
                    <div class="field">
                        <div class="label">DOB</div>
                        <div class="value">{{ optional($idcard->date_of_birth)->format('d M Y') }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Status</div>
                        <div class="value"><span class="badge">{{ strtoupper($idcard->status) }}</span></div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <span>Issue: {{ optional($idcard->issue_date)->format('d M Y') }}</span>
                <span>Expiry: {{ optional($idcard->expiry_date)->format('d M Y') }}</span>
            </div>
        </div>

        <!-- Back Side -->
        <div class="id-card back">
            <div class="header">Guardian & Emergency</div>
            <div class="body">
                <div class="row-line"><strong>Guardian:</strong> <span>{{ $idcard->guardian_name ?? '-' }}</span></div>
                <div class="row-line"><strong>Phone:</strong> <span>{{ $idcard->phone ?? '-' }}</span></div>
                <div class="row-line"><strong>Address:</strong> <span>{{ $idcard->address ?? '-' }}</span></div>
                <hr style="margin: 1.5mm 0;">
                <div class="hint">If found, please return this card to the school office.</div>
                <div class="row-line" style="margin-top:2mm; align-items:center; gap:6mm; justify-content: space-between;">
                    <div style="display:flex; align-items:center; gap:2mm;">
                        <img src="{{ $logo }}" alt="Logo" style="width:8mm; height:8mm; object-fit:contain; opacity:.75;">
                        <span style="font-weight:600; color:#2c3e64;">{{ $schoolName }}</span>
                    </div>
                    <div class="qr">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($qrPayload) }}" alt="QR">
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection


