@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <h1> Personal Information </h1>
        </div>
        <div class="card-body">
            <form action="{{ route('users.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3 mt-3">
                    <label for="avatar" class="form-label">Avatar:</label>
                    <input type="file" class="form-control" id="avatar" name="avatar">
                </div>
                <div class="mb-3 mt-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name"
                        value="{{ $user->name }}">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email"
                        value="{{ $user->email }}">
                </div>
                <div class="mb-3 mt-3">
                    <label for="bio" class="form-label">Bio:</label>
                    <input type="text" class="form-control" id="bio" placeholder="Enter Bio" name="bio"
                        value="{{ $user->bio }}">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h1>Password</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('users.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3 mt-3">
                    <label for="current" class="form-label">Current Password:</label>
                    <input type="password" class="form-control" id="current" placeholder="Enter Current Password"
                        name="current_password">
                </div>
                <div class="mb-3 mt-3">
                    <label for="new" class="form-label">New Password:</label>
                    <input type="password" class="form-control" id="new" placeholder="Enter New Password"
                        name="password">
                </div>
                <div class="mb-3 mt-3">
                    <label for="repeat" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" id="repeat" placeholder="Enter Password Confirmation"
                        name="password_confirmation">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
    </div>
@endsection
