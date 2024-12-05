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
                <p>Kursi yang Dipesan: @foreach ($seat as $item)
                        {{ $item->seat_number }}@if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </p>
                <h5>Layanan Tambahan:</h5>
                @foreach ($booking->bookingservice as $service)
                    <p>{{ $service->nama }}
                        (Rp{{ number_format($service->pivot->jumlah * $service->price, 0, ',', '.') }})
                    </p>
                @endforeach
                <p><strong>Total Harga: Rp{{ number_format($totalPrice, 0, ',', '.') }}</strong></p>
                <p>Status: {{ $booking->status }}</p>
            </div>
        </div>

        <a href="{{ route('user.booking.index', ['scheduleId' => $schedule->id]) }}" class="btn btn-primary mt-3">Kembali ke
            Halaman Pemesanan</a>
    </div>
@endsection
