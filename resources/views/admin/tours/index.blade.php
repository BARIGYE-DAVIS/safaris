@extends('layouts.admin')

@section('title', 'Tours List')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">All Tours</h1>
    <a href="{{ route('admin.tours.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">
        <i class="fas fa-plus"></i> Create New Tour
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if($tours->count())
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-indigo-100">
                    <th class="px-4 py-2 text-left">Title</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Destinations</th>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Created</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tours as $tour)
                    <tr class="border-b hover:bg-indigo-50">
                        <td class="px-4 py-2">{{ $tour->title }}</td>
                        <td class="px-4 py-2">{{ $tour->category }}</td>
                        <td class="px-4 py-2">{{ $tour->destinations }}</td>
                        <td class="px-4 py-2">{{ $tour->type }}</td>
                        <td class="px-4 py-2">{{ $tour->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('admin.tours.edit', $tour->id) }}" class="text-indigo-600 hover:underline mr-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.tours.destroy', $tour->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure to delete this tour?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline" type="submit">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $tours->links() }}
    </div>
@else
    <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded">
        No tours found. <a href="{{ route('admin.tours.create') }}" class="underline text-indigo-700">Create your first tour here.</a>
    </div>
@endif
@endsection