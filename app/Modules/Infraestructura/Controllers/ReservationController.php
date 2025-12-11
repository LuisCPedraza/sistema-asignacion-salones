<?php

namespace App\Modules\Infraestructura\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Infraestructura\Models\Reservation;
use App\Modules\Infraestructura\Models\Classroom;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole('coordinador_infraestructura')) {
                abort(403, 'Acceso denegado. Se requiere rol de coordinador de infraestructura.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Reservation::with('classroom');

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_from')) {
            $query->whereDate('start_time', '>=', $request->start_from);
        }

        if ($request->filled('start_to')) {
            $query->whereDate('start_time', '<=', $request->start_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('requester_name', 'like', "%$search%")
                    ->orWhere('requester_email', 'like', "%$search%")
                    ->orWhere('notes', 'like', "%$search%");
            });
        }

        $reservations = $query->orderBy('start_time', 'asc')->paginate(15);
        $classrooms = Classroom::where('is_active', true)->orderBy('name')->get();

        $statusCounts = [
            'pendiente' => Reservation::pending()->count(),
            'aprobada' => Reservation::approved()->count(),
            'rechazada' => Reservation::rejected()->count(),
            'cancelada' => Reservation::cancelled()->count(),
        ];

        return view('infraestructura.reservations.index', [
            'reservations' => $reservations,
            'classrooms' => $classrooms,
            'filters' => $request->only(['classroom_id', 'status', 'start_from', 'start_to', 'search']),
            'statusCounts' => $statusCounts,
        ]);
    }

    public function create()
    {
        $classrooms = Classroom::where('is_active', true)->orderBy('name')->get();
        return view('infraestructura.reservations.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        Reservation::create($validated);

        return redirect()->route('infraestructura.reservations.index')
            ->with('success', 'Reserva creada correctamente.');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('classroom');
        return view('infraestructura.reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $classrooms = Classroom::where('is_active', true)->orderBy('name')->get();
        return view('infraestructura.reservations.edit', compact('reservation', 'classrooms'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $this->validateData($request, $reservation->id);
        $reservation->update($validated);

        return redirect()->route('infraestructura.reservations.show', $reservation)
            ->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('infraestructura.reservations.index')
            ->with('success', 'Reserva eliminada.');
    }

    public function approve(Reservation $reservation)
    {
        $reservation->approve();
        return back()->with('success', 'Reserva aprobada.');
    }

    public function reject(Reservation $reservation)
    {
        $reservation->reject();
        return back()->with('success', 'Reserva rechazada.');
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->cancel();
        return back()->with('success', 'Reserva cancelada.');
    }

    private function validateData(Request $request, ?int $reservationId = null): array
    {
        return $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requester_name' => 'required|string|max:120',
            'requester_email' => 'nullable|email|max:150',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:pendiente,aprobada,rechazada,cancelada',
            'notes' => 'nullable|string',
        ]);
    }
}
