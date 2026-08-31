<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcaraController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_acara'      => 'required|string|max:200',
            'deskripsi'       => 'nullable|string',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'lokasi'          => 'nullable|string|max:255',
            'banner_image'    => 'nullable',
        ]);
        Acara::create([
            'user_id'         => Auth::id(),
            'nama_acara'      => $request->nama_acara,
            'deskripsi'       => $request->deskripsi,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi'          => $request->lokasi,
            'banner_image'    => $request->banner_image ?? 'https://picsum.photos/seed/acara/800/400',
        ]);
        return back()->with('success', 'Acara berhasil ditambahkan.');
    }

    public function update(Request $request, Acara $acara)
    {
        $request->validate([
            'nama_acara'      => 'required|string|max:200',
            'deskripsi'       => 'nullable|string',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'lokasi'          => 'nullable|string|max:255',
            'banner_image'    => 'nullable',
        ]);
        $acara->update($request->only('nama_acara', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'lokasi', 'banner_image'));
        return back()->with('success', 'Acara berhasil diperbarui.');
    }

    public function destroy(Acara $acara)
    {
        $acara->delete();
        return back()->with('success', 'Acara berhasil dihapus.');
    }
}
