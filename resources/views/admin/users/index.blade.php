@extends('layouts.app')

@section('content')
    <a href='/admin/users/create' class="btn btn-primary">Add New User</a>
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
                        <a href='/admin/users/{{ $user->id }}/edit' class="btn btn-primary">Edit User</a>
                    </td>
                    <td>
                        <form method="POST" action="/admin/users/{{ $user->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"class="btn btn-danger"> Delete User</button>
                        </form>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
