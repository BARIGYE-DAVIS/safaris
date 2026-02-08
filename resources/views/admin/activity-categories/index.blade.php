@extends('layouts.admin')

@section('title', 'Activity Categories')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Activity Categories</h1>
        <a href="{{ route('admin.activity-categories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition shadow-md">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-5 mb-6">
        <form method="GET" action="{{ route('admin.activity-categories.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Search categories..." value="{{ request('search') }}">
                </div>
                <div>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition flex-1">
                        Filter
                    </button>
                    <a href="{{ route('admin.activity-categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-5">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </p>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Icon</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sort Order</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($activityCategories as $category)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="select-item rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="{{ $category->id }}">
                            </td>
                            <td class="px-6 py-4">
                                @if($category->icon)
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 text-xl">
                                        <i class="{{ $category->icon }}"></i>
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-th-large text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500">{{ $category->slug }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs truncate">
                                    {{ $category->description ?? 'No description' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $category->is_active ? '✓ Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $category->sort_order }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium space-x-2">
                                <a href="{{ route('admin.activity-categories.edit', $category) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.activity-categories.toggle-status', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 {{ $category->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded transition" title="Toggle Status">
                                        <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.activity-categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-th-large text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg">No activity categories found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $activityCategories->links() }}
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="mt-4">
        <form action="{{ route('admin.activity-categories.bulk-delete') }}" method="POST" id="bulk-delete-form" onsubmit="return confirm('Are you sure?')">
            @csrf
            <input type="hidden" name="ids" id="bulk-ids">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-trash"></i> Delete Selected
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Select all checkboxes
    document.getElementById('select-all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.select-item');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Bulk delete
    document.getElementById('bulk-delete-form').addEventListener('submit', function(e) {
        let selected = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => cb.value);
        if (selected.length === 0) {
            e.preventDefault();
            alert('Please select at least one category');
            return false;
        }
        document.getElementById('bulk-ids').value = JSON.stringify(selected);
    });
</script>
@endpush
@endsection