@extends('layouts.app')

@section('content')
<h1>Welcome Admin</h1>
<a href='{{ route('admin.users.index') }}' class="btn btn-primary"> Check Users</a>

<a href='{{ route('admin.roles.index') }}' class="btn btn-primary"> Check Roles</a>

<a href='{{ route('admin.logs') }}' class="btn btn-primary"> Check Logs</a>
@endsection
