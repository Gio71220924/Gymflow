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
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
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
        ]);

        // Simpan data member baru ke database
        Member_Gym::create($validatedData);

        // Redirect atau berikan respons sesuai kebutuhan
        return redirect()->route('member')->with('success', 'Member baru berhasil ditambahkan!');
    }


}