@extends('layouts.admin')

@section('title', 'Countries')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Countries</h1>
        <a href="{{ route('admin.countries.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Country
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.countries.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search countries..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Countries Table -->
    <div class="card shadow">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Flag</th>
                            <th>Destinations</th>
                            <th>Activities</th>
                            <th>Status</th>
                            <th>Sort Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($countries as $country)
                        <tr>
                            <td><input type="checkbox" class="select-item" value="{{ $country->id }}"></td>
                            <td>{{ $country->name }}</td>
                            <td>{{ $country->code }}</td>
                            <td>{{ $country->flag_icon }}</td>
                            <td>{{ $country->destinations_count }}</td>
                            <td>{{ $country->activities_count }}</td>
                            <td>
                                <span class="badge badge-{{ $country->is_active ? 'success' : 'secondary' }}">
                                    {{ $country->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $country->sort_order }}</td>
                            <td>
                                <a href="{{ route('admin.countries.edit', $country) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.countries.toggle-status', $country) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-{{ $country->is_active ? 'warning' : 'success' }}">
                                        <i class="fas fa-{{ $country->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.countries.destroy', $country) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No countries found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $countries->links() }}
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="mt-3">
        <form action="{{ route('admin.countries.bulk-delete') }}" method="POST" id="bulk-delete-form" onsubmit="return confirm('Are you sure?')">
            @csrf
            <input type="hidden" name="ids" id="bulk-ids">
            <button type="submit" class="btn btn-danger">
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
            alert('Please select at least one country');
            return false;
        }
        document.getElementById('bulk-ids').value = JSON.stringify(selected);
    });
</script>
@endpush
@endsection