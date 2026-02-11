@extends('layouts.admin')

@section('title', 'Blog Categories')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Blog Categories</h1>
                <p class="mt-1 text-sm text-slate-500">Manage categories used for blog posts. Create, edit, reorder and remove categories.</p>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.blog-categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Category
                </a>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Controls: search, per-page, bulk actions --}}
        <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
            <form method="GET" action="{{ route('admin.blog-categories.index') }}" class="md:col-span-2 flex items-center gap-3">
                <input type="text" name="q" value="{{ request('q', $search ?? '') }}" placeholder="Search categories..." class="flex-1 px-4 py-2 rounded-lg border border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-600">Search</button>
                <a href="{{ route('admin.blog-categories.index') }}" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">Reset</a>
            </form>

            <div class="flex items-center justify-end gap-3">
                <label class="text-sm text-slate-600 mr-2">Per page</label>
                <form id="per-page-form" method="GET" action="{{ route('admin.blog-categories.index') }}">
                    <select name="per_page" onchange="document.getElementById('per-page-form').submit()" class="px-3 py-2 rounded-lg border border-slate-300 bg-white">
                        @foreach([10,20,50,100] as $n)
                            <option value="{{ $n }}" {{ (request('per_page') == $n) ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                    {{-- Preserve search q in per-page form --}}
                    <input type="hidden" name="q" value="{{ request('q', $search ?? '') }}">
                </form>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                        <input id="select-all" type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600">
                        Select all
                    </label>

                    <button id="bulk-delete-btn" class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm">Delete Selected</button>

                    <button id="reorder-save-btn" class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-sm">Save Order</button>
                </div>

                <div class="text-sm text-slate-500">
                    Showing {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} categories
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 w-12"><!-- checkbox --></th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3 w-48">Icon</th>
                            <th class="px-4 py-3 w-36">Slug</th>
                            <th class="px-4 py-3 w-28">Order</th>
                            <th class="px-4 py-3 w-36">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="px-4 py-3 align-top">
                                    <input type="checkbox" class="row-checkbox form-checkbox h-4 w-4 text-indigo-600" value="{{ $category->id }}" data-id="{{ $category->id }}">
                                </td>

                                <td class="px-4 py-3 align-top">
                                    <div class="font-medium text-slate-900">{{ $category->name }}</div>
                                    @if($category->description)
                                        <div class="text-xs text-slate-500 mt-1">{{ \Illuminate\Support\Str::limit($category->description, 120) }}</div>
                                    @endif
                                </td>

                                <td class="px-4 py-3 align-top">
                                    @if($category->icon)
                                        @if(Str::startsWith($category->icon, 'storage/'))
                                            <img src="{{ asset($category->icon) }}" alt="{{ $category->name }} icon" class="h-10 w-10 rounded object-cover border">
                                        @else
                                            {{-- assume icon is a CSS class (Font Awesome / hero) --}}
                                            <div class="flex items-center gap-2">
                                                <i class="{{ $category->icon }} text-xl text-slate-700"></i>
                                                <span class="text-xs text-slate-500">{{ $category->icon }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-xs text-slate-400 italic">No icon</div>
                                    @endif
                                </td>

                                <td class="px-4 py-3 align-top">
                                    <div class="text-sm text-slate-600 font-mono">{{ $category->slug }}</div>
                                </td>

                                <td class="px-4 py-3 align-top">
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="order-btn px-2 py-1 bg-slate-100 rounded text-sm" data-action="up" data-id="{{ $category->id }}">▲</button>
                                        <button type="button" class="order-btn px-2 py-1 bg-slate-100 rounded text-sm" data-action="down" data-id="{{ $category->id }}">▼</button>
                                        <input type="number" class="order-input w-16 px-2 py-1 border rounded text-sm" data-id="{{ $category->id }}" value="{{ $category->order }}">
                                    </div>
                                </td>

                                <td class="px-4 py-3 align-top">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.blog-categories.edit', $category) }}" class="px-3 py-1 bg-amber-500 text-white rounded hover:bg-amber-600 text-sm">Edit</a>

                                        <form method="POST" action="{{ route('admin.blog-categories.destroy', $category) }}" onsubmit="return confirm('Delete category &quot;{{ $category->name }}&quot;? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-slate-500">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="px-4 py-4 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <form id="bulk-action-form" method="POST" action="{{ route('admin.blog-categories.bulk-destroy') }}">
                        @csrf
                        <input type="hidden" name="ids" id="bulk-ids">
                    </form>
                </div>

                <div>
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Select all toggle
    const selectAll = document.getElementById('select-all');
    const rowCheckboxes = Array.from(document.querySelectorAll('.row-checkbox'));
    selectAll?.addEventListener('change', function () {
        rowCheckboxes.forEach(cb => cb.checked = this.checked);
    });

    // Bulk delete
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    bulkDeleteBtn?.addEventListener('click', function () {
        const selected = rowCheckboxes.filter(cb => cb.checked).map(cb => cb.value);
        if (selected.length === 0) {
            alert('Please select at least one category to delete.');
            return;
        }
        if (!confirm('Delete ' + selected.length + ' selected categories? This cannot be undone.')) return;

        // Submit via form with JSON-encoded ids
        const form = document.getElementById('bulk-action-form');
        const idsInput = document.getElementById('bulk-ids');
        idsInput.value = JSON.stringify(selected);

        form.submit();
    });

    // Order up/down buttons and Save Order
    const orderInputs = Array.from(document.querySelectorAll('.order-input'));
    const orderButtons = Array.from(document.querySelectorAll('.order-btn'));

    orderButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const action = this.dataset.action;
            const id = this.dataset.id;
            const current = orderInputs.find(inp => inp.dataset.id === id);
            if (!current) return;
            let val = parseInt(current.value || 0, 10);
            if (action === 'up') val = val - 1;
            if (action === 'down') val = val + 1;
            current.value = val;
        });
    });

    // Save order via AJAX
    const reorderSaveBtn = document.getElementById('reorder-save-btn');
    reorderSaveBtn?.addEventListener('click', function () {
        const items = orderInputs.map(inp => ({ id: inp.dataset.id, order: parseInt(inp.value || 0, 10) }));
        if (!confirm('Save the updated order for ' + items.length + ' categories?')) return;

        fetch("{{ route('admin.blog-categories.reorder') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order: items })
        })
        .then(res => {
            if (!res.ok) throw res;
            return res.json();
        })
        .then(json => {
            alert(json.message || 'Order updated');
            // Optionally reload to reflect ordering
            window.location.reload();
        })
        .catch(async (err) => {
            let msg = 'Failed to update order';
            try {
                const txt = await err.text();
                msg += ': ' + txt;
            } catch(e) {}
            alert(msg);
        });
    });

    // Make table rows keyboard-focusable for accessibility
    document.querySelectorAll('tbody tr').forEach(tr => tr.setAttribute('tabindex', 0));
});
</script>
@endpush