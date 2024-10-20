@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <form action="{{ route('user.film.index') }}" method="GET" class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" class="w-full px-3 py-2 border rounded-md" placeholder="Cari judul atau deskripsi" value="{{ request('search') }}">
            <select name="genre" class="w-full px-3 py-2 border rounded-md">
                <option value="">Pilih Genre</option>
                <option value="Action" {{ request('genre') == 'Action' ? 'selected' : '' }}>Action</option>
                <option value="Drama" {{ request('genre') == 'Drama' ? 'selected' : '' }}>Drama</option>
                <option value="Comedy" {{ request('genre') == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                <!-- Tambahkan opsi genre lainnya -->
            </select>
            <input type="date" name="release_date" class="w-full px-3 py-2 border rounded-md" value="{{ request('release_date') }}">
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Cari</button>
        </div>
    </form>

    <div class="flex flex-wrap -mx-2">
        @foreach ($film as $item)
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-2 mb-4">
                <img src="{{ asset('storage/' . $item->poster) }}" class="w-full h-64 object-cover" alt="{{ $item->judul }}">
                <div class="p-4">
                    <h5 class="text-lg font-semibold mb-2">{{ $item->judul }}</h5>
                    <p class="text-sm mb-2">{{ date('d F Y', strtotime($item->tanggalRilis)) }}</p>
                    <div class="flex justify-between items-center mb-2">
                        <span class="bg-blue-500 text-xs font-bold px-2 py-1 rounded">{{ $item->genre }}</span>
                    </div>
                    <p class="text-red-500 text-xs mb-4">*Advance Ticket Sales</p>
                    <a href="{{ route('user.film.show', $item->id) }}" class="block w-full bg-blue-500 text-center px-4 py-2 rounded-md hover:bg-blue-600">Lihat Detail</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $film->links() }}
    </div>
</div>
@endsection