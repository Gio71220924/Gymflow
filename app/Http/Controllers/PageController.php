<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Member_Gym;

class PageController extends Controller
{
    public function home()
    {
        return view('home', ['key' => 'home']);
    }

    public function member()
    {
        $members = Member_Gym::orderByDesc('id')->get();

        return view('member', [
            'key'     => 'member',
            'members' => $members,
        ]);
    }

    public function class()
    {
        return view('class', ['key' => 'class']);
    }

    public function addMemberForm()
    {
        return view('add-member', ['key' => 'member']);
    }

    public function saveMember(Request $request)
    {
        $validatedData = $request->validate([
        // 1) Validasi dulu
        'id_member'             => 'required|string|max:10|unique:member_gym,id_member',
        'nama_member'           => 'required|string|max:100',
        'email_member'          => 'required|email|max:100|unique:member_gym,email_member',
        'nomor_telepon_member'  => 'required|string|max:20',
        'tanggal_lahir'         => 'required|date',
        'gender'                => 'required|in:Laki-laki,Perempuan',
        'tanggal_join'          => 'required|date',
        'membership_plan'       => 'required|in:basic,premium',
        'durasi_plan'           => 'required|integer|min:1',
        'start_date'            => 'required|date',
        'end_date'              => 'required|date|after_or_equal:start_date',
        'status_membership'     => 'required|in:Aktif,Tidak Aktif,Suspended',
        'notes'                 => 'nullable|string',
        'foto_profil'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // 2) Pastikan tidak menyimpan UploadedFile mentah ke DB
        unset($validatedData['foto_profil']);

    // 3) Upload jika ada, simpan NAMA FILE ke DB
        if ($request->hasFile('foto_profil')) {
            // Simpan ke storage/app/public/foto_profil
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            // Ambil nama file-nya saja (atau bisa simpan $path kalau mau)
            $validatedData['foto_profil'] = basename($path);
        } else {
            $validatedData['foto_profil'] = null;
        }

    // 4) Simpan
        Member_Gym::create($validatedData);

        return redirect()->route('member')->with('success', 'Member baru berhasil ditambahkan!');
    }
}