<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' — ' : '' }}Safety Permit INKA Madiun</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-gray-900 flex h-screen overflow-hidden">

        {{-- ===== SIDEBAR ===== --}}
        @php $role = auth()->user()->role ?? ''; @endphp
        <aside id="sidebar" class="w-64 bg-inka-navy text-white flex flex-col shrink-0 z-30 transition-transform duration-200">

            {{-- Logo --}}
            <div class="h-16 flex items-center gap-3 px-5 border-b border-white/10 shrink-0">
                <img src="{{ asset('assets/images/logoinka.svg') }}" alt="Logo PT INKA" class="h-8 w-auto brightness-0 invert">
                <div class="leading-tight pl-1 border-l border-white/20">
                    <p class="text-[11px] font-bold tracking-wider text-white">SAFETY PERMIT</p>
                    <p class="text-[9px] text-gray-400 tracking-wide">PT INKA Madiun</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-1">

                {{-- === SUPERADMIN === --}}
                @if($role === 'superadmin')
                    <a href="/superadmin/dashboard" class="sidebar-link {{ request()->is('superadmin/dashboard') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="/superadmin/users" class="sidebar-link {{ request()->is('superadmin/users*') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        User Management
                    </a>
                @endif

                {{-- === DIVISI === --}}
                @if($role === 'divisi')
                    <a href="/divisi/dashboard" class="sidebar-link {{ request()->is('divisi/dashboard') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="/divisi/permits/create" class="sidebar-link {{ request()->is('divisi/permits/create') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Buat Permit
                    </a>
                    <a href="/divisi/history" class="sidebar-link {{ request()->is('divisi/history*') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        History Permit
                    </a>
                    <a href="/divisi/cancellations" class="sidebar-link {{ request()->is('divisi/cancellations*') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Pembatalan Permit
                    </a>
                @endif

                {{-- === ADMIN (Staff, Manager, Senior Manager) === --}}
                @if(in_array($role, ['staff', 'manager', 'senior-manager']))
                    <a href="/admin/dashboard" class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="/admin/approvals" class="sidebar-link {{ request()->is('admin/approvals*') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Review Permit
                    </a>
                    <a href="/admin/history" class="sidebar-link {{ request()->is('admin/history*') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        History
                    </a>
                @endif



            </nav>

            {{-- Logout --}}
            <div class="px-3 pb-4 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-gray-400 hover:text-white hover:bg-white/5 rounded-xl transition-colors">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- ===== MAIN AREA ===== --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- Header --}}
            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0 shadow-sm">
                <div class="flex items-center gap-3">
                    <h2 class="text-base font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h2>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>

        </div>

        <style>
            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 9px 12px;
                border-radius: 10px;
                font-size: 0.8125rem;
                font-weight: 500;
                color: #9ca3af;
                transition: background-color 0.15s, color 0.15s;
            }
            .sidebar-link:hover {
                background-color: rgba(255,255,255,0.07);
                color: #fff;
            }
            .sidebar-link.active {
                background-color: rgba(255,255,255,0.12);
                color: #fff;
            }
            .sidebar-icon {
                width: 18px;
                height: 18px;
                flex-shrink: 0;
            }
        </style>

    </body>
</html>
