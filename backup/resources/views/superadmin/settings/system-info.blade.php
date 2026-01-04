@extends('superadmin.app')

@section('content')
<h2>System Information</h2>

<ul>
    <li><strong>PHP Version:</strong> {{ $systemInfo['php_version'] }}</li>
    <li><strong>Laravel Version:</strong> {{ $systemInfo['laravel_version'] }}</li>
    <li><strong>Server Software:</strong> {{ $systemInfo['server_software'] }}</li>
    <li><strong>DB Connection:</strong> {{ $systemInfo['database_connection'] }}</li>
</ul>
@endsection
