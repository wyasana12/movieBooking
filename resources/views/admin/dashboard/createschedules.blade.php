@extends('layouts.admin.app');

@section('content')
    <form action="{{ url('admin/schedules/store') }}" method="POST">
        @csrf
        <div class="form-group">
            <select name="film_id" required>

                @foreach ($films as $film)
                    <option value="{{ $film->id }}">{{ $film->judul }}</option>
                @endforeach

            </select>
        </div>
        
        <div class="form-group">
        <label for="start_date">Tanggal Mulai:</label>
        <input type="date" name="start_date" class="form-control" required>
        </div>

        <div class="form-group">
        <label for="end_date">Tanggal Selesai:</label>
        <input type="date" name="end_date" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="start_time">Waktu Mulai:</label>
            <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
        </div>
        <button type="submit" class="btn btn-success">Buat Jadwal Otomatis</button>
    </form>

    <ul>
        @foreach ($schedules as $schedule)
        <li>
            <p>{{ $schedule->show_date }} - {{ $schedule->start_time }} s/d {{ $schedule->end_time }}</p>
            <a href="{{ route('schedule.seats', $schedule->id) }}">Pilih Kursi</a>
        </li>
    @endforeach
    </ul>
@endsection
