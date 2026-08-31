<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimony;
use Illuminate\Http\Request;

class TestimonyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'alumni_id' => 'required|exists:alumni,id',
            'pesan'     => 'required|string',
            'status'    => 'required|in:pending,approved',
        ]);
        Testimony::create($request->only('alumni_id', 'pesan', 'status'));
        return back()->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function update(Request $request, Testimony $testimony)
    {
        $request->validate([
            'alumni_id' => 'required|exists:alumni,id',
            'pesan'     => 'required|string',
            'status'    => 'required|in:pending,approved',
        ]);
        $testimony->update($request->only('alumni_id', 'pesan', 'status'));
        return back()->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimony $testimony)
    {
        $testimony->delete();
        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
