@props(['variant' => 'primary', 'size' => 'md', 'as' => 'button'])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-brand-500/15 disabled:cursor-not-allowed disabled:opacity-60 active:scale-[0.98]';
    $sizes = [
        'sm' => 'min-h-[36px] px-3 py-2 text-xs',
        'md' => 'min-h-[44px] px-4 py-2.5 text-sm',
        'lg' => 'min-h-[48px] px-5 py-3 text-sm',
    ];
    $variants = [
        'primary' => 'grad-brand text-white shadow-lg shadow-brand-500/30 hover:brightness-110',
        'secondary' => 'border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 dark:border-white/5 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800',
        'danger' => 'bg-rose-500 text-white shadow-lg shadow-rose-500/20 hover:bg-rose-400',
        'ghost' => 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800',
        'success' => 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 hover:bg-emerald-400',
    ];
@endphp

@if ($as === 'a')
    <a {{ $attributes->merge(['class' => $base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary'])]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary'])]) }}>
        {{ $slot }}
    </button>
@endif
