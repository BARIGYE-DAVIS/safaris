@extends('layouts.admin')

@section('title', 'Edit Budget Category')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Budget Category: {{ $budgetCategory->name }}</h1>
        <a href="{{ route('admin.budget-categories.index') }}" class="btn btn-secondary">
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

            <form action="{{ route('admin.budget-categories.update', $budgetCategory) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $budgetCategory->name) }}" required placeholder="e.g., Budget, Mid-Range, Luxury">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Slug -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $budgetCategory->slug) }}" placeholder="auto-generated if empty">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">URL-friendly version (leave empty to auto-generate)</small>
                        </div>
                    </div>

                    <!-- Price Range Min -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="price_range_min">Minimum Price</label>
                            <input type="number" name="price_range_min" id="price_range_min" class="form-control @error('price_range_min') is-invalid @enderror" value="{{ old('price_range_min', $budgetCategory->price_range_min) }}" min="0" step="0.01" placeholder="e.g., 100">
                            @error('price_range_min')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Minimum budget per person per day</small>
                        </div>
                    </div>

                    <!-- Price Range Max -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="price_range_max">Maximum Price</label>
                            <input type="number" name="price_range_max" id="price_range_max" class="form-control @error('price_range_max') is-invalid @enderror" value="{{ old('price_range_max', $budgetCategory->price_range_max) }}" min="0" step="0.01" placeholder="e.g., 500">
                            @error('price_range_max')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maximum budget per person per day</small>
                        </div>
                    </div>

                    <!-- Currency -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <select name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror">
                                <option value="USD" {{ old('currency', $budgetCategory->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ old('currency', $budgetCategory->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ old('currency', $budgetCategory->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="KES" {{ old('currency', $budgetCategory->currency) == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                                <option value="TZS" {{ old('currency', $budgetCategory->currency) == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                                <option value="UGX" {{ old('currency', $budgetCategory->currency) == 'UGX' ? 'selected' : '' }}>UGX - Ugandan Shilling</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Icon -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="icon">Icon Class</label>
                            <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $budgetCategory->icon) }}" placeholder="e.g., fas fa-dollar-sign">
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Font Awesome icon class (e.g., fas fa-dollar-sign)</small>
                        </div>
                    </div>

                    <!-- Sort Order -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $budgetCategory->sort_order) }}" min="0">
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
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $budgetCategory->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Brief description of this budget category</small>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Features</label>
                            <div id="features-container">
                                @php
                                    $features = old('features', $budgetCategory->features ?? []);
                                @endphp
                                @if(is_array($features) && count($features) > 0)
                                    @foreach($features as $feature)
                                    <div class="input-group mb-2 feature-item">
                                        <input type="text" name="features[]" class="form-control" value="{{ $feature }}" placeholder="e.g., Shared accommodation">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-danger remove-feature">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                <div class="input-group mb-2 feature-item">
                                    <input type="text" name="features[]" class="form-control" placeholder="e.g., Shared accommodation">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger remove-feature">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary" id="add-feature">
                                <i class="fas fa-plus"></i> Add Feature
                            </button>
                            <small class="form-text text-muted">List features included in this budget category</small>
                        </div>
                    </div>

                    <!-- Active -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $budgetCategory->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                            <small class="form-text text-muted">Inactive budget categories won't be visible on the website</small>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Budget Category
                    </button>
                    <a href="{{ route('admin.budget-categories.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash"></i> Delete Budget Category
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
                <p>Are you sure you want to delete <strong>{{ $budgetCategory->name }}</strong>?</p>
                <p class="text-danger">This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.budget-categories.destroy', $budgetCategory) }}" method="POST" style="display: inline;">
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

    // Add feature
    document.getElementById('add-feature').addEventListener('click', function() {
        const container = document.getElementById('features-container');
        const newFeature = document.createElement('div');
        newFeature.className = 'input-group mb-2 feature-item';
        newFeature.innerHTML = `
            <input type="text" name="features[]" class="form-control" placeholder="e.g., Private transportation">
            <div class="input-group-append">
                <button type="button" class="btn btn-danger remove-feature">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newFeature);
        attachRemoveHandler(newFeature.querySelector('.remove-feature'));
    });

    // Remove feature
    function attachRemoveHandler(button) {
        button.addEventListener('click', function() {
            this.closest('.feature-item').remove();
        });
    }

    // Attach remove handler to existing features
    document.querySelectorAll('.remove-feature').forEach(attachRemoveHandler);
</script>
@endpush
@endsection