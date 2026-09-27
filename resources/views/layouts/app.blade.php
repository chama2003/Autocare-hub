<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AutoCare Hub')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/[email protected]/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff', 100: '#dbeafe', 500: '#2563eb',
                            600: '#1d4ed8', 700: '#1e40af', 900: '#1e3a8a',
                        },
                    },
                },
            },
        }
    </script>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

<div class="min-h-screen flex">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-slate-200 transform transition-transform duration-200
                   lg:translate-x-0 lg:static lg:inset-auto"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-16 flex items-center gap-2 px-5 border-b border-slate-800">
            <i class="fa-solid fa-car-side text-brand-500 text-xl"></i>
            <span class="font-bold text-lg text-white">AutoCare Hub</span>
        </div>

        <nav class="p-3 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : '' }}">
                <i class="fa-solid fa-gauge-high w-5"></i> Dashboard
            </a>
            <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('vehicles.*') ? 'bg-slate-800 text-white' : '' }}">
                <i class="fa-solid fa-car w-5"></i> Vehicles
            </a>
            <a href="{{ route('appointments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('appointments.*') ? 'bg-slate-800 text-white' : '' }}">
                <i class="fa-solid fa-calendar-check w-5"></i> Appointments
            </a>

            @if(auth()->user()->isStaff() || auth()->user()->isAdmin() || auth()->user()->isMechanic())
            <a href="{{ route('jobcards.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('jobcards.*') ? 'bg-slate-800 text-white' : '' }}">
                <i class="fa-solid fa-clipboard-list w-5"></i> Job Cards
            </a>
            @endif

            <a href="{{ route('invoices.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('invoices.*') ? 'bg-slate-800 text-white' : '' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5"></i> Invoices
            </a>

            @if(auth()->user()->isStaff() || auth()->user()->isAdmin())
            <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('inventory.*') ? 'bg-slate-800 text-white' : '' }}">
                <i class="fa-solid fa-boxes-stacked w-5"></i> Inventory
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <a href="{{ route('finance.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('finance.*') ? 'bg-slate-800 text-white' : '' }}">
                <i class="fa-solid fa-chart-line w-5"></i> Finance
            </a>
            @endif
        </nav>

        <div class="absolute bottom-0 inset-x-0 p-3 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-sm">
                    <i class="fa-solid fa-right-from-bracket w-5"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col lg:ml-0">
        {{-- Topbar --}}
        <header class="h-16 bg-white border-b flex items-center justify-between px-4 lg:px-8 sticky top-0 z-20">
            <button class="lg:hidden text-slate-600" @click="sidebarOpen = !sidebarOpen">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h1 class="font-semibold text-lg text-slate-700">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline text-sm text-slate-500">{{ auth()->user()->name }}</span>
                <span class="text-xs px-2 py-1 rounded-full bg-brand-100 text-brand-700 font-medium capitalize">{{ auth()->user()->role }}</span>
            </div>
        </header>

        <main class="flex-1 p-4 lg:p-8">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm">
                    <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
