@extends('layouts.admin')

@section('title', 'Admin Dashboard - Safari Tours')

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .notification-badge {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="p-6 lg:p-8">
    <!-- Welcome Banner -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-tachometer-alt text-indigo-600 mr-2"></i>
            Welcome back, Admin!
        </h1>
        <p class="text-gray-600">Here's what's happening with your safari business today.</p>
    </div>

    <!-- Quick Stats Bar -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Bookings -->
        <div class="stat-card bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Bookings</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalBookings) }}</h3>
                    <p class="text-sm mt-2">
                        <span class="text-{{ $bookingsChange >= 0 ? 'green' : 'red' }}-600 font-semibold">
                            <i class="fas fa-{{ $bookingsChange >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ number_format(abs($bookingsChange), 1) }}%
                        </span>
                        <span class="text-gray-500 ml-1">vs last month</span>
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="stat-card bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Revenue</p>
                    <h3 class="text-3xl font-bold text-gray-800">${{ number_format($totalRevenue, 2) }}</h3>
                    <p class="text-sm mt-2 text-gray-500">This month</p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="stat-card bg-white rounded-lg shadow-md p-6 cursor-pointer" onclick="window.location.href='#pending-bookings'">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Pending Approvals</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $pendingApprovals }}</h3>
                    <p class="text-sm mt-2 text-orange-600 font-medium">
                        <i class="fas fa-exclamation-circle"></i> Needs attention
                    </p>
                </div>
                <div class="bg-orange-100 rounded-full p-4 relative">
                    <i class="fas fa-clock text-orange-600 text-2xl"></i>
                    @if($pendingApprovals > 0)
                    <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center">
                        {{ $pendingApprovals }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Active Tours -->
        <div class="stat-card bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Active Tours</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $activeTours }}</h3>
                    <p class="text-sm mt-2 text-gray-500">Published packages</p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-route text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Chart (Takes 2 columns) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-chart-line text-indigo-600 mr-2"></i>
                    Revenue Overview
                </h2>
                <select id="revenueFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="month">Last 12 Months</option>
                    <option value="week">Last 12 Weeks</option>
                    <option value="day">Last 30 Days</option>
                </select>
            </div>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Notifications Panel -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-bell text-indigo-600 mr-2"></i>
                Notifications
            </h2>
            <div class="space-y-4 max-h-80 overflow-y-auto">
                @forelse($notifications as $notification)
                <div class="flex items-start space-x-3 p-3 rounded-lg bg-{{ $notification['type'] == 'success' ? 'green' : ($notification['type'] == 'warning' ? 'yellow' : 'blue') }}-50 border border-{{ $notification['type'] == 'success' ? 'green' : ($notification['type'] == 'warning' ? 'yellow' : 'blue') }}-200">
                    <div class="flex-shrink-0">
                        <i class="fas fa-{{ $notification['icon'] }} text-{{ $notification['type'] == 'success' ? 'green' : ($notification['type'] == 'warning' ? 'yellow' : 'blue') }}-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">{{ $notification['message'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notification['time'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-check-circle text-4xl mb-2"></i>
                    <p>All caught up!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Bookings Overview Chart & Popular Tours -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Bookings Status Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-pie text-indigo-600 mr-2"></i>
                Bookings by Status
            </h2>
            <div class="chart-container">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>

        <!-- Popular Safari Packages -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-star text-indigo-600 mr-2"></i>
                Top 5 Safari Packages
            </h2>
            <div class="space-y-4">
                @forelse($popularTours as $index => $tour)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white font-bold text-sm">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p class="font-semibold text-gray-800">{{ Str::limit($tour->title, 30) }}</p>
                            <p class="text-xs text-gray-500">{{ $tour->bookings_count }} booking(s)</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-600">${{ number_format($tour->total_revenue, 2) }}</p>
                        <p class="text-xs text-gray-500">Revenue</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">No tours yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-list text-indigo-600 mr-2"></i>
                Recent Bookings
            </h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentBookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $booking->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $booking->tour ? Str::limit($booking->tour->title, 30) : 'Custom Safari' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $booking->travel_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            ${{ number_format($booking->total_cost ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status_badge }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="text-blue-600 hover:text-blue-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No bookings yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Actions Section -->
    @if($pendingBookings->count() > 0)
    <div id="pending-bookings" class="bg-white rounded-lg shadow-md p-6 mb-8 border-l-4 border-orange-500">
        <div class="flex items-center mb-4">
            <div class="bg-orange-100 rounded-full p-3 mr-4">
                <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Pending Approvals</h2>
                <p class="text-sm text-gray-600">{{ $pendingBookings->count() }} booking(s) need your attention</p>
            </div>
        </div>
        <div class="space-y-3">
            @foreach($pendingBookings as $booking)
            <div class="flex items-center justify-between p-4 bg-orange-50 rounded-lg border border-orange-200">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $booking->name }} - {{ $booking->tour ? $booking->tour->title : 'Custom Safari' }}</p>
                    <p class="text-sm text-gray-600">Travel Date: {{ $booking->travel_date->format('M d, Y') }} | Amount: ${{ number_format($booking->total_cost ?? 0, 2) }}</p>
                </div>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                        <i class="fas fa-check mr-1"></i> Approve
                    </button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                        <i class="fas fa-times mr-1"></i> Reject
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Bottom Section: Upcoming Safaris & Top Customers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Upcoming Safaris Calendar -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt text-indigo-600 mr-2"></i>
                Upcoming Safaris (Next 30 Days)
            </h2>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($upcomingSafaris as $date => $bookings)
                <div class="border-l-4 border-indigo-600 pl-4 py-2">
                    <p class="font-semibold text-gray-800 mb-2">
                        <i class="fas fa-calendar-day text-indigo-600 mr-2"></i>
                        {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                    </p>
                    @foreach($bookings as $booking)
                    <div class="ml-4 mb-2 text-sm">
                        <p class="text-gray-700">
                            <i class="fas fa-user text-gray-400 mr-1"></i>
                            {{ $booking->name }} - {{ $booking->tour ? Str::limit($booking->tour->title, 25) : 'Custom' }}
                        </p>
                        <p class="text-gray-500 text-xs">Group: {{ $booking->group_size }} people</p>
                    </div>
                    @endforeach
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-calendar-times text-4xl mb-2"></i>
                    <p>No upcoming safaris</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Top Customers & System Status -->
        <div class="space-y-6">
            <!-- Top Customers -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-trophy text-indigo-600 mr-2"></i>
                    Top Customers
                </h2>
                <div class="space-y-3">
                    @forelse($topCustomers as $customer)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="bg-indigo-600 text-white rounded-full h-10 w-10 flex items-center justify-center font-bold">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $customer->booking_count }} booking(s)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">${{ number_format($customer->total_spent, 2) }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">No customers yet</p>
                    @endforelse
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-server text-indigo-600 mr-2"></i>
                    System Status
                </h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-database text-gray-600"></i>
                            <span class="text-sm text-gray-700">Database</span>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $systemStatus['database'] == 'Connected' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $systemStatus['database'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-hdd text-gray-600"></i>
                            <span class="text-sm text-gray-700">Storage Usage</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">{{ $systemStatus['storage'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-shield-alt text-gray-600"></i>
                            <span class="text-sm text-gray-700">Last Backup</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">{{ $systemStatus['last_backup'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Toolbar (Floating) -->
    <div class="fixed bottom-6 right-6 space-y-3">
     
        <button onclick="window.location.href='{{ route('admin.tours.create') }}'" 
                class="flex items-center justify-center w-14 h-14 bg-green-600 text-white rounded-full shadow-lg hover:bg-green-700 transition transform hover:scale-110"
                title="Add New Tour">
            <i class="fas fa-route text-xl"></i>
        </button>
        <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="flex items-center justify-center w-14 h-14 bg-gray-600 text-white rounded-full shadow-lg hover:bg-gray-700 transition transform hover:scale-110"
                title="Scroll to Top">
            <i class="fas fa-arrow-up text-xl"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($revenueData);
    
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(d => d.month),
            datasets: [{
                label: 'Revenue ($)',
                data: revenueData.map(d => d.revenue),
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: $' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Bookings by Status Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    const bookingsByStatus = @json($bookingsByStatus);
    
    const bookingsChart = new Chart(bookingsCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(bookingsByStatus).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            datasets: [{
                data: Object.values(bookingsByStatus),
                backgroundColor: [
                    'rgba(251, 191, 36, 0.8)',  // pending - yellow
                    'rgba(34, 197, 94, 0.8)',   // confirmed - green
                    'rgba(239, 68, 68, 0.8)',   // cancelled - red
                    'rgba(59, 130, 246, 0.8)'   // completed - blue
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Revenue Filter Change
    document.getElementById('revenueFilter').addEventListener('change', function(e) {
        const period = e.target.value;
        // AJAX call to fetch new data
        fetch(`/admin/dashboard/revenue-stats?period=${period}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update chart with new data
                    revenueChart.data.labels = data.data.map(d => d.month || d.week || d.date);
                    revenueChart.data.datasets[0].data = data.data.map(d => d.revenue);
                    revenueChart.update();
                }
            })
            .catch(error => console.error('Error:', error));
    });
});
</script>
@endpush