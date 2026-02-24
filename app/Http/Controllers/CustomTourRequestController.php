<?php

namespace App\Http\Controllers;
use App\Mail\CustomTourRequestConfirmation;
use App\Mail\CustomTourRequestAdminNotification;
use App\Models\CustomTourRequest;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\BudgetCategory;
use App\Models\AccommodationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
            'total'   => CustomTourRequest::count(),
            'new'     => CustomTourRequest::new()->count(),
            'pending' => CustomTourRequest::pending()->count(),
            'quoted'  => CustomTourRequest::byStatus(CustomTourRequest::STATUS_QUOTED)->count(),
            'booked'  => CustomTourRequest::byStatus(CustomTourRequest::STATUS_BOOKED)->count(),
        ];

        return view('admin.custom-tour-requests.index', compact('requests', 'statuses', 'countries', 'stats'));
    }

    /**
     * ADMIN: Show single custom tour request (Simplified version)
     */
    public function adminShow(CustomTourRequest $customTourRequest)
    {
        $title = 'Tour Request Details - ' . $customTourRequest->reference_number;

        // Pre-load destinations with countries
        if ($customTourRequest->destinations && is_array($customTourRequest->destinations)) {
            $destinations = Destination::whereIn('id', $customTourRequest->destinations)
                ->with('country')
                ->get();
            $customTourRequest->setRelation('destinations_details', $destinations);
        }

        // Pre-load activities with categories
        if ($customTourRequest->activities && is_array($customTourRequest->activities)) {
            $activities = Activity::whereIn('id', $customTourRequest->activities)
                ->when(class_exists('App\Models\ActivityCategory'), function ($query) {
                    return $query->with('category');
                })
                ->get();
            $customTourRequest->setRelation('activities_details', $activities);
        }

        // Load budget category if applicable
        if ($customTourRequest->budget_category && class_exists('App\Models\BudgetCategory')) {
            $budgetCategory = \App\Models\BudgetCategory::where('slug', $customTourRequest->budget_category)
                ->orWhere('name', $customTourRequest->budget_category)
                ->first();
            $customTourRequest->setRelation('budget_category_details', $budgetCategory);
        }

        // Get country details
        if ($customTourRequest->country) {
            $countryDetails = Country::where('name', $customTourRequest->country)->first();
            $customTourRequest->setRelation('country_relation', $countryDetails);
        }

        // Get similar requests for recommendations
        $similarRequests = CustomTourRequest::where('id', '!=', $customTourRequest->id)
            ->where(function ($query) use ($customTourRequest) {
                $query->where('country', $customTourRequest->country)
                      ->orWhere(function ($q) use ($customTourRequest) {
                          if ($customTourRequest->destinations && is_array($customTourRequest->destinations)) {
                              $q->whereJsonContains('destinations', $customTourRequest->destinations[0] ?? null);
                          }
                      });
            })
            ->where('status', '!=', CustomTourRequest::STATUS_CANCELLED)
            ->latest()
            ->limit(5)
            ->get();

        // Format admin notes for better display
        $formattedAdminNotes = $customTourRequest->formatted_admin_notes ?? [];

        return view('admin.custom-tour-requests.show', compact(
            'customTourRequest',
            'similarRequests',
            'formattedAdminNotes'
        ));
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
            'status'      => 'required|in:' . implode(',', array_keys(CustomTourRequest::getStatuses())),
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
            'ids'      => 'required|array',
            'ids.*'    => 'exists:custom_tour_requests,id',
            'status'   => 'required|in:' . implode(',', array_keys(CustomTourRequest::getStatuses())),
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
            'ids'   => 'required|array',
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
            'Content-Type'        => 'text/csv',
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
     * PUBLIC: Show custom tour request form (Multi-step wizard)
     */
    public function create()
    {
        // Get all active countries with their destinations
        $countries = Country::where('is_active', true)
                    ->with(['destinations' => function($query) {
                        $query->where('is_active', true)->orderBy('name');
                    }])
                    ->orderBy('name')
                    ->get();

        // Get all destinations for filtering
        $destinations = Destination::where('is_active', true)
                        ->with('country')
                        ->orderBy('name')
                        ->get();

        // Get all activities (not strictly needed in the wizard now, but kept for compatibility)
        $activities = Activity::where('is_active', true)
                    ->orderBy('name')
                    ->get();

        // Get activity categories WITH activities & destinations for filtering by chosen destinations
        $activityCategories = collect();
        if (class_exists('App\Models\ActivityCategory')) {
            $activityCategories = ActivityCategory::where('is_active', true)
                                ->with(['activities' => function($query) {
                                    $query->where('is_active', true)
                                          ->with('destinations') // <-- needed for Blade data-destinations
                                          ->orderBy('name');
                                }])
                                ->orderBy('name')
                                ->get();
        }

        // Get budget categories if they exist
        $budgetCategories = collect();
        if (class_exists('App\Models\BudgetCategory')) {
            $budgetCategories = BudgetCategory::where('is_active', true)
                            ->orderBy('sort_order')
                            ->get();
        }

        // Get accommodation types if they exist
        $accommodationTypes = collect();
        if (class_exists('App\Models\AccommodationType')) {
            $accommodationTypes = AccommodationType::where('is_active', true)
                                ->orderBy('sort_order')
                                ->get();
        }

        return view('custom-tour-requests.create', compact(
            'countries',
            'destinations',
            'activities',
            'activityCategories',
            'budgetCategories',
            'accommodationTypes'
        ));
    }

 
             /**
     * PUBLIC: Store custom tour request + send emails to client & admin
     */
    public function store(Request $request)
    {
        try {
            // ── Validate ───────────────────────────────────────────────────
            $validated = $request->validate([
                // Step 1: Personal Information
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255',
                'phone'    => 'nullable|string|max:50',
                'country'  => 'nullable|string|max:255',
                'language' => 'nullable|string|max:50',

                // Step 2: Travel Details
                'travel_date_from' => 'nullable|date|after_or_equal:today',
                'travel_date_to'   => 'nullable|date|after_or_equal:travel_date_from',
                'flexible_dates'   => 'nullable|boolean',
                'duration_days'    => 'nullable|integer|min:1|max:60',
                'adults_count'     => 'required|integer|min:1|max:50',
                'children_count'   => 'nullable|integer|min:0|max:50',
                'infants_count'    => 'nullable|integer|min:0|max:50',

                // Step 3: Destinations & Activities
                'destinations'   => 'nullable|array',
                'destinations.*' => 'exists:destinations,id',
                'activities'     => 'nullable|array',
                'activities.*'   => 'exists:activities,id',

                // Step 4: Preferences
                'budget_category'          => 'nullable|string|max:100',
                'approximate_budget'       => 'nullable|string|max:255',
                'accommodation_preference' => 'nullable|string|max:255',

                // Step 5: Special Requirements
                'special_requirements' => 'nullable|array',
                'dietary_restrictions' => 'nullable|string',
                'medical_conditions'   => 'nullable|string',
                'special_requests'     => 'nullable|string',
                'heard_from'           => 'nullable|string|max:255',
            ]);

            // ── Defaults ───────────────────────────────────────────────────
            $validated['flexible_dates']  = $request->has('flexible_dates');
            $validated['children_count']  = $validated['children_count'] ?? 0;
            $validated['infants_count']   = $validated['infants_count'] ?? 0;
            $validated['status']          = CustomTourRequest::STATUS_NEW;

            // Compute travel_date_to from start + duration if not set
            if (!empty($validated['travel_date_from']) && !empty($validated['duration_days'])) {
                $start = \Carbon\Carbon::parse($validated['travel_date_from']);
                $validated['travel_date_to'] = $start->copy()
                    ->addDays($validated['duration_days'] - 1)
                    ->toDateString();
            }

            if (!empty($validated['duration_days'])) {
                $validated['duration'] = $validated['duration_days'] . ' days';
            }

            // ── Create record ──────────────────────────────────────────────
            $tourRequest = CustomTourRequest::create($validated);

            Log::info('Custom tour request created', [
                'id'        => $tourRequest->id,
                'reference' => $tourRequest->reference_number,
                'email'     => $tourRequest->email,
            ]);

            // ── Send emails ────────────────────────────────────────────────
            try {
                // 1. Confirmation to the client
                Mail::to($tourRequest->email)
                    ->send(new CustomTourRequestConfirmation($tourRequest));

                Log::info('Custom tour confirmation sent to client', [
                    'email' => $tourRequest->email,
                    'ref'   => $tourRequest->reference_number,
                ]);

            } catch (\Exception $mailException) {
                // Log but don't block the user — their request is still saved
                Log::error('Failed to send client confirmation email', [
                    'error' => $mailException->getMessage(),
                    'email' => $tourRequest->email,
                ]);
            }

            try {
                // 2. Alert to the admin
                $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL', 'info@calmafricasafaris.com'));

                Mail::to($adminEmail)
                    ->send(new CustomTourRequestAdminNotification($tourRequest));

                Log::info('Custom tour admin notification sent', [
                    'admin' => $adminEmail,
                    'ref'   => $tourRequest->reference_number,
                ]);

            } catch (\Exception $mailException) {
                Log::error('Failed to send admin notification email', [
                    'error' => $mailException->getMessage(),
                ]);
            }

            // ── Redirect to success page ───────────────────────────────────
            return redirect()
                ->route('custom-tour-requests.success', $tourRequest->id)
                ->with('success', 'Your custom tour request has been submitted successfully!')
                ->with('reference_number', $tourRequest->reference_number);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for custom tour request', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Error creating custom tour request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()
                ->with('error', 'An error occurred while submitting your request. Please try again.')
                ->withInput();
        }
    }
    /**
     * PUBLIC: Success page after submission
     */
    public function success($id = null)
    {
        // If ID is provided, find the request
        if ($id) {
            $request = CustomTourRequest::findOrFail($id);
            return view('custom-tour-requests.success', compact('request'));
        }

        // Fallback to session data
        if (!session()->has('success')) {
            return redirect()->route('custom-tour-requests.create');
        }

        // Try to get request from session reference number
        $referenceNumber = session('reference_number');
        $request = null;
        
        if ($referenceNumber) {
            $id = ltrim(str_replace('CTR-', '', $referenceNumber), '0');
            $request = CustomTourRequest::find($id);
        }

        return view('custom-tour-requests.success', compact('request'));
    }

    /**
     * PUBLIC: Track request by reference number
     */
    public function track(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'reference_number' => 'required|string',
                'email'            => 'required|email',
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
     * API: Get destinations by country (AJAX)
     */
    public function getDestinationsByCountry($countryId)
    {
        $destinations = Destination::where('country_id', $countryId)
                                   ->where('is_active', true)
                                   ->orderBy('name')
                                   ->get(['id', 'name', 'slug', 'image', 'description', 'type']);

        return response()->json($destinations);
    }

    /**
     * API: Get activities by country (AJAX)
     */
    public function getActivitiesByCountry($countryId)
    {
        $activities = Activity::whereHas('destinations', function ($q) use ($countryId) {
                          $q->where('country_id', $countryId);
                      })
                      ->where('is_active', true)
                      ->orderBy('name')
                      ->get(['id', 'name', 'slug', 'icon', 'image', 'description']);

        return response()->json($activities);
    }

    /**
     * API: Get activities by category (AJAX)
     */
    public function getActivitiesByCategory($categoryId)
    {
        $activities = Activity::where('category_id', $categoryId)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get(['id', 'name', 'slug', 'icon', 'image', 'description', 'duration']);

        return response()->json($activities);
    }
}