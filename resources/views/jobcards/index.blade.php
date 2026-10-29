@extends('layouts.app')
@section('title', 'Job Cards')
@section('page-title', 'Job Card Management')

@section('content')
<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div class="flex flex-wrap gap-2 text-sm">
        @foreach(['' => 'All', 'pending' => 'Pending', 'in_progress' => 'In Progress', 'quality_check' => 'Quality Check', 'completed' => 'Completed'] as $key => $label)
        <a href="{{ route('jobcards.index', $key ? ['status' => $key] : []) }}"
           class="rounded-full px-3 py-1.5 font-medium {{ request('status', '') === $key ? 'bg-gradient-to-r from-indigo-500 to-violet-500 text-white shadow-lg shadow-violet-500/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700' }}">{{ $label }}</a>
        @endforeach
    </div>
    <a href="{{ route('jobcards.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/20 hover:from-indigo-400 hover:to-violet-400">
        <i class="fa-solid fa-plus"></i> Open Job Card
    </a>
</div>

<div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500 dark:bg-slate-950/80 dark:text-slate-400">
                <tr><th class="px-4 py-3">Job Card #</th><th class="px-4 py-3">Vehicle</th><th class="px-4 py-3">Mechanic</th><th class="px-4 py-3">Opened</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right"></th></tr>
            </thead>
            <tbody>
                @forelse($jobCards as $jc)
                <tr class="border-t border-slate-200 text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-950/50">
                    <td class="px-4 py-3 font-semibold">{{ $jc->job_card_number }}</td>
                    <td class="px-4 py-3">{{ $jc->vehicle->plate_number }}</td>
                    <td class="px-4 py-3">{{ $jc->mechanic?->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $jc->opened_at->format('d M Y') }}</td>
                    <td class="px-4 py-3"><x-badge :variant="match($jc->status) { 'pending' => 'warning', 'in_progress' => 'info', 'quality_check' => 'primary', 'completed' => 'success', default => 'default' }">{{ str_replace('_',' ',$jc->status) }}</x-badge></td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('jobcards.show', $jc) }}" class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">Open</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">No job cards found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if(method_exists($jobCards, 'links'))<div class="mt-4">{{ $jobCards->links() }}</div>@endif
@endsection
