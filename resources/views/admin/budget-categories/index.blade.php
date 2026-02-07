
@extends('layouts.admin')

@section('title', 'Edit Activity')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Activity: {{ $activity->name }}</h1>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
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

            <form action="{{ route('admin.activities.update', $activity) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Activity Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $activity->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">Select Category (Optional)</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $activity->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Slug -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $activity->slug) }}" placeholder="auto-generated if empty">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">URL-friendly version (leave empty to auto-generate)</small>
                        </div>
                    </div>

                    <!-- Sort Order -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $activity->sort_order) }}" min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Lower numbers appear first</small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $activity->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Brief description of the activity</small>
                        </div>
                    </div>

                    <!-- Countries -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Available in Countries</label>
                            <div class="row">
                                @foreach($countries as $country)
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="country_{{ $country->id }}" name="countries[]" value="{{ $country->id }}" {{ in_array($country->id, old('countries', $selectedCountries)) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="country_{{ $country->id }}">
                                            {{ $country->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">Select countries where this activity is available</small>
                        </div>
                    </div>

                    <!-- Current Icon -->
                    @if($activity->icon)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Current Icon</label>
                            <div>
                                <img src="{{ asset('storage/' . $activity->icon) }}" alt="{{ $activity->name }}" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Current Image -->
                    @if($activity->image)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Current Image</label>
                            <div>
                                <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->name }}" style="max-width: 300px; max-height: 200px;" class="img-thumbnail">
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Icon -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="icon">Activity Icon (Small) {{ $activity->icon ? '(Upload new to replace)' : '' }}</label>
                            <input type="file" name="icon" id="icon" class="form-control-file @error('icon') is-invalid @enderror" accept="image/*">
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Small icon/logo (Recommended: 100x100px, Max: 1MB)</small>
                            <div id="icon-preview" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Activity Image (Large) {{ $activity->image ? '(Upload new to replace)' : '' }}</label>
                            <input type="file" name="image" id="image" class="form-control-file @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Featured image (Recommended: 1200x800px, Max: 2MB)</small>
                            <div id="image-preview" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Popular -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_popular" name="is_popular" {{ old('is_popular', $activity->is_popular) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_popular">Mark as Popular</label>
                            </div>
                            <small class="form-text text-muted">Popular activities are featured prominently</small>
                        </div>
                    </div>

                    <!-- Active -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $activity->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                            <small class="form-text text-muted">Inactive activities won't be visible on the website</small>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Activity
                    </button>
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash"></i> Delete Activity
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
                <p>Are you sure you want to delete <strong>{{ $activity->name }}</strong>?</p>
                <p class="text-danger">This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate slug from name (only if slug is empty)
    document.getElementById('name').addEventListener('input', function() {
        let slugField = document.getElementById('slug');
        if (slugField.value === '' || slugField.dataset.autoGenerated === 'true') {
            let slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            slugField.value = slug;
            slugField.dataset.autoGenerated = 'true';
        }
    });

    // Icon preview
    document.getElementById('icon').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('icon-preview').innerHTML = 
                    '<div class="mt-2"><strong>New Icon Preview:</strong><br>' +
                    '<img src="' + e.target.result + '" style="max-width: 100px; max-height: 100px;" class="img-thumbnail"></div>';
            }
            reader.readAsDataURL(file);
        }
    });

    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').innerHTML = 
                    '<div class="mt-2"><strong>New Image Preview:</strong><br>' +
                    '<img src="' + e.target.result + '" style="max-width: 300px; max-height: 200px;" class="img-thumbnail"></div>';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection