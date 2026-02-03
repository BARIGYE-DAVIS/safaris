@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome, {{ Auth::guard('admin')->user()->email }}</h1>
        <p class="leading-7 text-gray-600">This is your admin dashboard. You can customize it with stats, links, or other content for administrators.</p>
    </div>
@endsection