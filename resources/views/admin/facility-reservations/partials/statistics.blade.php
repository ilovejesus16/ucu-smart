<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <p class="text-sm text-slate-500">Total Reservations</p>
        <h2 class="mt-2 text-3xl font-bold text-slate-800">
            {{ $totalReservations }}
        </h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <p class="text-sm text-slate-500">Upcoming Reservations</p>
        <h2 class="mt-2 text-3xl font-bold text-[#0E4C6B]">
            {{ $upcomingReservations }}
        </h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <p class="text-sm text-slate-500">Cancelled Reservations</p>
        <h2 class="mt-2 text-3xl font-bold text-red-600">
            {{ $cancelledReservations }}
        </h2>
    </div>

</div>
