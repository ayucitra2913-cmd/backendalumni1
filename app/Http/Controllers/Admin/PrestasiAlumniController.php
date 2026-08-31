<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrestasiAlumni;
use Illuminate\Http\Request;

class PrestasiAlumniController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'alumni_id'       => 'required|exists:alumni,id',
            'nama_prestasi'   => 'required|string|max:255',
            'tingkat'         => 'nullable|string|max:100',
            'tahun_perolehan' => 'nullable|digits:4|integer',
            'deskripsi'       => 'nullable|string',
            'sertifikat_url'  => 'nullable',
        ]);
        PrestasiAlumni::create($request->only('alumni_id', 'nama_prestasi', 'tingkat', 'tahun_perolehan', 'deskripsi', 'sertifikat_url'));
        return back()->with('success', 'Data prestasi berhasil ditambahkan.');
    }

    public function update(Request $request, PrestasiAlumni $prestasiAlumni)
    {
        $request->validate([
            'alumni_id'       => 'required|exists:alumni,id',
            'nama_prestasi'   => 'required|string|max:255',
            'tingkat'         => 'nullable|string|max:100',
            'tahun_perolehan' => 'nullable|digits:4|integer',
            'deskripsi'       => 'nullable|string',
            'sertifikat_url'  => 'nullable',
        ]);
        $prestasiAlumni->update($request->only('alumni_id', 'nama_prestasi', 'tingkat', 'tahun_perolehan', 'deskripsi', 'sertifikat_url'));
        return back()->with('success', 'Data prestasi berhasil diperbarui.');
    }

    public function destroy(PrestasiAlumni $prestasiAlumni)
    {
        $prestasiAlumni->delete();
        return back()->with('success', 'Data prestasi berhasil dihapus.');
    }
}
