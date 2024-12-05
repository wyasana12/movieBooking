<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\Seats;
use App\Models\service;
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
        $service = service::all();
        return view('user.booking.index', compact('schedule', 'availableSeats', 'film', 'availableSchedules', 'service'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_id' => 'required|array', // Pastikan seat_id adalah array
            'seat_id.*' => 'exists:seats,id', // Validasi setiap ID kursi
            'services' => 'array',
            'services.*' => 'exists:services,id',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);

        // Ambil kursi yang dipilih berdasarkan ID
        $seats = Seats::whereIn('id', $request->seat_id)->get();

        // Pastikan semua kursi yang dipilih tersedia
        foreach ($seats as $seat) {
            if ($seat->status != 'sedia') {
                return redirect()->back()->withErrors('Beberapa kursi sudah dipesan!');
            }
        }

        // Hitung total harga berdasarkan kursi yang dipilih
        $totalPrice = $schedule->price * count($seats);

        // Simpan booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'schedule_id' => $schedule->id,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        $booking->bookingseat()->attach($seats);

        $seats->each(function ($seat) {
            $seat->update(['status' => 'tidak tersedia']);
        });

        if ($request->has('services')) {
            $services = Service::findMany($request->services);
            foreach ($services as $service) {
                // Menambahkan layanan ke booking dengan jumlah
                $booking->bookingservice()->attach($service->id, ['jumlah' => 1]);
                $totalPrice += $service->price; // Tambahkan harga layanan ke total
            }
        }
    
        $booking->update(['total_price' => $totalPrice]);

        return redirect()->route('user.booking.konfirmasi', ['scheduleId' => $schedule->id])
            ->with('success', 'Pemesanan Berhasil');
    }



    public function konfirmasi($scheduleId)
    {
        // Ambil semua pemesanan terakhir dari pengguna yang sedang login
        $booking = Booking::where('user_id', Auth::id())->latest()->first();

        if (!$booking) {
            return redirect()->route('user.booking.index', ['scheduleId' => $scheduleId])->withErrors('Tidak ada pemesanan yang ditemukan.');
        }

        $schedule = Schedule::with('film')->findOrFail($booking->schedule_id);
        $seat = $booking->bookingseat;
        $totalPrice = $booking->total_price;

        return view('user.booking.konfirmasi', compact('booking', 'schedule', 'seat', 'totalPrice'));
    }
}
