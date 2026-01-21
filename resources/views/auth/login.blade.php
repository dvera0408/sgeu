@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@section('content')
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-uclv-primary focus:border-uclv-primary">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-uclv-primary focus:border-uclv-primary">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4 flex items-center justify-around">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-uclv-primary focus:ring-uclv-primary">
                <span class="ml-2 text-sm text-gray-600">Recordarme</span>
            </label>
                <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-uclv-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#269aa6] focus:bg-[#269aa6] active:bg-[#1a7c86] focus:outline-none focus:ring-2 focus:ring-uclv-primary focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-sign-in-alt mr-2"></i>
                {{ __('Iniciar Sesión') }}
            </button>
        </div>
    </form>

    <!-- Nota para el administrador -->
    <div class="mt-6 pt-4 border-t border-gray-200">
        <p class="text-xs text-gray-600 text-center">
            Sistema de administración SCEU UCLV.<br>
            Para acceder al panel de administración, utiliza las credenciales proporcionadas.
        </p>
    </div>
@endsection
