<x-guest-layout>
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600 dark:text-violet-300">Reset access</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Forgot your password?</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No problem. Enter your email and we’ll send you a reset link.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input-base @error('email') border-rose-300 focus:border-rose-400 focus:ring-rose-100 dark:border-rose-500/40 dark:focus:ring-rose-500/20 @enderror">
            @error('email')
                <p class="mt-2 text-xs font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-indigo-400 hover:to-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
            <i class="fa-solid fa-paper-plane"></i>
            Email reset link
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        <a href="{{ route('login') }}" class="font-semibold text-violet-600 hover:text-violet-500 dark:text-violet-300">Back to login</a>
    </p>
</x-guest-layout>
