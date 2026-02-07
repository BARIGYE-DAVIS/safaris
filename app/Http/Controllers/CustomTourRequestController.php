<?php

namespace App\Http\Controllers;

use App\Models\CustomTourRequest;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Activity;
use App\Models\BudgetCategory;
use App\Models\AccommodationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomTourRequestController extends Controller
{
    /**
     * ADMIN: Display a listing of custom tour requests
     */
    public function adminIndex(Request $request)
    {
        $query = CustomTourRequest::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        $requests = $query->latest()->paginate(15);
        $statuses = CustomTourRequest::getStatuses();
        $countries = Country::active()->ordered()->pluck('name', 'name');

        // Statistics
        $stats = [
            'total' => CustomTourRequest::count(),
            'new' => CustomTourRequest::new()->count(),
            'pending' => CustomTourRequest::pending()->count(),
            'quoted' => CustomTourRequest::byStatus(CustomTourRequest::STATUS_QUOTED)->count(),
            'booked' => CustomTourRequest::byStatus(CustomTourRequest::STATUS_BOOKED)->count(),
        ];

        return view('admin.custom-tour-requests.index', compact('requests', 'statuses', 'countries', 'stats'));
    }

    /**
     * ADMIN: Show single custom tour request
     */
    public function adminShow(CustomTourRequest $customTourRequest)
    {
        $customTourRequest->load(['destinationsDetails', 'activitiesDetails']);

        return view('admin.custom-tour-requests.show', compact('customTourRequest'));
    }

    /**
     * ADMIN: Show edit form
     */
    public function adminEdit(CustomTourRequest $customTourRequest)
    {
        $statuses = CustomTourRequest::getStatuses();

        return view('admin.custom-tour-requests.edit', compact('customTourRequest', 'statuses'));
    }

    /**
     * ADMIN: Update request details
     */
    public function adminUpdate(Request $request, CustomTourRequest $customTourRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(CustomTourRequest::getStatuses())),
            'admin_notes' => 'nullable|string',
        ]);

        $customTourRequest->update($validated);

        return redirect()->route('admin.custom-tour-requests.show', $customTourRequest)
                        ->with('success', 'Tour request updated successfully!');
    }

    /**
     * ADMIN: Update status
     */
    public function adminUpdateStatus(Request $request, CustomTourRequest $customTourRequest)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(CustomTourRequest::getStatuses())),
        ]);

        $customTourRequest->update(['status' => $request->status]);

        return back()->with('success', 'Status updated successfully!');
    }

    /**
     * ADMIN: Add admin notes
     */
    public function adminAddNote(Request $request, CustomTourRequest $customTourRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $existingNotes = $customTourRequest->admin_notes ?? '';
        $timestamp = now()->format('Y-m-d H:i:s');
        $newNote = "\n\n[{$timestamp}]\n" . $request->admin_notes;

        $customTourRequest->update([
            'admin_notes' => $existingNotes . $newNote
        ]);

        return back()->with('success', 'Note added successfully!');
    }

    /**
     * ADMIN: Delete request
     */
    public function adminDestroy(CustomTourRequest $customTourRequest)
    {
        $customTourRequest->delete();

        return redirect()->route('admin.custom-tour-requests.index')
                        ->with('success', 'Tour request deleted successfully!');
    }

    /**
     * ADMIN: Bulk update status
     */
    public function adminBulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:custom_tour_requests,id',
            'status' => 'required|in:' . implode(',', array_keys(CustomTourRequest::getStatuses())),
        ]);

        CustomTourRequest::whereIn('id', $request->ids)->update(['status' => $request->status]);

        return back()->with('success', 'Status updated for selected requests!');
    }

    /**
     * ADMIN: Bulk delete requests
     */
    public function adminBulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:custom_tour_requests,id'
        ]);

        CustomTourRequest::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected requests deleted successfully!');
    }

    /**
     * ADMIN: Export requests to CSV
     */
    public function adminExport(Request $request)
    {
        $query = CustomTourRequest::query();

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requests = $query->latest()->get();

        $filename = 'custom-tour-requests-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($requests) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Reference', 'Name', 'Email', 'Phone', 'Country', 'Travel Dates',
                'Duration', 'Travelers', 'Budget', 'Status', 'Created At'
            ]);

            // Data rows
            foreach ($requests as $request) {
                fputcsv($file, [
                    $request->reference_number,
                    $request->name,
                    $request->email,
                    $request->phone,
                    $request->country,
                    $request->travel_dates_formatted,
                    $request->duration,
                    $request->total_travelers,
                    $request->approximate_budget ?? $request->budget_category,
                    $request->status_label,
                    $request->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * PUBLIC: Show custom tour request form
     */
    public function create()
    {
        $countries = Country::active()->ordered()->get();
        $destinations = Destination::active()->ordered()->get();
        $activities = Activity::active()->ordered()->get();
        $budgetCategories = BudgetCategory::active()->ordered()->get();
        $accommodationTypes = AccommodationType::active()->ordered()->get();

        return view('custom-tour-requests.create', compact(
            'countries',
            'destinations',
            'activities',
            'budgetCategories',
            'accommodationTypes'
        ));
    }

    /**
     * PUBLIC: Store custom tour request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Personal Information
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'language' => 'nullable|string|max:50',

            // Travel Details
            'travel_date_from' => 'nullable|date|after_or_equal:today',
            'travel_date_to' => 'nullable|date|after_or_equal:travel_date_from',
            'flexible_dates' => 'boolean',
            'duration' => 'nullable|string|max:50',
            'adults_count' => 'required|integer|min:1|max:50',
            'children_count' => 'nullable|integer|min:0|max:50',
            'infants_count' => 'nullable|integer|min:0|max:50',

            // Tour Preferences
            'budget_category' => 'nullable|string|max:100',
            'destinations' => 'nullable|array',
            'destinations.*' => 'exists:destinations,id',
            'activities' => 'nullable|array',
            'activities.*' => 'exists:activities,id',
            'accommodation_preference' => 'nullable|string|max:255',

            // Special Requirements
            'special_requirements' => 'nullable|array',
            'dietary_restrictions' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'special_requests' => 'nullable|string',

            // Additional Info
            'heard_from' => 'nullable|string|max:255',
            'approximate_budget' => 'nullable|string|max:100',
        ]);

        $validated['flexible_dates'] = $request->has('flexible_dates');
        $validated['children_count'] = $validated['children_count'] ?? 0;
        $validated['infants_count'] = $validated['infants_count'] ?? 0;
        $validated['status'] = CustomTourRequest::STATUS_NEW;

        $tourRequest = CustomTourRequest::create($validated);

        // Send notification email to admin (optional)
        // Mail::to(config('mail.admin_email'))->send(new NewTourRequestNotification($tourRequest));

        // Send confirmation email to customer (optional)
        // Mail::to($tourRequest->email)->send(new TourRequestConfirmation($tourRequest));

        return redirect()->route('custom-tour-requests.success')
                        ->with('success', 'Your custom tour request has been submitted successfully!')
                        ->with('reference_number', $tourRequest->reference_number);
    }

    /**
     * PUBLIC: Success page after submission
     */
    public function success()
    {
        if (!session()->has('success')) {
            return redirect()->route('custom-tour-requests.create');
        }

        return view('custom-tour-requests.success');
    }

    /**
     * PUBLIC: Track request by reference number
     */
    public function track(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'reference_number' => 'required|string',
                'email' => 'required|email',
            ]);

            $referenceNumber = str_replace('CTR-', '', $request->reference_number);
            $id = ltrim($referenceNumber, '0');

            $tourRequest = CustomTourRequest::where('id', $id)
                                           ->where('email', $request->email)
                                           ->first();

            if (!$tourRequest) {
                return back()->withErrors(['error' => 'Request not found. Please check your reference number and email.']);
            }

            return view('custom-tour-requests.track', compact('tourRequest'));
        }

        return view('custom-tour-requests.track-form');
    }

    /**
     * API: Get destinations by country
     */
    public function getDestinationsByCountry($countryId)
    {
        $destinations = Destination::where('country_id', $countryId)
                                   ->active()
                                   ->ordered()
                                   ->get();

        return response()->json($destinations);
    }

    /**
     * API: Get activities by country
     */
    public function getActivitiesByCountry($countryId)
    {
        $activities = Activity::whereHas('countries', function ($q) use ($countryId) {
                          $q->where('countries.id', $countryId);
                      })
                      ->active()
                      ->ordered()
                      ->get();

        return response()->json($activities);
    }
}