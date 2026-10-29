@extends('layouts.app')
@section('title', 'Appointments')
@section('page-title', 'Appointments')

@section('content')
<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <p class="text-sm text-slate-500 dark:text-slate-400">Interactive booking calendar and appointment queue.</p>
    <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/20 hover:from-indigo-400 hover:to-violet-400">
        <i class="fa-solid fa-plus"></i> Book Appointment
    </a>
</div>

<div class="mb-6 rounded-[28px] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900" x-data="appointmentCalendar()" x-init="load()">
    <div class="mb-4 flex items-center justify-between">
        <button @click="prevMonth()" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"><i class="fa-solid fa-chevron-left"></i></button>
        <p class="text-lg font-semibold text-slate-900 dark:text-white" x-text="monthLabel"></p>
        <button @click="nextMonth()" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"><i class="fa-solid fa-chevron-right"></i></button>
    </div>

    <div class="grid grid-cols-7 gap-2 text-center text-[11px] font-medium uppercase tracking-[0.12em] text-slate-400 dark:text-slate-500">
        <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
    </div>

    <div class="mt-2 grid grid-cols-7 gap-2">
        <template x-for="cell in cells">
            <div class="min-h-[90px] rounded-2xl border border-slate-200 bg-slate-50 p-2 dark:border-slate-700 dark:bg-slate-950/40" :class="cell.inMonth ? '' : 'opacity-50'">
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400" x-text="cell.day || ''"></div>
                <div class="mt-2 space-y-1">
                    <template x-for="a in cell.appointments" :key="a.id">
                        <div class="truncate rounded-lg bg-violet-50 px-1.5 py-1 text-[10px] font-medium text-violet-700 dark:bg-violet-500/10 dark:text-violet-300" x-text="a.title"></div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>

<div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500 dark:bg-slate-950/80 dark:text-slate-400">
                <tr>
                    <th class="px-4 py-3">Date</th><th class="px-4 py-3">Time</th><th class="px-4 py-3">Vehicle</th>
                    <th class="px-4 py-3">Service</th>
                    @if(!auth()->user()->isCustomer())<th class="px-4 py-3">Customer</th>@endif
                    <th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appt)
                <tr class="border-t border-slate-200 text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-950/50">
                    <td class="px-4 py-3">{{ $appt->appointment_date->format('d M Y') }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                    <td class="px-4 py-3 font-medium">{{ $appt->vehicle->plate_number }}</td>
                    <td class="px-4 py-3">{{ $appt->serviceType->name }}</td>
                    @if(!auth()->user()->isCustomer())<td class="px-4 py-3">{{ $appt->customer->name }}</td>@endif
                    <td class="px-4 py-3"><x-badge :variant="match($appt->status) { 'pending' => 'warning', 'approved' => 'success', 'cancelled' => 'danger', default => 'default' }">{{ $appt->status }}</x-badge></td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            @if((auth()->user()->isStaff() || auth()->user()->isAdmin()) && $appt->status === 'pending')
                                <form method="POST" action="{{ route('appointments.approve', $appt) }}" class="inline">
                                    @csrf
                                    <button class="rounded-lg bg-emerald-500 px-2.5 py-1.5 text-[11px] font-semibold text-white hover:bg-emerald-400">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('appointments.cancel', $appt) }}" class="inline">
                                    @csrf
                                    <button class="rounded-lg bg-rose-500 px-2.5 py-1.5 text-[11px] font-semibold text-white hover:bg-rose-400">Cancel</button>
                                </form>
                            @endif
                            @if((auth()->user()->isStaff() || auth()->user()->isAdmin()) && $appt->status === 'approved')
                                <a href="{{ route('jobcards.create', ['appointment_id' => $appt->id]) }}" class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">Open Job Card</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">No appointments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if(method_exists($appointments, 'links'))
<div class="mt-4">{{ $appointments->links() }}</div>
@endif

@push('scripts')
<script>
function appointmentCalendar() {
    return {
        current: new Date(),
        appointments: [],
        cells: [],
        monthLabel: '',
        async load() {
            const res = await fetch("{{ route('appointments.feed') }}");
            this.appointments = await res.json();
            this.build();
        },
        prevMonth() { this.current.setMonth(this.current.getMonth() - 1); this.build(); },
        nextMonth() { this.current.setMonth(this.current.getMonth() + 1); this.build(); },
        build() {
            const year = this.current.getFullYear(), month = this.current.getMonth();
            this.monthLabel = this.current.toLocaleString('default', { month: 'long', year: 'numeric' });
            const firstDay = new Date(year, month, 1);
            const startOffset = firstDay.getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const cells = [];
            for (let i = 0; i < startOffset; i++) cells.push({ day: '', inMonth: false, appointments: [] });
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                cells.push({
                    day: d,
                    inMonth: true,
                    appointments: this.appointments.filter(a => a.start.startsWith(dateStr)),
                });
            }
            this.cells = cells;
        },
    }
}
</script>
@endpush
@endsection
