@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-2xl mx-auto">
            <!-- Poster di tengah -->
            <div class="flex justify-center p-4">
                <img class="w-64 h-96 object-cover rounded" src="{{ asset('storage/' . $film->poster) }}"
                    alt="{{ $film->judul }}">
            </div>

            <!-- Informasi film -->
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $film->judul }}</h1>
                <div class="text-lg text-gray-600 mb-4">{{ $film->genre }}</div>
                <p class="text-gray-700 text-base mb-4">{!! $film->deskripsi !!}</p>
                <div class="mb-2">
                    <span class="text-gray-500 font-semibold">Rilis:</span>
                    <span class="text-gray-900">{{ date('d/m/Y', strtotime($film->tanggalRilis)) }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-gray-500 font-semibold">Durasi:</span>
                    <span class="text-gray-900">{{ $film->duration }} menit</span>
                </div>
                <div class="mt-6">
                    <h3 class="mt-4 text-lg font-semibold">Jadwal untuk Film {{ $film->judul }}</h3>
                @if($schedules->isEmpty())
                    <span class="text-red-500">Jadwal tidak tersedia</span>
                @else
                    <ul>
                        @foreach ($schedules as $schedule)
                            <li>
                                Tanggal: {{ $schedule->show_date }} | Jam: {{ $schedule->start_time }}
                                <a href="{{ route('user.booking.index', $schedule->id) }}" class="ml-4 bg-blue-500 hover:bg-blue-700 font-bold py-1 px-3 rounded">
                                    Pesan Tiket
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                </div>
            </div>
        </div>
    </div>
@endsection
