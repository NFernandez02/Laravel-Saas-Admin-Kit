@extends('layouts.app')
@section('content')
<form method="POST" action="{{route('verify')}}">
    @csrf
    Enter 2-FA Code from Authenticator
    <input type="text" name="code">
    <button type="submit">Confirm</button>
</form>
@endsection