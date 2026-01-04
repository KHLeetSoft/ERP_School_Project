<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin: 0 0 10px; }
        .muted { color: #555; }
        ol { padding-left: 18px; }
    </style>
</head>
<body>
    <h1>{{ $data['title'] ?? ($paper->subject.' Paper') }}</h1>
    @if(!empty($data['instructions']))
        <div class="muted">{{ $data['instructions'] }}</div>
    @endif

    <ol>
        @foreach(($data['questions'] ?? []) as $q)
            <li style="margin-bottom:8px;">
                <div><strong>({{ $q['marks'] ?? 1 }} marks)</strong> {{ $q['question'] ?? '' }}</div>
                @if(!empty($q['options']))
                    <ol type="A">
                        @foreach($q['options'] as $opt)
                            <li>{{ $opt }}</li>
                        @endforeach
                    </ol>
                @endif
            </li>
        @endforeach
    </ol>
</body>
<html>


