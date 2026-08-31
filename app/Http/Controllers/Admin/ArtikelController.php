<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'konten'       => 'nullable|string',
            'gambar_utama' => 'nullable',
            'status'       => 'required|in:draft,published',
        ]);
        Artikel::create([
            'user_id'      => Auth::id(),
            'judul'        => $request->judul,
            'slug'         => Str::slug($request->judul) . '-' . time(),
            'konten'       => $request->konten,
            'gambar_utama' => $request->gambar_utama ?? 'https://picsum.photos/seed/art/800/400',
            'status'       => $request->status,
        ]);
        return back()->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function update(Request $request, Artikel $artikel)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'konten'       => 'nullable|string',
            'gambar_utama' => 'nullable',
            'status'       => 'required|in:draft,published',
        ]);
        $artikel->update([
            'judul'        => $request->judul,
            'slug'         => Str::slug($request->judul) . '-' . $artikel->id,
            'konten'       => $request->konten,
            'gambar_utama' => $request->gambar_utama,
            'status'       => $request->status,
        ]);
        return back()->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Artikel $artikel)
    {
        $artikel->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }
}
