<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'key_identifier' => 'required|string|max:100|unique:contents,key_identifier',
            'judul'          => 'nullable|string|max:255',
            'isi'            => 'nullable|string',
            'gambar'         => 'nullable',
        ]);
        Content::create($request->only('key_identifier', 'judul', 'isi', 'gambar'));
        return back()->with('success', 'Konten berhasil ditambahkan.');
    }

    public function update(Request $request, Content $content)
    {
        $request->validate([
            'key_identifier' => 'required|string|max:100|unique:contents,key_identifier,' . $content->id,
            'judul'          => 'nullable|string|max:255',
            'isi'            => 'nullable|string',
            'gambar'         => 'nullable',
        ]);
        $content->update($request->only('key_identifier', 'judul', 'isi', 'gambar'));
        return back()->with('success', 'Konten berhasil diperbarui.');
    }

    public function destroy(Content $content)
    {
        $content->delete();
        return back()->with('success', 'Konten berhasil dihapus.');
    }
}
