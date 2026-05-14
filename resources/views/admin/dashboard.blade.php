@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Total Users</h5>
                <h2>{{ $totalUsers }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Total Roles</h5>
                <h2>{{ $totalRoles }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Total Admins</h5>
                <h2>{{ $totalAdmins }}</h2>
            </div>
        </div>
    </div>
    <div class="mt-5">
        <h3>Recent Activity</h3>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($latestLogs as $log)
                <tr>
                    <td>{{$log->user?->name ?? 'Deleted User'}}</td>
                    <td>{{$log->action}}</td>
                    <td>{{$log->description}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
