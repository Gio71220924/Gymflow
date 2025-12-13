<?php

namespace App\Http\Controllers;

use App\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureSuperAdmin($request);

        $trainers = Trainer::orderBy('name')->get();

        return view('trainers', [
            'key'      => 'trainers',
            'trainers' => $trainers,
        ]);
    }

    public function create(Request $request)
    {
        $this->ensureSuperAdmin($request);

        return view('trainer-form', [
            'key'   => 'trainers',
            'mode'  => 'create',
            'trainer' => null,
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureSuperAdmin($request);

        $data = $this->validatePayload($request);

        Trainer::create($data);

        return redirect()->route('trainers.index')->with('success', 'Instruktur berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $this->ensureSuperAdmin($request);

        $trainer = Trainer::findOrFail($id);

        return view('trainer-form', [
            'key'     => 'trainers',
            'mode'    => 'edit',
            'trainer' => $trainer,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->ensureSuperAdmin($request);

        $trainer = Trainer::findOrFail($id);
        $data = $this->validatePayload($request);

        $trainer->update($data);

        return redirect()->route('trainers.index')->with('success', 'Instruktur berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $this->ensureSuperAdmin($request);

        $trainer = Trainer::findOrFail($id);
        $trainer->delete();

        return redirect()->route('trainers.index')->with('success', 'Instruktur berhasil dihapus.');
    }

    private function validatePayload(Request $request)
    {
        return $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:50',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'hourly_rate'      => 'nullable|numeric|min:0',
            'status'           => 'required|in:active,inactive',
            'bio'              => 'nullable|string',
        ]);
    }

    private function ensureSuperAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Hanya admin yang dapat mengelola instruktur.');
        }
    }
}
