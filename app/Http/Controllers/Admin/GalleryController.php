<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'album_id' => 'required|exists:albums,id',
            'file_url' => 'required|string',
            'caption'  => 'nullable|string|max:255',
        ]);
        Gallery::create($request->only('album_id', 'file_url', 'caption'));
        return back()->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'album_id' => 'required|exists:albums,id',
            'file_url' => 'required|string',
            'caption'  => 'nullable|string|max:255',
        ]);
        $gallery->update($request->only('album_id', 'file_url', 'caption'));
        return back()->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }
}
