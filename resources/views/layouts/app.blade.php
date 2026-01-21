<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SCEU UCLV - @yield('title', 'Panel de Administración')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="overlay" id="overlay"></div>

    <div class="flex h-screen">
        <aside class="sidebar sidebar-mobile fixed lg:relative bg-uclv-primary text-white w-64 flex flex-col z-30 h-full">
            <div class="p-4 border-b border-white/20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-university text-2xl text-uclv-primary"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg">SCEU UCLV</h1>
                        <p class="text-xs opacity-80">Sistema de Control de Eventos</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10' : '' }}">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="pt-4">
                        <p class="text-xs uppercase tracking-wider opacity-60 px-3 mb-2">Gestión</p>
                    </li>

                    <li>
                        <a href="{{ route('admin.categorias.index') }}"
                           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.categorias.*') ? 'bg-white/10' : '' }}">
                            <i class="fas fa-tags w-6"></i>
                            <span>Categorías</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.eventos.index') }}"
                           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.eventos.*') ? 'bg-white/10' : '' }}">
                            <i class="fas fa-calendar-alt w-6"></i>
                            <span>Eventos</span>
                        </a>
                    </li>

                     <li>
                        <a href="{{ route('admin.ediciones.index') }}"
                           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.ediciones.*') ? 'bg-white/10' : '' }}">
                            <i class="fas fa-calendar-day w-6"></i>
                            <span>Ediciones</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.modalidades.index') }}"
                           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.modalidades.*') ? 'bg-white/10' : '' }}">
                            <i class="fas fa-layer-group w-6"></i>
                            <span>Modalidades</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-white/20">
                <div class="text-center text-sm opacity-80">
                    <p>UCLV "Marta Abreu"</p>
                    <p class="mt-2 text-xs">© {{ date('Y') }} SCEU</p>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b">
                <div class="px-4 py-3 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <button id="sidebarToggle" class="lg:hidden text-gray-600 hover:text-uclv-primary">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Panel de Administración')</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 bg-uclv-primary rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="hidden md:inline text-gray-700">{{ Auth::user()->name ?? 'Usuario' }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>

                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i> Mi Perfil
                                </a>
                                <div class="border-t my-2"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                 @if(session('success'))
                    <div class="alert alert-success mb-4 p-4 rounded bg-green-100 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar-mobile').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        });

        document.getElementById('overlay')?.addEventListener('click', function() {
            document.querySelector('.sidebar-mobile').classList.remove('active');
            this.classList.remove('active');
        });

        // Toggle user menu
        document.getElementById('userMenuButton')?.addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('userMenu').classList.toggle('hidden');
        });

        // Cerrar menú al click fuera
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const userMenuButton = document.getElementById('userMenuButton');
            if (userMenu && !userMenu.classList.contains('hidden') && !userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
