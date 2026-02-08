@extends('layouts.admin')

@section('title', 'Edit Tour Request')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Tour Request: {{ $customTourRequest->reference_number }}</h1>
        <div>
            <a href="{{ route('admin.custom-tour-requests.show', $customTourRequest) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> View Details
            </a>
            <a href="{{ route('admin.custom-tour-requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
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

            <form action="{{ route('admin.custom-tour-requests.update', $customTourRequest) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Request Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                @foreach(\App\Models\CustomTourRequest::getStatuses() as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $customTourRequest->status) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Current Status Display -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Current Status</label>
                            <div>
                                <span class="badge badge-{{ $customTourRequest->status_color }} p-2" style="font-size: 1rem;">
                                    {{ $customTourRequest->status_label }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="admin_notes">Admin Notes</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control @error('admin_notes') is-invalid @enderror" rows="8">{{ old('admin_notes', $customTourRequest->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Internal notes visible only to administrators</small>
                        </div>
                    </div>
                </div>

                <!-- Customer Information (Read-only) -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">Customer Information (Read-only)</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->email }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->phone }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->country }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Travel Information (Read-only) -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">Travel Information (Read-only)</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Travel Dates</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->travel_dates_formatted }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Duration</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->duration ?? 'Not specified' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Adults</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->adults_count }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Children</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->children_count ?? 0 }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Infants</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->infants_count ?? 0 }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Budget Category</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->budget_category ?? 'Not specified' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Approximate Budget</label>
                            <input type="text" class="form-control" value="{{ $customTourRequest->approximate_budget ?? 'Not specified' }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Preferences (Read-only) -->
                @if($customTourRequest->destinationsDetails && $customTourRequest->destinationsDetails->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Preferred Destinations</label>
                            <div>
                                @foreach($customTourRequest->destinationsDetails as $destination)
                                    <span class="badge badge-primary mr-1">{{ $destination->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($customTourRequest->activitiesDetails && $customTourRequest->activitiesDetails->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Interested Activities</label>
                            <div>
                                @foreach($customTourRequest->activitiesDetails as $activity)
                                    <span class="badge badge-info mr-1">{{ $activity->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit Buttons -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Tour Request
                    </button>
                    <a href="{{ route('admin.custom-tour-requests.show', $customTourRequest) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash"></i> Delete Request
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
                <p>Are you sure you want to delete this tour request?</p>
                <p><strong>Reference:</strong> {{ $customTourRequest->reference_number }}</p>
                <p><strong>Customer:</strong> {{ $customTourRequest->name }}</p>
                <p class="text-danger">This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.custom-tour-requests.destroy', $customTourRequest) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection