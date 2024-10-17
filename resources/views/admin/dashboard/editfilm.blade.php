@extends('layouts.admin.app')

@section('content')
    <h1> Edit Film </h1>
    <form action="{{ url('/admin/movies/update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="judul">Judul</label>
            <input type="text" class="form-control" id="judul" name="judul"
                value="{{ old('judul', $film->judul ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea class="form-control" name="deskripsi" rows="5">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" class="form-control" id="genre" name="genre"
                value="{{ old('genre', $film->genre ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="tanggalRilis">Tanggal Rilis</label>
            <input type="date" class="form-control" id="tanggalRilis" name="tanggalRilis"
                value="{{ old('tanggalRilis', $film->tanggalRilis ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="poster">Poster</label>
            <input type="file" class="form-control" id="poster" name="poster">
        </div>

        <button type="submit" class="btn btn-success">Submit</button>
    </form>
    <script>
        CKEDITOR.replace('description');
    </script>
@endsection
