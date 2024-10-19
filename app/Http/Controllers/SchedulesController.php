<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Film;
use App\Models\Time_Slot;
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

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'films_id' => 'required|exists:films,id',
    //         'time_slot_id' => 'required|array',
    //         'show_dates' => 'required|array',
    //         'total_seats' => 'required|integer|min:1'
    //     ]);

    // }

    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:films,id', // Validasi ID film
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date', // Pastikan end_date tidak sebelum start_date
        ]);

        $film = Film::findorfail($request->film_id);
        $time_slots = Time_Slot::all();

        $startDate = \Carbon\Carbon::parse($request->input('start_date'));
        $endDate = \Carbon\Carbon::parse($request->input('end_date'));

        
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            foreach ($time_slots as $item) {
                Schedule::create([
                    'films_id' => $film->id,
                    'time_slot_id' => $item->id,
                    'show_date' => $currentDate,
                    'total_seats' => 200
                ]);
            }

            $currentDate->addDay();
        }

        return redirect()->route('admin.dashboard.schedules')->with('success', 'Schedules Berhasil Dibuat');
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('admin.dashboard.schedules')->with('success', 'Schedule has been deleted');
    }
}
