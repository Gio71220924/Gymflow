<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Member_Gym;
use App\User;
use Illuminate\Support\Facades\Storage;

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
        $availableUsers = User::whereDoesntHave('memberGym')->orderBy('name')->get();

        return view('add-member', [
            'key'            => 'member',
            'availableUsers' => $availableUsers,
        ]);
    }

    public function saveMember(Request $request)
    {
        $validatedData = $request->validate([
        // 1) Validasi dulu
        'user_id'               => 'required|exists:users,id|unique:member_gym,user_id',
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

    public function editMemberForm($id)
    {
        $member = Member_Gym::findOrFail($id);
        $availableUsers = User::whereDoesntHave('memberGym')
                              ->orWhere('id', $member->user_id)
                              ->orderBy('name')
                              ->get();

        return view('edit-member', [
            'key'    => 'member',
            'member' => $member,
            'availableUsers' => $availableUsers,
        ]);
    }

    public function updateMember(Request $request, $id) {
        $member = Member_Gym::findOrFail($id);

        $validated = $request->validate([
            'user_id'               => 'required|exists:users,id|unique:member_gym,user_id,' . $member->id,
            'id_member'             => 'required|string|max:10|unique:member_gym,id_member,' . $member->id,
            'nama_member'           => 'required|string|max:100',
            'email_member'          => 'required|email|max:100|unique:member_gym,email_member,' . $member->id,
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

        //Validasi input
        $member->user_id = $validated['user_id'];
        $member->id_member = $validated['id_member'];
        $member->nama_member = $validated['nama_member'];
        $member->email_member = $validated['email_member'];
        $member->nomor_telepon_member = $validated['nomor_telepon_member'];
        $member->tanggal_lahir = $validated['tanggal_lahir'];
        $member->gender = $validated['gender'];
        $member->tanggal_join = $validated['tanggal_join'];
        $member->membership_plan = $validated['membership_plan'];
        $member->durasi_plan = $validated['durasi_plan'];
        $member->start_date = $validated['start_date'];
        $member->end_date = $validated['end_date'];
        $member->status_membership = $validated['status_membership'];
        $member->notes = $validated['notes'];
       
        //Cek apakah ada file yang diupload
        if ($request->foto_profil)
        {
            if($member->foto_profil){
                //Hapus file poster lama dari storage
                Storage::disk('public')->delete('foto_profil/'.$member->foto_profil);
            }
            //Simpan file poster ke folder public/storage/poster
            $file_name = time(). '-'.$request->file('foto_profil')->getClientOriginalName();
            $path = $request->file('foto_profil')->storeAs('foto_profil', $file_name, 'public');
            $member->foto_profil = $file_name;
        }
        //Simpan perubahan
        $member->save();
        //Arahkan kembali ke halaman member
        return redirect() -> route('member')->with('success', 'Data member berhasil diperbarui!');
    }  

    public function deleteMember($id)
    {
        $member = Member_Gym::findOrFail($id);

        // Hapus file foto profil dari storage jika ada
        if ($member->foto_profil) {
            Storage::disk('public')->delete('foto_profil/' . $member->foto_profil);
        }

        // Hapus data member dari database
        $member->delete();

        return redirect()->route('member')->with('success', 'Member berhasil dihapus!');
    }
}   
