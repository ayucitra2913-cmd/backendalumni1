<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_album'  => 'required|string|max:200',
            'deskripsi'   => 'nullable|string',
            'cover_image' => 'nullable',
        ]);
        Album::create([
            'user_id'     => Auth::id(),
            'nama_album'  => $request->nama_album,
            'deskripsi'   => $request->deskripsi,
            'cover_image' => $request->cover_image ?? 'https://picsum.photos/seed/album/400/300',
        ]);
        return back()->with('success', 'Album berhasil ditambahkan.');
    }

    public function update(Request $request, Album $album)
    {
        $request->validate([
            'nama_album'  => 'required|string|max:200',
            'deskripsi'   => 'nullable|string',
            'cover_image' => 'nullable',
        ]);
        $album->update($request->only('nama_album', 'deskripsi', 'cover_image'));
        return back()->with('success', 'Album berhasil diperbarui.');
    }

    public function destroy(Album $album)
    {
        $album->delete();
        return back()->with('success', 'Album berhasil dihapus.');
    }
}
