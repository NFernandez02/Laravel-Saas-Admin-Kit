@extends('layouts.app')

@section('content')
    <form action="/admin/users/{{ $user->id }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3 mt-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" placeholder="Enter name" value="{{ $user->name }}"name="name">
        </div>
        <div class="mb-3">
            <label class="form-check-label" for="role">Role:</label>
            <select class="form-select" id="role" name="role" >
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" >{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        @if ($errors->any())
            <div>
                {{ $errors->first() }}
            </div>
        @endif
    </form>
@endsection
