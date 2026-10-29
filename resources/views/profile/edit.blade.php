@extends('layouts.app')
@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')
<div class="grid gap-6 xl:grid-cols-3">
    <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900 xl:col-span-1">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-xl font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name ?? 'AC', 0, 1)) }}
            </div>
            <div>
                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <div class="mt-6 space-y-3 text-sm">
            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-950/50"><span class="text-slate-500 dark:text-slate-400">Role</span><span class="font-medium text-slate-900 dark:text-white">{{ auth()->user()->role ?? 'User' }}</span></div>
            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-950/50"><span class="text-slate-500 dark:text-slate-400">Member since</span><span class="font-medium text-slate-900 dark:text-white">{{ auth()->user()->created_at?->format('d M Y') ?? '—' }}</span></div>
        </div>
    </div>

    <div class="space-y-6 xl:col-span-2">
        <x-card class="p-5">
            @include('profile.partials.update-profile-information-form')
        </x-card>

        <x-card class="p-5">
            @include('profile.partials.update-password-form')
        </x-card>

        <x-card class="p-5">
            @include('profile.partials.delete-user-form')
        </x-card>
    </div>
</div>
@endsection
