<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        .sidebar-group-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem 0.5rem;
            color: #a5b4fc;
            font-weight: 600;
        }
        .sidebar-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.1);
            margin: 0.5rem 1.5rem;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Topbar for ALL screens -->
    <nav class="bg-indigo-700 py-4 px-8 flex justify-between items-center shadow">
        <button id="open-sidebar" class="text-white text-2xl">
            <i class="fas fa-bars"></i>
        </button>
        <span class="text-white font-semibold text-xl">Admin Panel</span>
    </nav>

    <div>
        <!-- Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black bg-opacity-40 hidden z-30"></div>
        
        <!-- Sidebar (hidden by default on all screens) -->
        <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-indigo-800 text-white flex flex-col z-40 transform -translate-x-full transition-transform duration-300 overflow-y-auto">
            <div class="py-5 px-6 flex items-center justify-between bg-indigo-900">
                <span class="font-semibold text-lg"><i class="fas fa-crown mr-2"></i>Admin Panel</span>
                <button id="close-sidebar" class="text-white text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="flex-1 pb-20">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}">
                    <div class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </div>
                </a>

                <div class="sidebar-divider"></div>

                <!-- Core Management -->
                <div class="sidebar-group-title">Core Management</div>
                
                <a href="{{ route('admin.tours.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-route"></i>
                    Tours
                </a>
                <a href="{{ route('admin.tours.create') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-plus-circle"></i>
                    Create Tour
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-calendar-check"></i>
                    Bookings
                </a>
                <a href="{{ route('admin.custom-tour-requests.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-paper-plane"></i>
                    Custom Tour Requests
                </a>

                <div class="sidebar-divider"></div>

                <!-- Location Management -->
                <div class="sidebar-group-title">Location Management</div>
                
                <a href="{{ route('admin.countries.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-flag"></i>
                    Countries
                </a>
                <a href="{{ route('admin.destinations.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-map-marker-alt"></i>
                    Destinations
                </a>

                <div class="sidebar-divider"></div>

                <!-- Activity Management -->
                <div class="sidebar-group-title">Activity Management</div>
                
                <a href="{{ route('admin.activity-categories.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-th-large"></i>
                    Activity Categories
                </a>
                <a href="{{ route('admin.activities.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-hiking"></i>
                    Activities
                </a>

                <div class="sidebar-divider"></div>

                <!-- Configuration -->
                <div class="sidebar-group-title">Configuration</div>
                
                <a href="{{ route('admin.budget-categories.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-dollar-sign"></i>
                    Budget Categories
                </a>
                <a href="{{ route('admin.accommodation-types.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-hotel"></i>
                    Accommodation Types
                </a>

                <div class="sidebar-divider"></div>

                <!-- Media & Communication -->
                <div class="sidebar-group-title">Media & Communication</div>
                
                <a href="{{ route('admin.gallery.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-images"></i>
                    Gallery
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-envelope"></i>
                    Contact Messages
                </a>

                <a href="{{ route('admin.emails.compose') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-paper-plane"></i>
                    Compose Email

                </a>

                <a href="{{ route('admin.blogs.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-blog"></i>
                    Blogs
                </a>

                <a href="{{ route('admin.blog-categories.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-tags"></i>
                    Blog Categories

                </a>

                <a href="{{ route('admin.accommodations.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-bed"></i>
                    Accommodations
                </a>

                <a href="{{ route('admin.subscribers.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-users"></i>
                    Subscribers
                </a>

                <div class="sidebar-divider"></div>
                        
                <form method="POST" action="{{ route('admin.logout') }}" class="m-0 mt-4">
                    @csrf
                    <button class="w-full px-6 py-3 text-left bg-indigo-900 hover:bg-indigo-700 flex items-center gap-3 sidebar-link" type="submit">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8" style="min-height:100vh;">
            @yield('content')
        </main>
    </div>

    {{-- Responsive Sidebar Script --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
        const openSidebarBtn = document.getElementById('open-sidebar');
        const closeSidebarBtn = document.getElementById('close-sidebar');
        const sidebarLinks = document.querySelectorAll('.sidebar-link');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarBackdrop.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarBackdrop.classList.add('hidden');
        }

        if (openSidebarBtn) openSidebarBtn.addEventListener('click', openSidebar);
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
        if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);

        // Auto-close sidebar when a link is clicked
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                closeSidebar();
            });
        });
    </script>

    @stack('scripts')
</body>
</html>