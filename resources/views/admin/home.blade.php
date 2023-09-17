@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h1 class="py-3">Welcome back, {{ Auth::user()->name }}!</h1>
@endsection
