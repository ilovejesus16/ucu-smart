<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FacilityReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = FacilityReservation::with(['facility', 'creator'])
            ->latest('reservation_date')
            ->latest('start_time');

        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('reservation_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                    ->orWhere('requesting_office', 'like', "%{$search}%");
            });
        }

        $reservations = $query->paginate(12)->withQueryString();

        $facilities = Facility::where('is_active', true)
            ->orderBy('name')
            ->get();

        $totalReservations = FacilityReservation::count();

        $upcomingReservations = FacilityReservation::approved()
            ->whereDate('reservation_date', '>=', today())
            ->count();

        $cancelledReservations = FacilityReservation::where('status', 'cancelled')
            ->count();

        return view('admin.facility-reservations.index', compact(
            'reservations',
            'facilities',
            'totalReservations',
            'upcomingReservations',
            'cancelledReservations'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateReservation($request);

        DB::transaction(function () use ($validated, $request) {
            $facility = Facility::whereKey($validated['facility_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if (!$facility->is_active) {
                throw ValidationException::withMessages([
                    'facility_id' => 'This facility is currently inactive.',
                ]);
            }

            $this->ensureNoConflict($validated);

            FacilityReservation::create([
                ...$validated,
                'status' => 'approved',
                'created_by' => $request->user()->id,
            ]);
        });

        return back()->with('success', 'Reservation created successfully.');
    }

    public function update(Request $request, FacilityReservation $reservation)
    {
        abort_if($reservation->status === 'cancelled', 422, 'Cancelled reservations cannot be edited.');

        $validated = $this->validateReservation($request);

        DB::transaction(function () use ($validated, $reservation) {
            Facility::whereKey($validated['facility_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $facility = Facility::findOrFail($validated['facility_id']);

            if (!$facility->is_active && $facility->id !== $reservation->facility_id) {
                throw ValidationException::withMessages([
                    'facility_id' => 'This facility is currently inactive.',
                ]);
            }

            $this->ensureNoConflict($validated, $reservation->id);

            $reservation->update($validated);
        });

        return back()->with('success', 'Reservation updated successfully.');
    }

    public function cancel(FacilityReservation $reservation)
    {
        if ($reservation->status === 'cancelled') {
            return back()->with('info', 'This reservation is already cancelled.');
        }

        $reservation->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Reservation cancelled successfully.');
    }

    private function validateReservation(Request $request): array
    {
        return $request->validate([
            'facility_id' => ['required', 'exists:facilities,id'],
            'event_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'reservation_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],
            'requesting_office' => ['required', 'string', 'max:255'],
        ]);
    }

    private function ensureNoConflict(
        array $data,
        ?int $ignoreReservationId = null
    ): void {
        $conflict = FacilityReservation::where('facility_id', $data['facility_id'])
            ->whereDate('reservation_date', $data['reservation_date'])
            ->where('status', 'approved')
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->when(
                $ignoreReservationId,
                fn ($query) => $query->where('id', '!=', $ignoreReservationId)
            )
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'start_time' => 'This facility already has an approved reservation that overlaps with the selected time.',
            ]);
        }
    }
}
