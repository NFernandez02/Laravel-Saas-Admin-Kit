@extends('layouts.app')

@section('content')
    <h1>Logs</h1>
    <form method="GET" action="{{route('admin.logs')}}" class="mb-3">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search logs by user..."
            value="{{request('search')}}"
        >
    </form>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Target</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($audit_logs as $log)
                <tr>
                    <td>{{ $log->user?->name ?? 'Deleted User' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->target_type }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
    {{$audit_logs->links()}}
    </div>
@endsection
