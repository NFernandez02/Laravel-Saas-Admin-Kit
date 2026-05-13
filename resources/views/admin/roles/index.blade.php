@extends('layouts.app')

@section('content')
    <a href='{{ route('admin.roles.create') }}' class="btn btn-primary">Add New Role</a>
    <form method="GET" action="{{route('admin.roles.index')}}" class="mb-3">
        <input
            type="text"
            name="search"
            class="form-control"
            value="{{request('search')}}"
            placeholder="Search roles..."
        >
    </form>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Role Name</th>
                <th>User Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->users_count }}</td>
                    <td>
                        <a href='{{ route('admin.roles.edit', $role) }}' class="btn btn-primary">Edit Role</a>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"class="btn btn-danger"> Delete Role</button>
                        </form>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{$roles->links()}}
@endsection
