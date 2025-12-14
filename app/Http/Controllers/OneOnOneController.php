<?php

namespace App\Http\Controllers;

use App\Member_Gym;
use App\MemberMembership;
use App\OneOnOneRequest;
use App\Trainer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OneOnOneController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== User::ROLE_USER) {
            abort(403, 'Hanya member yang dapat mengajukan sesi.');
        }

        $member = $user->memberGym;
        if (!$member) {
            return back()->with('error', 'Lengkapi profil member terlebih dahulu.');
        }

        $latestMembership = $member->memberMemberships()->latest('id')->first();
        if ($this->isMembershipExpired($latestMembership, $member)) {
            return back()->with('error', 'Membership Anda sudah berakhir. Perpanjang sebelum mengajukan sesi.');
        }

        $latestInvoice = $latestMembership ? $latestMembership->invoices()->latest('id')->first() : null;
        if ($latestInvoice && $latestInvoice->status !== 'lunas') {
            return back()->with('error', 'Tagihan terakhir belum lunas. Selesaikan pembayaran sebelum mengajukan sesi.');
        }

        $data = $request->validate([
            'trainer_id'      => 'required|integer|exists:trainers,id',
            'preferred_date'  => 'required|date|after_or_equal:today',
            'preferred_time'  => 'required|string|max:16',
            'location'        => 'required|string|max:255',
            'note'            => 'nullable|string|max:1000',
        ]);

        $timezone = env('APP_TIMEZONE') ?: (config('app.timezone') !== 'UTC' ? config('app.timezone') : 'Asia/Jakarta');
        $requestedDateTime = Carbon::createFromFormat('Y-m-d H:i', $data['preferred_date'] . ' ' . $data['preferred_time'], $timezone);
        $now = Carbon::now($timezone);
        if ($requestedDateTime->lt($now)) {
            return back()
                ->withInput()
                ->withErrors(['preferred_time' => 'Waktu harus setelah waktu sekarang.']);
        }

        OneOnOneRequest::create([
            'member_id'      => $member->id,
            'trainer_id'     => $data['trainer_id'],
            'preferred_date' => $data['preferred_date'],
            'preferred_time' => $data['preferred_time'],
            'location'       => $data['location'],
            'note'           => $data['note'] ?? null,
            'status'         => OneOnOneRequest::STATUS_PENDING,
        ]);

        return back()->with('success', 'Permintaan sesi one-on-one dikirim. Admin akan meninjau.');
    }

    public function approve(Request $request, $id)
    {
        $this->ensureAdmin($request);
        $req = OneOnOneRequest::findOrFail($id);

        $req->status = OneOnOneRequest::STATUS_APPROVED;
        $req->admin_note = $request->input('admin_note');
        $req->approved_by = $request->user()->id;
        $req->save();

        return back()->with('success', 'Permintaan one-on-one disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $this->ensureAdmin($request);
        $req = OneOnOneRequest::findOrFail($id);

        $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $req->status = OneOnOneRequest::STATUS_REJECTED;
        $req->admin_note = $request->input('admin_note');
        $req->approved_by = $request->user()->id;
        $req->save();

        return back()->with('success', 'Permintaan one-on-one ditolak.');
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $req = OneOnOneRequest::findOrFail($id);
        if (!$user || $user->role !== User::ROLE_USER || $req->member_id !== optional($user->memberGym)->id) {
            abort(403, 'Tidak diizinkan.');
        }
        if ($req->status !== OneOnOneRequest::STATUS_PENDING) {
            return back()->with('error', 'Pengajuan sudah diproses, tidak dapat diubah.');
        }

        $data = $request->validate([
            'trainer_id'      => 'required|integer|exists:trainers,id',
            'preferred_date'  => 'required|date|after_or_equal:today',
            'preferred_time'  => 'required|date_format:H:i',
            'location'        => 'required|string|max:255',
        ]);

        $timezone = env('APP_TIMEZONE') ?: (config('app.timezone') !== 'UTC' ? config('app.timezone') : 'Asia/Jakarta');
        $requestedDateTime = Carbon::createFromFormat('Y-m-d H:i', $data['preferred_date'] . ' ' . $data['preferred_time'], $timezone);
        $now = Carbon::now($timezone);
        if ($requestedDateTime->lt($now)) {
            return back()
                ->withInput()
                ->withErrors(['preferred_time' => 'Waktu harus setelah waktu sekarang.']);
        }

        $req->update($data);

        return back()->with('success', 'Pengajuan one-on-one diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $req = OneOnOneRequest::findOrFail($id);
        if (!$user || $user->role !== User::ROLE_USER || $req->member_id !== optional($user->memberGym)->id) {
            abort(403, 'Tidak diizinkan.');
        }
        if ($req->status !== OneOnOneRequest::STATUS_PENDING) {
            return back()->with('error', 'Pengajuan sudah diproses, tidak dapat dibatalkan.');
        }

        $req->delete();

        return back()->with('success', 'Pengajuan one-on-one dibatalkan.');
    }

    private function ensureAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Hanya admin yang dapat memproses pengajuan.');
        }
    }

    private function isMembershipExpired($membership = null, $member = null)
    {
        $endDate = optional($membership)->end_date ?? optional($member)->end_date;
        if (!$endDate) {
            return false;
        }

        return Carbon::parse($endDate)->endOfDay()->isPast();
    }
}
