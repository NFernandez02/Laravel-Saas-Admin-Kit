@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="mb-3 mt-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        @if ($errors->any())
            <div>
                {{ $errors->first() }}
            </div>
        @endif
    </form>
@endsection
