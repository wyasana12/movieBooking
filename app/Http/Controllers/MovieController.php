<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use Illuminate\Container\Attributes\Storage;

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
            'poster' => 'nullable|image|mimes:jpg,png,jpeg|max:1000',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'genre' => 'required|string',
            'tanggalRilis' => 'required|date'
        ]);

        if ($request->hasFile('poster')) {
            // Simpan file poster dengan nama unik di storage/public/poster
            $image = $request->file('poster');
            $imageName = $image->hashName();  // Mendapatkan nama unik untuk file
            $image->storeAs('public/poster', $imageName);  // Simpan di folder public/storage/poster
    
            // Tambahkan nama file ke data yang akan disimpan
            $validatedData['poster'] = 'poster/' . $imageName;
        } else {
            // Jika tidak ada gambar yang diunggah, biarkan null
            $validatedData['poster'] = null;
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
            'poster' => 'nullable|image|mimes:jpg,png,jpeg|max:1000',
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
