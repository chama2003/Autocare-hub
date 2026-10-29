<nav class="bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-slate-800">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-brand-600 border border-slate-200">
                        <i class="fa-solid fa-box-open"></i>
                    </span>
                    <span class="text-xl font-semibold">Dashboard</span>
                </a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <details class="relative">
                        <summary class="list-none inline-flex cursor-pointer items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                            <span>{{ auth()->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </summary>
                        <div class="dropdown-menu absolute right-0 top-full mt-2 min-w-44 rounded-lg border border-slate-200 bg-white shadow-md">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">Log Out</button>
                            </form>
                        </div>
                    </details>
                @endauth
            </div>
        </div>
    </div>
</nav>
