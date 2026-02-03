<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Topbar for small screens -->
    <nav class="bg-indigo-700 py-4 px-8 flex justify-between items-center shadow lg:hidden">
        <button id="open-sidebar" class="text-white text-2xl">
            <i class="fas fa-bars"></i>
        </button>
        <span class="text-white font-semibold text-xl">Admin Panel</span>
    </nav>

    <div class="lg:flex">
        <!-- Sidebar -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black bg-opacity-40 hidden z-30"></div>
        <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-indigo-800 text-white flex flex-col z-40 transform -translate-x-full transition-transform duration-300 lg:relative lg:translate-x-0 lg:static lg:block">
            <div class="py-5 px-6 flex items-center justify-between bg-indigo-900">
                <span class="font-semibold text-lg"><i class="fas fa-crown mr-2"></i>Admin Panel</span>
                <button id="close-sidebar" class="lg:hidden text-white text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="flex-1">
                <a href="{{ route('admin.tours.index') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-route"></i>
                    Tours
                </a>
                <a href="{{ route('admin.tours.create') }}" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-plus-circle"></i>
                    Create Tour
                </a>
                {{-- Example: Itineraries, Prices, Images admin views if you have them --}}
                <a href="#" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-calendar-day"></i>
                    Itineraries
                </a>
                <a href="#" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-tags"></i>
                    Prices
                </a>
                <a href="#" class="block py-3 px-6 hover:bg-indigo-700 flex items-center gap-3 sidebar-link">
                    <i class="fas fa-image"></i>
                    Images
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" class="m-0 mt-auto">
                    @csrf
                    <button class="w-full px-6 py-3 text-left bg-indigo-900 hover:bg-indigo-700 flex items-center gap-3 sidebar-link" type="submit">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8 lg:ml-0" style="min-height:100vh;">
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

        // Auto-close sidebar when a link is clicked, on mobile
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if(window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        // Optionally, auto-close on window resize (mobile to desktop)
        window.addEventListener('resize', () => {
            if(window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebarBackdrop.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>
</html>