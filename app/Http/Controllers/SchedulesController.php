<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Film;
use App\Models\Time_Slot;
use App\Models\Seats;
use Illuminate\Auth\Events\Validated;
use Carbon\Carbon;

class SchedulesController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['film', 'time_slot'])->latest()->paginate(10);
        return view('admin.dashboard.schedules', compact('schedules'));
    }

    public function create()
    {
        $data['films'] = Film::all();
        $data['schedules'] = [];
        return view('admin.dashboard.createschedules', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:films,id', // Validasi ID film
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date', // Pastikan end_date tidak sebelum start_date
        ]);

        $film = Film::findorfail($request->film_id);
        $time_slots = Time_Slot::all();
        $filmDuration = $film->duration;

        $startDate = \Carbon\Carbon::parse($request->input('start_date'));
        $endDate = \Carbon\Carbon::parse($request->input('end_date'));


        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            foreach ($time_slots as $item) {
                $startTime = Carbon::parse($item->slot);
                $endTime = $startTime->copy()->addMinutes($filmDuration);
                
                $schedule = Schedule::create([
                    'films_id' => $film->id,
                    'time_slot_id' => $item->id,
                    'show_date' => $currentDate,
                    'total_seats' => 150,
                    'start_time' => $startTime->format('H:i'),
                    'end_time' => $endTime->format('H:i'),
                ]);

                $this->createSeats($schedule);
            }

            $currentDate->addDay();
        }

        return redirect()->route('admin.dashboard.schedules')->with('success', 'Schedules Berhasil Dibuat');
    }

    private function createSeats($schedule)
    {
        $rows = range('A', 'I'); // Baris A sampai I
        $seatsPerRow = 15; // 15 kursi per baris

        foreach ($rows as $row) {
            for ($i = 1; $i <= $seatsPerRow; $i++) {
                Seats::create([
                    'schedule_id' => $schedule->id,
                    'seat_number' => $row . $i, // Format kursi, misal A1, A2, ..., I15
                    'status' => 'sedia', // Status default
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('admin.dashboard.schedules')->with('success', 'Schedule has been deleted');
    }
}