@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Dashboard Admin</h2>
    <p>Halo, {{ Auth::user()->name }} ({{ Auth::user()->role }})</p>
    <form action="{{ route('internal.logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>
@endsection
