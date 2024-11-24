<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\Seats;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index($scheduleId)
    {
        $schedule = Schedule::findorfail($scheduleId);
        $film = $schedule->film;
        $availableSchedules = Schedule::where('films_id', $film->id)->get();
        $availableSeats = Seats::where('schedule_id', $scheduleId)
            ->select([
                'id',
                'seat_number',
                'status'
            ])
            ->get();
        return view('user.booking.index', compact('schedule', 'availableSeats', 'film', 'availableSchedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_id' => 'required|exists:seats,id',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        $seat = Seats::findOrFail($request->seat_id);

        if ($seat->status != 'sedia') {
            return redirect()->back()->withErrors('Kursi sudah dipesan!');
        }

        $totalPrice = $schedule->price;

        Booking::create([
            'user_id' => Auth::id(),
            'schedule_id' => $schedule->id,
            'seat_id' => $seat->id,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        $seat->update(['status' => 'tidak tersedia']);

        return redirect()->route('user.booking.konfirmasi', ['scheduleId' => $schedule->id])->with('success', 'Pemesanan Berhasil');
    }

    public function konfirmasi($scheduleId)
    {
        // Ambil semua pemesanan terakhir dari pengguna yang sedang login
        $booking = Booking::where('user_id', Auth::id())->latest()->first();

        if (!$booking) {
            return redirect()->route('user.booking.index', ['scheduleId' => $scheduleId])->withErrors('Tidak ada pemesanan yang ditemukan.');
        }

        $schedule = Schedule::with('film')->findOrFail($booking->schedule_id);
        $seat = Seats::findOrFail($booking->seat_id);
        $totalPrice = $booking->total_price;

        return view('user.booking.konfirmasi', compact('booking', 'schedule', 'seat', 'totalPrice'));
    }
}
