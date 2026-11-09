@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Login Internal</h2>
    <form action="{{ route('internal.login.post') }}" method="POST">
        @csrf
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
        @if($errors->any())
            <div style="color:red">{{ $errors->first() }}</div>
        @endif
    </form>
</div>
@endsection
