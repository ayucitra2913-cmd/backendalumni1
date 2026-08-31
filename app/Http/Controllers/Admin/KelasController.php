<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'angkatan_id' => 'required|exists:angkatan,id',
            'nama_kelas'  => 'required|string|max:100',
        ]);
        Kelas::create($request->only('angkatan_id', 'nama_kelas'));
        return back()->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'angkatan_id' => 'required|exists:angkatan,id',
            'nama_kelas'  => 'required|string|max:100',
        ]);
        $kelas->update($request->only('angkatan_id', 'nama_kelas'));
        return back()->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return back()->with('success', 'Data kelas berhasil dihapus.');
    }
}
