<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name', 'AutoCare Hub'))</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="{
        sidebarOpen: false,
        dark: localStorage.getItem('theme') === 'dark'
    }" x-init="
        $watch('dark', value => {
            localStorage.setItem('theme', value ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', value);
        });
        document.documentElement.classList.toggle('dark', dark);
    " class="page-shell antialiased">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute left-[-120px] top-[-80px] h-72 w-72 rounded-full bg-brand-400/20 blur-3xl"></div>
            <div class="absolute right-[-40px] top-32 h-80 w-80 rounded-full bg-pink-400/20 blur-3xl"></div>
        </div>

        <div class="relative mx-auto flex min-h-screen max-w-[1400px] gap-5 p-4 sm:p-6 lg:p-8">
            <aside class="hidden w-72 shrink-0 flex-col rounded-[30px] border border-slate-200/70 bg-white/80 p-4 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_4px_16px_rgba(16,24,40,0.06)] backdrop-blur-xl dark:border-white/5 dark:bg-slate-900/80 lg:flex">
                <div class="flex items-center gap-3 border-b border-slate-200/80 pb-4 dark:border-white/5">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl grad-brand text-lg font-bold text-white shadow-lg shadow-brand-500/30">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <div>
                            <div class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">AutoCare Hub</div>
                            <div class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Service Management</div>
                        </div>
                    </a>
                </div>

                @php $user = auth()->user(); @endphp

                <nav class="mt-6 space-y-6">
                    <div>
                        <p class="px-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Overview</p>
                        <div class="mt-3 space-y-1.5">
                            <a href="{{ route('dashboard') }}" class="nav-pill {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <span class="nav-icon bg-brand-500/10 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300"><i class="fa-solid fa-grid-2"></i></span>
                                Dashboard
                            </a>
                            <a href="{{ route('vehicles.index') }}" class="nav-pill {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                                <span class="nav-icon bg-sky-500/10 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300"><i class="fa-solid fa-car-side"></i></span>
                                Vehicles
                            </a>
                            <a href="{{ route('appointments.index') }}" class="nav-pill {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                                <span class="nav-icon bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300"><i class="fa-regular fa-calendar-check"></i></span>
                                Appointments
                            </a>
                        </div>
                    </div>

                    <div>
                        <p class="px-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Operations</p>
                        <div class="mt-3 space-y-1.5">
                            <a href="{{ route('jobcards.index') }}" class="nav-pill {{ request()->routeIs('jobcards.*') ? 'active' : '' }}">
                                <span class="nav-icon bg-violet-500/10 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300"><i class="fa-solid fa-clipboard-list"></i></span>
                                Job Cards
                            </a>
                            <a href="{{ route('invoices.index') }}" class="nav-pill {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                                <span class="nav-icon bg-amber-500/10 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                                Invoices
                            </a>
                            <a href="{{ route('inventory.index') }}" class="nav-pill {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                                <span class="nav-icon bg-rose-500/10 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300"><i class="fa-solid fa-boxes-stacked"></i></span>
                                Inventory
                            </a>
                        </div>
                    </div>

                    @if($user && ($user->isAdmin() || $user->isStaff()))
                        <div>
                            <p class="px-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Management</p>
                            <div class="mt-3 space-y-1.5">
                                <a href="{{ route('finance.dashboard') }}" class="nav-pill {{ request()->routeIs('finance.*') ? 'active' : '' }}">
                                    <span class="nav-icon bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300"><i class="fa-solid fa-chart-pie"></i></span>
                                    Finance
                                </a>
                            </div>
                        </div>
                    @endif
                </nav>

                <div class="mt-auto rounded-[24px] grad-brand p-4 text-white shadow-lg shadow-brand-500/30">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-white/80">
                        {{ $user && method_exists($user, 'isCustomer') && $user->isCustomer() ? 'Customer view' : 'Operations' }}
                    </p>
                    <p class="mt-2 text-2xl font-extrabold tracking-tight">
                        {{ $user && method_exists($user, 'isCustomer') && $user->isCustomer() ? 'Service check' : 'Workshop pulse' }}
                    </p>
                    <p class="mt-1 text-sm text-white/80">
                        {{ $user && method_exists($user, 'isCustomer') && $user->isCustomer() ? 'Keep vehicles running smoothly.' : 'Everything is tracking on schedule.' }}
                    </p>
                </div>

                <div class="mt-5 rounded-2xl border border-slate-200/70 bg-slate-50/80 p-3 dark:border-white/5 dark:bg-slate-950/60">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl grad-brand text-sm font-bold text-white">
                            {{ strtoupper(substr($user->name ?? 'AC', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $user->name ?? 'AutoCare User' }}</div>
                            <div class="truncate text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ $user->role ?? 'User' }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 dark:border-white/5 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="sticky top-0 z-30 mb-5 rounded-[28px] border border-slate-200/70 bg-white/70 px-4 py-3 backdrop-blur-xl dark:border-white/5 dark:bg-slate-900/70">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200/70 bg-white text-slate-600 hover:bg-slate-100 dark:border-white/5 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 lg:hidden" @click="sidebarOpen = true">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                            <div class="hidden sm:block">
                                <div class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Home</div>
                                <div class="mt-1 text-sm font-medium text-slate-600 dark:text-slate-300">/ @yield('title', 'Dashboard')</div>
                            </div>
                        </div>

                        <div class="hidden min-w-[220px] max-w-md flex-1 items-center gap-3 rounded-2xl border border-slate-200/70 bg-slate-50/80 px-3 py-2.5 dark:border-white/5 dark:bg-slate-950/60 md:flex">
                            <i class="fa-solid fa-magnifying-glass text-sm text-slate-400"></i>
                            <input type="text" placeholder="Search anything..." aria-label="Search" class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none dark:text-slate-200" />
                        </div>

                        <div class="flex items-center gap-2 md:gap-3">
                            <button type="button" class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200/70 bg-white text-slate-600 hover:bg-slate-100 dark:border-white/5 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800" aria-label="Toggle dark mode" @click="dark = !dark">
                                <i class="fa-solid" :class="dark ? 'fa-sun' : 'fa-moon'" aria-hidden="true"></i>
                            </button>

                            <button type="button" class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200/70 bg-white text-slate-600 hover:bg-slate-100 dark:border-white/5 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800" aria-label="Notifications">
                                <i class="fa-solid fa-bell"></i>
                                <span class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900"></span>
                            </button>

                            <div class="relative" x-data="{ open: false }">
                                <button type="button" class="flex items-center gap-3 rounded-xl border border-slate-200/70 bg-slate-50/80 px-2 py-1.5 hover:bg-slate-100 dark:border-white/5 dark:bg-slate-950/70 dark:hover:bg-slate-800" @click="open = !open" aria-expanded="false">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl grad-brand text-sm font-bold text-white">
                                        {{ strtoupper(substr($user->name ?? 'AC', 0, 1)) }}
                                    </div>
                                    <div class="hidden text-left sm:block">
                                        <div class="text-sm font-semibold text-slate-800 dark:text-white">{{ $user->name ?? 'AutoCare User' }}</div>
                                        <div class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ $user->role ?? 'User' }}</div>
                                    </div>
                                    <i class="fa-solid fa-chevron-down hidden text-[10px] text-slate-400 sm:block"></i>
                                </button>

                                <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 top-[calc(100%+12px)] w-56 rounded-2xl border border-slate-200/70 bg-white p-2 shadow-soft dark:border-white/5 dark:bg-slate-900">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800"><i class="fa-solid fa-user"></i> Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="px-1 pb-6 md:px-2">
                    @if(session('success') || session('error') || session('warning'))
                        @php $flashType = session('success') ? 'success' : (session('error') ? 'error' : 'warning'); $flashMessage = session('success') ?? session('error') ?? session('warning'); @endphp
                        <div class="mb-5" x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-2 opacity-0">
                            <div class="flex items-center justify-between gap-3 rounded-2xl border px-4 py-3 shadow-soft
                                @if($flashType === 'success') border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300
                                @elseif($flashType === 'error') border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300
                                @else border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300
                                @endif">
                                <div class="flex items-center gap-3">
                                    <i class="@if($flashType === 'success') fa-solid fa-circle-check @elseif($flashType === 'error') fa-solid fa-circle-xmark @else fa-solid fa-triangle-exclamation @endif"></i>
                                    <span class="text-sm font-medium">{{ $flashMessage }}</span>
                                </div>
                                <button type="button" class="text-sm opacity-75 hover:opacity-100" @click="show = false">×</button>
                            </div>
                        </div>
                    @endif

                    <div class="mb-6 rounded-[28px] border border-slate-200/70 bg-white/80 px-5 py-4 shadow-soft dark:border-white/5 dark:bg-slate-900/80">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Page</div>
                                <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                <span>AutoCare Hub</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                <span class="font-semibold text-slate-700 dark:text-slate-200">@yield('title', 'Dashboard')</span>
                            </div>
                        </div>
                    </div>

                    @yield('content')
                </main>
            </div>
        </div>

        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-4 opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="-translate-x-4 opacity-0" class="fixed inset-0 z-40 lg:hidden" x-cloak>
            <div class="absolute inset-0 bg-slate-950/40" @click="sidebarOpen = false"></div>
            <aside class="relative h-full w-72 max-w-[85vw] border-r border-slate-200/70 bg-white p-4 shadow-soft dark:border-white/5 dark:bg-slate-900">
                <div class="flex items-center justify-between pb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl grad-brand text-white"><i class="fa-solid fa-car-side"></i></div>
                        <div class="text-base font-extrabold text-slate-900 dark:text-white">AutoCare Hub</div>
                    </div>
                    <button type="button" @click="sidebarOpen = false" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/70 text-slate-500 dark:border-white/5 dark:text-slate-300">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <nav class="mt-6 space-y-1.5 text-sm">
                    <a href="{{ route('dashboard') }}" class="nav-pill {{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="nav-icon bg-brand-500/10 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300"><i class="fa-solid fa-grid-2"></i></span>Dashboard</a>
                    <a href="{{ route('vehicles.index') }}" class="nav-pill {{ request()->routeIs('vehicles.*') ? 'active' : '' }}"><span class="nav-icon bg-sky-500/10 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300"><i class="fa-solid fa-car-side"></i></span>Vehicles</a>
                    <a href="{{ route('appointments.index') }}" class="nav-pill {{ request()->routeIs('appointments.*') ? 'active' : '' }}"><span class="nav-icon bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300"><i class="fa-regular fa-calendar-check"></i></span>Appointments</a>
                    <a href="{{ route('jobcards.index') }}" class="nav-pill {{ request()->routeIs('jobcards.*') ? 'active' : '' }}"><span class="nav-icon bg-violet-500/10 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300"><i class="fa-solid fa-clipboard-list"></i></span>Job Cards</a>
                    <a href="{{ route('invoices.index') }}" class="nav-pill {{ request()->routeIs('invoices.*') ? 'active' : '' }}"><span class="nav-icon bg-amber-500/10 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300"><i class="fa-solid fa-file-invoice-dollar"></i></span>Invoices</a>
                    <a href="{{ route('inventory.index') }}" class="nav-pill {{ request()->routeIs('inventory.*') ? 'active' : '' }}"><span class="nav-icon bg-rose-500/10 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300"><i class="fa-solid fa-boxes-stacked"></i></span>Inventory</a>
                    @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isStaff()))
                        <a href="{{ route('finance.dashboard') }}" class="nav-pill {{ request()->routeIs('finance.*') ? 'active' : '' }}"><span class="nav-icon bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300"><i class="fa-solid fa-chart-pie"></i></span>Finance</a>
                    @endif
                </nav>
            </aside>
        </div>
    </body>
</html>
