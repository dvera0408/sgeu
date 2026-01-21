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
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <!-- Logo UCLV -->
            <div class="flex flex-col items-center mb-6">
                <div class="w-16 h-16 bg-uclv-primary rounded-full flex items-center justify-center mb-3">
                    <i class="fas fa-university text-white text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">SCEU UCLV</h1>
                <p class="text-sm text-gray-600">Sistema de Control de Eventos</p>
            </div>

            <!-- Contenido de la vista -->
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-gray-600 text-sm">
            <p>Universidad Central "Marta Abreu" de Las Villas</p>
            <p class="mt-1">© {{ date('Y') }} SCEU - Todos los derechos reservados</p>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
