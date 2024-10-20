@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Konfirmasi Pemesanan</h2>
    
    <div class="card">
        <div class="card-header">
            Detail Pemesanan
        </div>
        <div class="card-body">
            <h5>Film: {{ $schedule->film->judul }}</h5>
            <p>Tanggal dan Waktu: {{ $schedule->show_date }} - {{ $schedule->start_time }}</p>
            <p>Kursi yang Dipesan: {{ $seat->seat_number }}</p>
            <p>Total Harga: Rp {{ number_format($totalPrice, 2, ',', '.') }}</p>
            <p>Status: {{ $booking->status }}</p>
        </div>
    </div>

    <a href="{{ route('user.booking.index',  ['scheduleId' => $schedule->id]) }}" class="btn btn-primary mt-3">Kembali ke Halaman Pemesanan</a>
</div>
@endsection
