@extends('layouts.app')

@section('content')
    <h1>Logs</h1>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Target</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($audit_logs as $log)
                <tr>
                    <td>{{ $log->user?->name ?? 'Deleted User' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->target_type }}</td>
                    <td>{{ $log->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
