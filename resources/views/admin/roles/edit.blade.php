@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3 mt-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" placeholder="Enter name"
                value="{{ $role->name }}"name="name">
        </div>
        @foreach ($permissions as $permission)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="permissions[]" value="{{$permission->id}}" {{$role->permissions->contains($permission->id) ? 'checked' : ''}}>
                <label class="form-check-label">{{$permission->name}}</label>
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Submit</button>
        @if ($errors->any())
            <div>
                {{ $errors->first() }}
            </div>
        @endif
    </form>
@endsection
