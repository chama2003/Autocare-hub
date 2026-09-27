@extends('layouts.app')
@section('title', 'Job Cards')
@section('page-title', 'Job Card Management')

@section('content')
<div class="flex justify-between items-center mb-5">
    <div class="flex gap-2 text-sm">
        @foreach(['' => 'All', 'pending' => 'Pending', 'in_progress' => 'In Progress', 'quality_check' => 'Quality Check', 'completed' => 'Completed'] as $key => $label)
        <a href="{{ route('jobcards.index', $key ? ['status' => $key] : []) }}"
           class="px-3 py-1.5 rounded-full {{ request('status', '') === $key ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $label }}</a>
        @endforeach
    </div>
    <a href="{{ route('jobcards.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        <i class="fa-solid fa-plus mr-1"></i> Open Job Card
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-500">
            <tr><th class="px-4 py-3">Job Card #</th><th class="px-4 py-3">Vehicle</th><th class="px-4 py-3">Mechanic</th><th class="px-4 py-3">Opened</th><th class="px-4 py-3">Status</th><th></th></tr>
        </thead>
        <tbody>
            @forelse($jobCards as $jc)
            <tr class="border-t hover:bg-slate-50">
                <td class="px-4 py-3 font-medium">{{ $jc->job_card_number }}</td>
                <td class="px-4 py-3">{{ $jc->vehicle->plate_number }}</td>
                <td class="px-4 py-3">{{ $jc->mechanic?->name ?? '—' }}</td>
                <td class="px-4 py-3">{{ $jc->opened_at->format('d M Y') }}</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-700 capitalize">{{ str_replace('_',' ',$jc->status) }}</span></td>
                <td class="px-4 py-3 text-right"><a href="{{ route('jobcards.show', $jc) }}" class="text-brand-600 hover:underline">Open</a></td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">No job cards found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if(method_exists($jobCards, 'links'))<div class="mt-4">{{ $jobCards->links() }}</div>@endif
@endsection
