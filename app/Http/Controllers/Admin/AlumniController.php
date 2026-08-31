<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'       => 'required|string|max:150',
            'jenis_kelamin'      => 'required|in:L,P',
            'angkatan_id'        => 'nullable|exists:angkatan,id',
            'kelas_id'           => 'nullable|exists:kelas,id',
            'nisn'               => 'nullable|string|max:20',
            'telepon'            => 'nullable|string|max:20',
            'alamat'             => 'nullable|string',
            'pekerjaan_saat_ini' => 'nullable|string|max:200',
            'foto_profil'        => 'nullable|url',
        ]);
        Alumni::create($request->only([
            'user_id', 'angkatan_id', 'kelas_id', 'nisn',
            'nama_lengkap', 'jenis_kelamin', 'telepon', 'alamat',
            'pekerjaan_saat_ini', 'foto_profil',
        ]));
        return back()->with('success', 'Data alumni berhasil ditambahkan.');
    }

    public function update(Request $request, Alumni $alumni)
    {
        $request->validate([
            'nama_lengkap'       => 'required|string|max:150',
            'jenis_kelamin'      => 'required|in:L,P',
            'angkatan_id'        => 'nullable|exists:angkatan,id',
            'kelas_id'           => 'nullable|exists:kelas,id',
            'nisn'               => 'nullable|string|max:20',
            'telepon'            => 'nullable|string|max:20',
            'alamat'             => 'nullable|string',
            'pekerjaan_saat_ini' => 'nullable|string|max:200',
            'foto_profil'        => 'nullable',
        ]);
        $alumni->update($request->only([
            'user_id', 'angkatan_id', 'kelas_id', 'nisn',
            'nama_lengkap', 'jenis_kelamin', 'telepon', 'alamat',
            'pekerjaan_saat_ini', 'foto_profil',
        ]));
        return back()->with('success', 'Data alumni berhasil diperbarui.');
    }

    public function destroy(Alumni $alumni)
    {
        $alumni->delete();
        return back()->with('success', 'Data alumni berhasil dihapus.');
    }
}
