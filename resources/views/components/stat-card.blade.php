@props(['icon' => 'fa-solid fa-chart-line', 'label' => 'Label', 'value' => '0', 'hint' => null, 'color' => 'brand'])

@php
    $colors = [
        'brand' => ['tile' => 'from-brand-500/10 to-violet-500/10 text-brand-600 dark:text-brand-300', 'bar' => 'bg-gradient-to-r from-brand-500 to-violet-500'],
        'sky' => ['tile' => 'from-sky-500/10 to-brand-500/10 text-sky-600 dark:text-sky-300', 'bar' => 'bg-gradient-to-r from-sky-500 to-brand-500'],
        'emerald' => ['tile' => 'from-emerald-500/10 to-teal-500/10 text-emerald-600 dark:text-emerald-300', 'bar' => 'bg-gradient-to-r from-emerald-500 to-teal-500'],
        'amber' => ['tile' => 'from-amber-500/10 to-rose-500/10 text-amber-600 dark:text-amber-300', 'bar' => 'bg-gradient-to-r from-amber-500 to-rose-500'],
        'rose' => ['tile' => 'from-rose-500/10 to-pink-500/10 text-rose-600 dark:text-rose-300', 'bar' => 'bg-gradient-to-r from-rose-500 to-pink-500'],
        'violet' => ['tile' => 'from-violet-500/10 to-pink-500/10 text-violet-600 dark:text-violet-300', 'bar' => 'bg-gradient-to-r from-violet-500 to-pink-500'],
    ];
    $selected = $colors[$color] ?? $colors['brand'];
@endphp

<div class="card-shell fade-in-up p-5 {{ $attributes->get('class') }}">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $label }}</p>
            <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white tabular-nums">{{ $value }}</p>
        </div>
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br ring-1 ring-inset ring-white/30 {{ $selected['tile'] }}">
            <i class="{{ $icon }} text-lg"></i>
        </div>
    </div>

    @if($hint)
        <div class="mt-4 text-xs font-medium text-slate-500 dark:text-slate-400">{{ $hint }}</div>
    @endif

    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
        <div class="h-full rounded-full {{ $selected['bar'] }}" style="width: 72%"></div>
    </div>
</div>
