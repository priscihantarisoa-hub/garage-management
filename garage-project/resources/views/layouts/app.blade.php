<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Garage Backoffice')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Responsive avancé */
        @media (max-width: 640px) {
            .mobile-menu {
                display: none;
            }
            .mobile-menu.active {
                display: block;
            }
            .desktop-only {
                display: none !important;
            }
            .mobile-only {
                display: block !important;
            }
            .nav-mobile {
                flex-direction: column;
                height: auto !important;
            }
            .nav-mobile .flex {
                flex-direction: column;
                width: 100%;
            }
        }
        
        @media (min-width: 641px) {
            .mobile-only {
                display: none !important;
            }
        }
        
        @media (max-width: 768px) {
            .tablet-hidden {
                display: none !important;
            }
        }
        
        /* Performance optimisations */
        .nav-link {
            transition: all 0.2s ease;
        }
        
        /* Touch optimisations */
        @media (hover: none) {
            .nav-link {
                padding: 12px !important;
            }
        }
    </style>
</head>
<body style="background-color: #0f1419;">
    @if(session('authenticated'))
        <nav style="background-color: #54ACBF;" class="text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Mobile menu button -->
                <div class="mobile-only">
                    <div class="flex justify-between items-center py-4">
                        <div class="flex items-center">
                            <i class="fas fa-wrench mr-3 text-xl"></i>
                            <h1 class="text-lg font-bold">Garage</h1>
                        </div>
                        <button onclick="toggleMobileMenu()" style="background-color: transparent;" class="p-2 rounded hover:opacity-80">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                    <div id="mobileMenu" class="mobile-menu pb-4">
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('dashboard') }}" class="nav-link block px-4 py-2 rounded" style="background-color: rgba(255,255,255,0.1);">
                                <i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord
                            </a>
                            <a href="{{ route('interventions.index') }}" class="nav-link block px-4 py-2 rounded" style="background-color: rgba(255,255,255,0.1);">
                                <i class="fas fa-tools mr-2"></i>Interventions
                            </a>
                            <a href="{{ route('admin.repairs.index') }}" class="nav-link block px-4 py-2 rounded" style="background-color: rgba(255,255,255,0.1);">
                                <i class="fas fa-car-side mr-2"></i>Réparations
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                <button type="submit" class="nav-link block w-full text-left px-4 py-2 rounded" style="background-color: rgba(255,255,255,0.1);">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Desktop menu -->
                <div class="desktop-only nav-mobile">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <i class="fas fa-wrench mr-3 text-xl"></i>
                            <h1 class="text-xl font-bold">Garage Backoffice</h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('dashboard') }}" class="nav-link px-3 py-2 rounded" style="background-color: rgba(255,255,255,0.1);">
                                <i class="fas fa-tachometer-alt mr-2"></i><span class="hidden sm:inline">Tableau de bord</span>
                            </a>
                            <a href="{{ route('interventions.index') }}" class="nav-link px-3 py-2 rounded" style="background-color: rgba(255,255,255,0.1);">
                                <i class="fas fa-tools mr-2"></i><span class="hidden sm:inline">Interventions</span>
                            </a>
                            <a href="{{ route('admin.repairs.index') }}" class="nav-link px-3 py-2 rounded" style="background-color: rgba(255,255,255,0.1);">
                                <i class="fas fa-car-side mr-2"></i><span class="hidden sm:inline">Réparations</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                <button type="submit" class="nav-link px-3 py-2 rounded" style="background-color: rgba(255,255,255,0.1);">
                                    <i class="fas fa-sign-out-alt mr-2"></i><span class="hidden sm:inline">Déconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    @endif

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-500 border border-green-400 text-white px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 border border-red-400 text-white px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('active');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const button = event.target.closest('button');
            
            if (!menu.contains(event.target) && !button && menu.classList.contains('active')) {
                menu.classList.remove('active');
            }
        });
    </script>
</body>
</html>
