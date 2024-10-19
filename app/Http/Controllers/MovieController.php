<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
// use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $data['films'] = \App\Models\Film::latest()->paginate(10);
        return view('admin.dashboard.film', $data);
    }

    public function create()
    {
        return view('admin.dashboard.createfilm');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'poster' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'genre' => 'required|array',
            'tanggalRilis' => 'required|date'
        ]);

        if ($request->has('genre')) {
            $validatedData['genre'] = implode(', ', $request->input('genre'));
        }

        if ($request->hasFile('poster')) {
            $validatedData['poster'] = $request->file('poster')->store('posters', 'public');
        }

        Film::create($validatedData);

        return redirect()->route('admin.dashboard.film')->with('success', 'Film Berhasil Ditambahkan');
    }

    public function edit(Film $film)
    {
        return view('admin.dashboard.editfilm', compact('film'));
    }

    public function update(Request $request, Film $film)
    {
        $validatedData = $request->validate([
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'genre' => 'required|string',
            'tanggalRilis' => 'required|date'
        ]);

        if ($request->hasFile('poster')) {
            // Hapus poster lama
            if ($film->poster) {
                Storage::delete('public/' . $film->poster);
            }
            $validatedData['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $film->update($validatedData);

        $film->update($request->all());

        return redirect()->route('admin.dashboard.film')->with('success', 'Film Telah Terupdate');
    }

    public function destroy($id)
    {
        $film = film::findorfail($id);

        $film->delete();
        return redirect()->route('admin.dashboard.film')->with('success', 'Film Berhasil Dihapus');
    }
}
