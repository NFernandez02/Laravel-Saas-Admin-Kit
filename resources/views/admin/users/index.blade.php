@extends('layouts.app')

@section('content')
    <a href='{{ route('admin.users.create') }}' class="btn btn-primary">Add New User</a>
    <form method="GET" action="{{ route('admin.users.index')}}" class="mb-3">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search Users..."
            value="{{ request('search')}}"
        >
    </form>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->name }}</td>
                    <td>
                        <a href='{{ route('admin.users.edit', $user) }}' class="btn btn-primary">Edit User</a>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"class="btn btn-danger"> Delete User</button>
                        </form>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
@endsection
