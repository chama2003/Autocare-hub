@props(['icon' => 'fa-solid fa-circle-info', 'title' => 'No records found', 'message' => 'There are no records to show right now.', 'action' => null])

<div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50/80 px-6 py-12 text-center dark:border-white/10 dark:bg-slate-900/40">
    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500/10 to-violet-500/10 text-xl text-brand-600 ring-1 ring-brand-500/10 dark:text-brand-300 dark:ring-brand-500/15">
        <i class="{{ $icon }}"></i>
    </div>
    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
    <p class="mt-2 max-w-md text-sm text-slate-500 dark:text-slate-400">{{ $message }}</p>

    @if($action)
        <div class="mt-5">{{ $action }}</div>
    @endif
</div>
