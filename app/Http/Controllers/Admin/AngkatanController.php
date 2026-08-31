<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use Illuminate\Http\Request;

class AngkatanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tahun_angkatan' => 'required|string|max:10',
            'nama_angkatan'  => 'required|string|max:100',
        ]);
        Angkatan::create($request->only('tahun_angkatan', 'nama_angkatan'));
        return back()->with('success', 'Data angkatan berhasil ditambahkan.');
    }

    public function update(Request $request, Angkatan $angkatan)
    {
        $request->validate([
            'tahun_angkatan' => 'required|string|max:10',
            'nama_angkatan'  => 'required|string|max:100',
        ]);
        $angkatan->update($request->only('tahun_angkatan', 'nama_angkatan'));
        return back()->with('success', 'Data angkatan berhasil diperbarui.');
    }

    public function destroy(Angkatan $angkatan)
    {
        $angkatan->delete();
        return back()->with('success', 'Data angkatan berhasil dihapus.');
    }
}
