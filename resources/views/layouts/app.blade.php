<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Safety Permit INKA Madiun') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-inka-light-gray flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-inka-navy text-white flex flex-col hidden md:flex shrink-0">
            <!-- Sidebar Header / Logo -->
            <div class="h-16 flex items-center justify-center border-b border-white/10 shrink-0">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-8 hidden">
                <div class="w-10 h-10 bg-white/10 rounded flex items-center justify-center text-xs font-bold text-gray-300 border border-white/20">Logo</div>
                <span class="ml-3 font-bold tracking-wider text-sm">SAFETY PERMIT</span>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                @php
                    $role = request()->segment(1);
                @endphp
                <a href="/{{ $role }}/dashboard" class="flex items-center px-3 py-2.5 bg-white/10 rounded-lg text-sm font-medium text-white">
                    <svg class="w-5 h-5 mr-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                
                @if($role == 'divisi')
                <a href="#" class="flex items-center px-3 py-2.5 hover:bg-white/5 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Permit
                </a>
                @endif
                
                @if(in_array($role, ['staff', 'manager', 'senior-manager']))
                <a href="#" class="flex items-center px-3 py-2.5 hover:bg-white/5 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Review Permit
                </a>
                @endif

                <a href="#" class="flex items-center px-3 py-2.5 hover:bg-white/5 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    History
                </a>

                @if($role == 'superadmin')
                <a href="#" class="flex items-center px-3 py-2.5 hover:bg-white/5 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    User Management
                </a>
                @endif
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 bg-inka-light-gray">
            
            <!-- Header -->
            <header class="h-16 bg-white border-b border-inka-border flex items-center justify-between px-6 shrink-0">
                <div class="flex items-center">
                    <button class="md:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 ml-2 md:ml-0">{{ $title ?? 'Dashboard' }}</h2>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <button class="text-gray-400 hover:text-gray-600 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="flex items-center gap-2 border-l border-inka-border pl-4">
                        <div class="w-8 h-8 rounded-full bg-inka-navy text-white flex items-center justify-center text-xs font-bold">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-semibold text-gray-700 leading-none">{{ Auth::user()->name ?? 'User Name' }}</p>
                            <p class="text-[10px] text-gray-500 mt-1 capitalize">{{ request()->segment(1) ?? 'Role' }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto space-y-6">
                    {{ $slot }}
                </div>
            </main>
        </div>

    </body>
</html>
