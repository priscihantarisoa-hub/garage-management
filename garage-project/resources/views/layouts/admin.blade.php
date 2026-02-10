<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Garage Backoffice') - Administration</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom styles -->
    <style>
        :root {
            --primary: #54ACBF;
            --secondary: #26658C;
            --dark-bg: #0f1419;
            --card-bg: #1a2332;
            --sidebar-bg: #111827;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--dark-bg);
            color: #f3f4f6;
        }

        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-item {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar-item:hover {
            background: rgba(84, 172, 191, 0.1);
            color: var(--primary);
        }

        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(84, 172, 191, 0.15) 0%, rgba(38, 101, 140, 0.05) 100%);
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }

        .sidebar-item.active i {
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                box-shadow: 20px 0 50px rgba(0, 0, 0, 0.5);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        .glass-header {
            background: rgba(26, 35, 50, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .toast {
            animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(8px);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        .fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    </style>

    <!-- Scripts for exports -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    @stack('styles')
</head>

<body style="background-color: #0f1419;">

    <!-- Mobile menu button -->
    <div class="md:hidden fixed top-4 left-4 z-50">
        <button onclick="toggleSidebar()" style="background-color: #54ACBF;"
            class="text-white p-2 rounded-lg shadow-lg">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="sidebar sidebar-transition fixed left-0 top-0 h-full w-64 bg-gray-900 text-white z-40 md:translate-x-0">
        <div class="p-6 border-b border-gray-800">
            <div class="flex items-center space-x-3">
                <div style="background-color: #54ACBF;" class="p-2 rounded-lg">
                    <i class="fas fa-wrench text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Garage</h1>
                    <p class="text-xs text-gray-400">Backoffice Admin</p>
                </div>
            </div>
        </div>

        <nav class="mt-6">
            <div class="px-4">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-4">Menu Principal</p>

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg mb-2 {{ request()->routeIs('dashboard') ? 'active' : 'hover:bg-gray-800' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Interventions -->
                <a href="{{ route('interventions.index') }}"
                    class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg mb-2 {{ request()->routeIs('interventions.*') ? 'active' : 'hover:bg-gray-800' }}">
                    <i class="fas fa-tools w-5"></i>
                    <span>Interventions</span>
                </a>

                <!-- Réparations -->
                <a href="{{ route('admin.repairs.index') }}"
                    class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg mb-2 {{ request()->routeIs('admin.repairs.*') ? 'active' : 'hover:bg-gray-800' }}">
                    <i class="fas fa-car-wrench w-5"></i>
                    <span>Réparations</span>
                </a>

                <!-- Statistiques -->
                <a href="{{ route('dashboard') }}#stats"
                    class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg mb-2 hover:bg-gray-800">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span>Statistiques</span>
                </a>
            </div>

            <div class="px-4 mt-8 border-t border-gray-800 pt-6">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-4">Système</p>

                <!-- Déconnexion -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg mb-2 w-full text-left hover:bg-red-600">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- User info -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-800">
            <div class="flex items-center space-x-3">
                <div class="bg-gray-700 p-2 rounded-full">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium">Administrateur</p>
                    <p class="text-xs text-gray-400">admin@garage.com</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="md:ml-64 min-h-screen">
        <!-- Header -->
        <header style="background-color: #1a2332; border-bottom: 1px solid #2d3f54;">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold" style="color: #ffffff;">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm" style="color: #a0a0a0;">@yield('page-description', 'Gestion du garage')</p>
                    </div>

                    <!-- Header actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Firebase status indicator -->
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm" style="color: #a0a0a0;">Firebase OK</span>
                        </div>

                        <!-- Time -->
                        <div class="text-sm" style="color: #a0a0a0;">
                            <i class="far fa-clock mr-1"></i>
                            {{ now()->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="p-4 sm:p-6 lg:p-8" style="background-color: #0f1419;">
            @include('partials.flash-messages')
            @yield('content')
        </main>
    </div>

    <!-- Toast container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- JavaScript -->
    <script>
        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function (event) {
            const sidebar = document.getElementById('sidebar');
            const menuButton = event.target.closest('button');

            if (window.innerWidth < 768 &&
                !sidebar.contains(event.target) &&
                !menuButton &&
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });

        // Toast notification system
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };

            const colorMap = {
                success: '#10b981',
                error: '#ef4444',
                warning: '#eab308',
                info: '#54ACBF'
            };

            toast.className = `toast flex items-center space-x-3 text-white px-4 py-3 rounded-lg shadow-lg`;
            toast.style.backgroundColor = colorMap[type];
            toast.innerHTML = `
                <i class="fas ${icons[type]}"></i>
                <span>${message}</span>
                <button onclick="removeToast(this)" class="ml-4 hover:opacity-75">
                    <i class="fas fa-times"></i>
                </button>
            `;

            container.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                const btn = toast.querySelector('button');
                if (btn) removeToast(btn);
            }, 5000);
        }

        function removeToast(button) {
            const toast = button.closest('.toast');
            if (!toast) return;
            toast.classList.add('fade-out');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }

        // Auto-refresh for dashboard
        @if(request()->routeIs('dashboard'))
            setInterval(() => {
                if (document.visibilityState === 'visible') {
                    window.location.reload();
                }
            }, 30000);
        @endif

        // Initialize tooltips and other UI elements
        document.addEventListener('DOMContentLoaded', function () {
            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>