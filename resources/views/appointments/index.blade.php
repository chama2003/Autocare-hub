@extends('layouts.app')
@section('title', 'Appointments')
@section('page-title', 'Appointments')

@section('content')
<div class="flex justify-between items-center mb-5">
    <p class="text-sm text-slate-500">Interactive booking calendar and appointment queue.</p>
    <a href="{{ route('appointments.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        <i class="fa-solid fa-plus mr-1"></i> Book Appointment
    </a>
</div>

{{-- Simple Alpine.js month calendar view sourced from the JSON feed --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-6" x-data="appointmentCalendar()" x-init="load()">
    <div class="flex justify-between items-center mb-4">
        <button @click="prevMonth()" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-chevron-left"></i></button>
        <p class="font-semibold" x-text="monthLabel"></p>
        <button @click="nextMonth()" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
    <div class="grid grid-cols-7 gap-1 text-center text-xs text-slate-400 mb-1">
        <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
    </div>
    <div class="grid grid-cols-7 gap-1">
        <template x-for="cell in cells">
            <div class="h-20 border rounded-lg p-1 text-xs" :class="cell.inMonth ? 'bg-white' : 'bg-slate-50 text-slate-300'">
                <div class="font-medium" x-text="cell.day"></div>
                <template x-for="a in cell.appointments" :key="a.id">
                    <div class="mt-1 truncate px-1 py-0.5 rounded bg-brand-100 text-brand-700" x-text="a.title"></div>
                </template>
            </div>
        </template>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-500">
            <tr>
                <th class="px-4 py-3">Date</th><th class="px-4 py-3">Time</th><th class="px-4 py-3">Vehicle</th>
                <th class="px-4 py-3">Service</th>
                @if(!auth()->user()->isCustomer())<th class="px-4 py-3">Customer</th>@endif
                <th class="px-4 py-3">Status</th><th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appt)
            <tr class="border-t">
                <td class="px-4 py-3">{{ $appt->appointment_date->format('d M Y') }}</td>
                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                <td class="px-4 py-3">{{ $appt->vehicle->plate_number }}</td>
                <td class="px-4 py-3">{{ $appt->serviceType->name }}</td>
                @if(!auth()->user()->isCustomer())<td class="px-4 py-3">{{ $appt->customer->name }}</td>@endif
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs bg-brand-100 text-brand-700 capitalize">{{ $appt->status }}</span></td>
                <td class="px-4 py-3 text-right space-x-2">
                    @if((auth()->user()->isStaff() || auth()->user()->isAdmin()) && $appt->status === 'pending')
                        <form method="POST" action="{{ route('appointments.approve', $appt) }}" class="inline">
                            @csrf
                            <button class="text-emerald-600 hover:underline">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('appointments.cancel', $appt) }}" class="inline">
                            @csrf
                            <button class="text-red-600 hover:underline">Cancel</button>
                        </form>
                    @endif
                    @if((auth()->user()->isStaff() || auth()->user()->isAdmin()) && $appt->status === 'approved')
                        <a href="{{ route('jobcards.create', ['appointment_id' => $appt->id]) }}" class="text-brand-600 hover:underline">Open Job Card</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">No appointments found.</td></tr>
            @endforelse
        </tbody>
    </table>
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
