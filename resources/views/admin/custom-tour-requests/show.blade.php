@extends('layouts.admin')

@section('title', 'Tour Request Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Tour Request: {{ $customTourRequest->reference_number }}</h1>
        <div>
            <a href="{{ route('admin.custom-tour-requests.edit', $customTourRequest) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.custom-tour-requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Main Details -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $customTourRequest->name }}</p>
                            <p><strong>Email:</strong> <a href="mailto:{{ $customTourRequest->email }}">{{ $customTourRequest->email }}</a></p>
                            <p><strong>Phone:</strong> <a href="tel:{{ $customTourRequest->phone }}">{{ $customTourRequest->phone }}</a></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Country:</strong> {{ $customTourRequest->country }}</p>
                            <p><strong>Language:</strong> {{ $customTourRequest->language ?? 'Not specified' }}</p>
                            <p><strong>Heard From:</strong> {{ $customTourRequest->heard_from ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Travel Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Travel Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Travel Dates:</strong> 
                                @if($customTourRequest->travel_date_from && $customTourRequest->travel_date_to)
                                    {{ $customTourRequest->travel_date_from->format('M d, Y') }} - {{ $customTourRequest->travel_date_to->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                            <p><strong>Flexible Dates:</strong> 
                                <span class="badge badge-{{ $customTourRequest->flexible_dates ? 'success' : 'secondary' }}">
                                    {{ $customTourRequest->flexible_dates ? 'Yes' : 'No' }}
                                </span>
                            </p>
                            <p><strong>Duration:</strong> {{ $customTourRequest->duration ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Adults:</strong> {{ $customTourRequest->adults_count }}</p>
                            <p><strong>Children:</strong> {{ $customTourRequest->children_count ?? 0 }}</p>
                            <p><strong>Infants:</strong> {{ $customTourRequest->infants_count ?? 0 }}</p>
                            <p><strong>Total Travelers:</strong> <strong>{{ $customTourRequest->total_travelers }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tour Preferences -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tour Preferences</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Budget Category:</strong> {{ $customTourRequest->budget_category ?? 'Not specified' }}</p>
                            <p><strong>Approximate Budget:</strong> {{ $customTourRequest->approximate_budget ?? 'Not specified' }}</p>
                            <p><strong>Accommodation Preference:</strong> {{ $customTourRequest->accommodation_preference ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    <!-- Destinations -->
                    @if($customTourRequest->destinationsDetails && $customTourRequest->destinationsDetails->count() > 0)
                    <div class="mt-3">
                        <strong>Preferred Destinations:</strong>
                        <div class="mt-2">
                            @foreach($customTourRequest->destinationsDetails as $destination)
                                <span class="badge badge-primary mr-1">{{ $destination->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Activities -->
                    @if($customTourRequest->activitiesDetails && $customTourRequest->activitiesDetails->count() > 0)
                    <div class="mt-3">
                        <strong>Interested Activities:</strong>
                        <div class="mt-2">
                            @foreach($customTourRequest->activitiesDetails as $activity)
                                <span class="badge badge-info mr-1">{{ $activity->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Special Requirements -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Special Requirements</h6>
                </div>
                <div class="card-body">
                    @if($customTourRequest->special_requirements && is_array($customTourRequest->special_requirements) && count($customTourRequest->special_requirements) > 0)
                    <p><strong>Requirements:</strong></p>
                    <ul>
                        @foreach($customTourRequest->special_requirements as $requirement)
                            <li>{{ $requirement }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if($customTourRequest->dietary_restrictions)
                    <p><strong>Dietary Restrictions:</strong><br>{{ $customTourRequest->dietary_restrictions }}</p>
                    @endif

                    @if($customTourRequest->medical_conditions)
                    <p><strong>Medical Conditions:</strong><br>{{ $customTourRequest->medical_conditions }}</p>
                    @endif

                    @if($customTourRequest->special_requests)
                    <p><strong>Special Requests:</strong><br>{{ $customTourRequest->special_requests }}</p>
                    @endif

                    @if(!$customTourRequest->special_requirements && !$customTourRequest->dietary_restrictions && !$customTourRequest->medical_conditions && !$customTourRequest->special_requests)
                    <p class="text-muted">No special requirements specified</p>
                    @endif
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Admin Notes</h6>
                </div>
                <div class="card-body">
                    @if($customTourRequest->admin_notes)
                    <div class="alert alert-info">
                        {!! nl2br(e($customTourRequest->admin_notes)) !!}
                    </div>
                    @else
                    <p class="text-muted">No admin notes yet</p>
                    @endif

                    <!-- Add Note Form -->
                    <form action="{{ route('admin.custom-tour-requests.add-note', $customTourRequest) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="admin_notes">Add Note</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Note
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Request Status</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="badge badge-{{ $customTourRequest->status_color }} p-3" style="font-size: 1.2rem;">
                            {{ $customTourRequest->status_label }}
                        </span>
                    </div>

                    <!-- Update Status Form -->
                    <form action="{{ route('admin.custom-tour-requests.update-status', $customTourRequest) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="status">Update Status</label>
                            <select name="status" id="status" class="form-control" required>
                                @foreach(\App\Models\CustomTourRequest::getStatuses() as $key => $label)
                                    <option value="{{ $key }}" {{ $customTourRequest->status == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sync"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Request Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Request Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Reference:</strong><br>{{ $customTourRequest->reference_number }}</p>
                    <p><strong>Created:</strong><br>{{ $customTourRequest->created_at->format('M d, Y H:i A') }}</p>
                    <p><strong>Last Updated:</strong><br>{{ $customTourRequest->updated_at->format('M d, Y H:i A') }}</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="mailto:{{ $customTourRequest->email }}" class="btn btn-info btn-block">
                        <i class="fas fa-envelope"></i> Send Email
                    </a>
                    <a href="tel:{{ $customTourRequest->phone }}" class="btn btn-success btn-block">
                        <i class="fas fa-phone"></i> Call Customer
                    </a>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash"></i> Delete Request
                    </button>
                </div>
            </div>
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