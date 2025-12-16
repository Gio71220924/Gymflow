<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


// Ini khusus USER!!!! Member gym di PageController
class UserController extends Controller
{
    public function getuser()
    {
        $users = User::orderByDesc('id')->get();

        return view('users', [
            'key'   => 'users',
            'users' => $users,
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function changePasswordForm()
    {
        return view('change-password', [
            'key'    => 'change-password',
            'member' => auth()->user()->memberGym,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'new_password'   => 'nullable|string|min:8|confirmed',
            'foto_profil'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required';
        }

        $validated = $request->validate($rules);

        $user = auth()->user();

        $passwordUpdated = false;
        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'] ?? '', $user->password)) {
                return back()->with('error', 'Password lama tidak sesuai.')->withInput();
            }

            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);
            $passwordUpdated = true;
        }

        $photoUpdated = false;
        if ($request->hasFile('foto_profil')) {
            $member = $user->memberGym;
            if (!$member) {
                return back()->with('error', 'Profil member tidak ditemukan.')->withInput();
            }

            if (!empty($member->foto_profil)) {
                Storage::disk('public')->delete('foto_profil/' . $member->foto_profil);
            }
            $fileName = time() . '-' . $request->file('foto_profil')->getClientOriginalName();
            $request->file('foto_profil')->storeAs('foto_profil', $fileName, 'public');
            $member->foto_profil = $fileName;
            $member->save();
            $photoUpdated = true;
        }

        if ($passwordUpdated && $photoUpdated) {
            return back()->with('success', 'Password dan foto profil berhasil diperbarui.');
        }
        if ($passwordUpdated) {
            return back()->with('success', 'Password berhasil diubah.');
        }
        if ($photoUpdated) {
            return back()->with('success', 'Foto profil berhasil diperbarui.');
        }

        return back()->with('success', 'Tidak ada perubahan disimpan.');
    }
}
