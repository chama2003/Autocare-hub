<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'AutoCare Hub') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="{ dark: localStorage.getItem('theme') === 'dark' }" x-init="document.documentElement.classList.toggle('dark', dark); $watch('dark', value => { localStorage.setItem('theme', value ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', value); });" class="page-shell antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute left-[-150px] top-[-80px] h-72 w-72 rounded-full bg-brand-400/20 blur-3xl"></div>
                <div class="absolute right-[-60px] top-16 h-80 w-80 rounded-full bg-violet-500/15 blur-3xl"></div>
                <div class="absolute bottom-[-80px] left-1/3 h-72 w-72 rounded-full bg-pink-400/15 blur-3xl"></div>
            </div>

            <div class="relative mx-auto flex min-h-screen max-w-[1400px] items-center justify-center p-4 sm:p-6 lg:p-8">
                <div class="grid w-full overflow-hidden rounded-[32px] border border-slate-200/70 bg-white shadow-[0_1px_2px_rgba(16,24,40,0.04),0_4px_16px_rgba(16,24,40,0.06)] dark:border-white/5 dark:bg-slate-900 lg:grid-cols-[1.2fr_0.8fr]">
                    <div class="relative hidden overflow-hidden grad-brand p-8 text-white lg:flex lg:flex-col lg:justify-between">
                        <div class="absolute inset-0 opacity-60" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.22), transparent 30%), radial-gradient(circle at bottom right, rgba(255,255,255,0.12), transparent 30%);"></div>

                        <div class="relative z-10 flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/20 bg-white/10 text-lg backdrop-blur-sm">
                                <i class="fa-solid fa-car-side"></i>
                            </div>
                            <div>
                                <div class="text-lg font-extrabold tracking-tight">AutoCare Hub</div>
                                <div class="text-[10px] font-semibold uppercase tracking-[0.22em] text-white/80">Service management</div>
                            </div>
                        </div>

                        <div class="relative z-10 max-w-md">
                            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-white/80 backdrop-blur-sm">
                                <i class="fa-solid fa-shield-heart"></i>
                                Trusted by modern fleets
                            </div>
                            <h1 class="text-4xl font-extrabold leading-tight tracking-tight">Keep every vehicle running smooth.</h1>
                            <p class="mt-4 text-base text-white/85">Track jobs, manage inventory, and deliver premium service experiences from one intelligent workshop dashboard.</p>
                        </div>

                        <div class="relative z-10 grid gap-3 text-sm text-white/90">
                            <div class="flex items-center gap-3 rounded-2xl border border-white/15 bg-white/5 px-4 py-3 backdrop-blur-sm">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10"><i class="fa-solid fa-calendar-check"></i></span>
                                <span>Appointments and workshop scheduling</span>
                            </div>
                            <div class="flex items-center gap-3 rounded-2xl border border-white/15 bg-white/5 px-4 py-3 backdrop-blur-sm">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10"><i class="fa-solid fa-boxes-stacked"></i></span>
                                <span>Inventory and stock visibility</span>
                            </div>
                            <div class="flex items-center gap-3 rounded-2xl border border-white/15 bg-white/5 px-4 py-3 backdrop-blur-sm">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                                <span>Automated invoice generation</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center p-6 sm:p-8 lg:p-10">
                        <div class="w-full max-w-md">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
