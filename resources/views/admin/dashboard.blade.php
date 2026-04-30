@extends('layouts.app')

@section('content')
<h1>Welcome Admin</h1>
<a href='{{ route('admin.users.index') }}' class="btn btn-primary"> Check Users</a>
@endsection
