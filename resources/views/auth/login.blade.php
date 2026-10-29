<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600 dark:text-violet-300">Welcome back</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Sign in to your workspace</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Access appointments, job cards, invoices, and service history.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="input-base @error('email') border-rose-300 focus:border-rose-400 focus:ring-rose-100 dark:border-rose-500/40 dark:focus:ring-rose-500/20 @enderror">
            @error('email')
                <p class="mt-2 text-xs font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-violet-600 hover:text-violet-500 dark:text-violet-300" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="input-base @error('password') border-rose-300 focus:border-rose-400 focus:ring-rose-100 dark:border-rose-500/40 dark:focus:ring-rose-500/20 @enderror">
            @error('password')
                <p class="mt-2 text-xs font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <label for="remember_me" class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-violet-600 shadow-sm focus:ring-violet-500" name="remember">
            <span>Remember me</span>
        </label>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-indigo-400 hover:to-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
            <i class="fa-solid fa-right-to-bracket"></i>
            Log in
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        Need an account?
        <a href="{{ route('register') }}" class="font-semibold text-violet-600 hover:text-violet-500 dark:text-violet-300">Create one</a>
    </p>
</x-guest-layout>
