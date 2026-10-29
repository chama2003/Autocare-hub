@props(['title' => 'Page title', 'subtitle' => null, 'action' => null])

<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div>
        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-brand-600 dark:text-brand-300">AutoCare Hub</p>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
        @endif
    </div>

    @if($action)
        <div>{{ $action }}</div>
    @endif
</div>
