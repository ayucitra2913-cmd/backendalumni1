<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengurusAlumni;
use Illuminate\Http\Request;

class PengurusAlumniController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'alumni_id'      => 'required|exists:alumni,id',
            'jabatan'        => 'required|string|max:100',
            'periode_mulai'  => 'nullable|date',
            'periode_selesai'=> 'nullable|date',
        ]);
        PengurusAlumni::create($request->only('alumni_id', 'jabatan', 'periode_mulai', 'periode_selesai'));
        return back()->with('success', 'Data pengurus berhasil ditambahkan.');
    }

    public function update(Request $request, PengurusAlumni $pengurusAlumni)
    {
        $request->validate([
            'alumni_id'      => 'required|exists:alumni,id',
            'jabatan'        => 'required|string|max:100',
            'periode_mulai'  => 'nullable|date',
            'periode_selesai'=> 'nullable|date',
        ]);
        $pengurusAlumni->update($request->only('alumni_id', 'jabatan', 'periode_mulai', 'periode_selesai'));
        return back()->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(PengurusAlumni $pengurusAlumni)
    {
        $pengurusAlumni->delete();
        return back()->with('success', 'Data pengurus berhasil dihapus.');
    }
}
