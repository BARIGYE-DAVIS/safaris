@extends('layouts.admin')

@section('title', 'Edit Country')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Country: {{ $country->name }}</h1>
        <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <!-- Edit Form -->
    <div class="card shadow">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.countries.update', $country) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Country Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $country->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Code -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">Country Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $country->code) }}" placeholder="e.g., KE, TZ, UG" maxlength="3" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">2 or 3 letter country code (ISO format)</small>
                        </div>
                    </div>

                    <!-- Flag Icon -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="flag_icon">Flag Icon/Emoji</label>
                            <input type="text" name="flag_icon" id="flag_icon" class="form-control @error('flag_icon') is-invalid @enderror" value="{{ old('flag_icon', $country->flag_icon) }}" placeholder="🇰🇪 or flag-ke">
                            @error('flag_icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Flag emoji or CSS class name</small>
                        </div>
                    </div>

                    <!-- Sort Order -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $country->sort_order) }}" min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Lower numbers appear first</small>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $country->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                            <small class="form-text text-muted">Inactive countries won't be visible on the website</small>
                        </div>
                    </div>
                </div>

                <!-- Statistics (Read-only info) -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <strong>Statistics:</strong>
                            <ul class="mb-0">
                                <li>Total Destinations: {{ $country->destinations()->count() }}</li>
                                <li>Active Destinations: {{ $country->destinations()->where('is_active', true)->count() }}</li>
                                <li>Total Activities: {{ $country->activities()->count() }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Country
                    </button>
                    <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash"></i> Delete Country
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $country->name }}</strong>?</p>
                @if($country->destinations()->count() > 0)
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> This country has {{ $country->destinations()->count() }} destination(s). You cannot delete it until all destinations are removed.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.countries.destroy', $country) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" {{ $country->destinations()->count() > 0 ? 'disabled' : '' }}>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection