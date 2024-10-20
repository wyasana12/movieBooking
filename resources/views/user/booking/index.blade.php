@extends('layouts.app')

@section('content')
    <h2>Pilih Kursi untuk {{ $schedule->film->judul }} pada {{ $schedule->show_date }}</h2>

    <form action="{{ route('user.booking.store') }}" method="POST">
        @csrf
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

        <div class="form-group">
            <label for="seat">Pilih Kursi:</label>
            <select name="seat_id" class="form-control" required>
                @foreach ($availableSeats as $seat)
                    <option value="{{ $seat->id }}">{{ $seat->seat_number }}</option>
                @endforeach
            </select>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <button type="submit" class="btn btn-primary">Pesan</button>
    </form>
@endsection
