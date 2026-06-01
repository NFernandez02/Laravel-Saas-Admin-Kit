@extends('layouts.app')

@section('content')
    <a href='{{ route('admin.permissions.create') }}' class="btn btn-primary">Add New Permission</a>
    <form method="GET" action="{{ route('admin.permissions.index') }}" class="mb-3">
        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search roles...">
    </form>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Permission Name</th>
                <th>Role Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->roles_count }}</td>
                    <td>
                        <a href='{{ route('admin.permissions.edit', $permission) }}' class="btn btn-primary">Edit Permission</a>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"class="btn btn-danger"> Delete Role</button>
                        </form>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {{ $permissions->links() }}
    </div>
@endsection
