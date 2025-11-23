<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Member_Gym;
use App\User;
use App\MembershipPlan;
use App\MemberMembership;
use App\Invoice;
use App\Payment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function home()
    {
        $today = Carbon::today();
        $next7 = $today->copy()->addDays(7);

        $totalMembers   = Member_Gym::count();
        $activeMembers  = Member_Gym::where('status_membership', 'Aktif')->count();
        $inactiveMembers = Member_Gym::where('status_membership', 'Tidak Aktif')->count();
        $expiringSoon   = Member_Gym::whereBetween('end_date', [$today, $next7])->count();
        $newThisMonth   = Member_Gym::whereBetween('tanggal_join', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])->count();

        $planCounts = Member_Gym::select('membership_plan', DB::raw('COUNT(*) as total'))
            ->groupBy('membership_plan')
            ->pluck('total', 'membership_plan');

        $statusCounts = Member_Gym::select('status_membership', DB::raw('COUNT(*) as total'))
            ->groupBy('status_membership')
            ->pluck('total', 'status_membership');

        $trendRows = Member_Gym::select(
                DB::raw('DATE_FORMAT(tanggal_join, "%Y-%m") as ym'),
                DB::raw('COUNT(*) as total')
            )
            ->where('tanggal_join', '>=', $today->copy()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $joinTrend = [
            'labels' => [],
            'values' => [],
        ];
        foreach ($trendRows as $row) {
            try {
                $label = Carbon::createFromFormat('Y-m', $row->ym)->format('M Y');
            } catch (\Throwable $e) {
                $label = $row->ym;
            }
            $joinTrend['labels'][] = $label;
            $joinTrend['values'][] = (int) $row->total;
        }

        $paidInvoices      = Invoice::where('status', 'lunas')->count();
        $pendingInvoices   = Invoice::where('status', 'menunggu')->count();
        $totalPaidAmount   = Invoice::where('status', 'lunas')->sum('total_tagihan');
        $outstandingAmount = Invoice::whereIn('status', ['menunggu', 'draft'])->sum('total_tagihan');
        $recentInvoices    = Invoice::with(['memberMembership.member'])
                                ->latest()
                                ->take(5)
                                ->get();

        return view('home', [
            'key'             => 'home',
            'totalMembers'    => $totalMembers,
            'activeMembers'   => $activeMembers,
            'inactiveMembers' => $inactiveMembers,
            'expiringSoon'    => $expiringSoon,
            'newThisMonth'    => $newThisMonth,
            'planCounts'      => $planCounts,
            'statusCounts'    => $statusCounts,
            'joinTrend'       => $joinTrend,
            'paidInvoices'    => $paidInvoices,
            'pendingInvoices' => $pendingInvoices,
            'totalPaidAmount' => $totalPaidAmount,
            'outstandingAmount' => $outstandingAmount,
            'recentInvoices'  => $recentInvoices,
        ]);
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


    public function settings()
    {
        return view('settings', ['key' => 'settings']);
    }

    public function billing()
    {
        $invoices = Invoice::with([
            'memberMembership.member',
            'memberMembership.plan',
            'payments' => function ($q) {
                $q->latest('paid_at')->latest('id');
            },
        ])->orderByDesc('id')->get();

        // Recalc invoice total jika ada yang 0/meleset
        foreach ($invoices as $inv) {
            $this->recalcInvoiceTotal($inv);
        }

        // Recompute summary after recalculation
        $summary = [
            'total'         => $invoices->count(),
            'lunas'         => $invoices->where('status', 'lunas')->count(),
            'menunggu'      => $invoices->where('status', 'menunggu')->count(),
            'draft'         => $invoices->where('status', 'draft')->count(),
            'batal'         => $invoices->where('status', 'batal')->count(),
            'total_amount'  => $invoices->sum('total_tagihan'),
        ];

        return view('billing', [
            'key'       => 'billing',
            'invoices'  => $invoices,
            'summary'   => $summary,
        ]);
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
        'end_date'              => 'nullable|date|after_or_equal:start_date',
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

    // 4) Hitung end_date dari durasi_plan + start_date
        $start = Carbon::parse($validatedData['start_date']);
        $durasi = max(1, (int) $validatedData['durasi_plan']);
        $validatedData['end_date'] = $start->copy()->addMonths($durasi)->toDateString();

    // 5) Simpan
        $member = Member_Gym::create($validatedData);
        $this->syncMembershipAndInvoice($member, $validatedData);

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
            'end_date'              => 'nullable|date|after_or_equal:start_date',
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
        $start = Carbon::parse($validated['start_date']);
        $durasi = max(1, (int) $validated['durasi_plan']);
        $validated['end_date'] = $start->copy()->addMonths($durasi)->toDateString();

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
        $this->syncMembershipAndInvoice($member, $validated);
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

    private function syncMembershipAndInvoice(Member_Gym $member, array $data)
    {
        $plan = $this->resolvePlanFromName($data['membership_plan'] ?? null, $data['durasi_plan'] ?? 1);

        if (!$plan) {
            return;
        }

        $membershipData = [
            'member_id'          => $member->id,
            'plan_id'            => $plan->id,
            'start_date'         => $data['start_date'] ?? null,
            'end_date'           => $data['end_date'] ?? null,
            'status'             => $this->mapStatusMembership($data['status_membership'] ?? null),
            'pembayaran_status'  => 'menunggu',
            'catatan'            => $data['notes'] ?? null,
        ];

        $membership = $member->memberMemberships()->latest('id')->first();

        if ($membership) {
            $membership->update($membershipData);
            $created = false;
        } else {
            $membership = MemberMembership::create($membershipData);
            $created = true;
        }

        if ($created) {
            $this->createInvoiceForMembership($membership, $plan, $data);
        }
    }

    private function createInvoiceForMembership(MemberMembership $membership, MembershipPlan $plan, array $data)
    {
        $invoice = new Invoice([
            'member_membership_id' => $membership->id,
            'nomor_invoice'        => $this->generateInvoiceNumber(),
            'due_date'             => $membership->end_date ?? ($data['end_date'] ?? null),
            'total_tagihan'        => $this->calculateTotalTagihan($plan, $membership, $data['durasi_plan'] ?? 1),
            'diskon'               => 0,
            'pajak'                => 0,
            'status'               => 'menunggu',
            'catatan'              => $data['notes'] ?? null,
        ]);

        $invoice->save();
    }

    private function calculateTotalTagihan(MembershipPlan $plan, MemberMembership $membership, $durasiInput)
    {
        // Hitung durasi berdasar tanggal start-end membership
        $durationMonths = null;
        if ($membership->start_date && $membership->end_date) {
            $durationMonths = Carbon::parse($membership->start_date)
                ->diffInMonths(Carbon::parse($membership->end_date));
            $durationMonths = max(1, $durationMonths);
        }

        $durasi = $durationMonths ?? max(1, (int) $durasiInput);

        // Harga fallback jika plan harga belum diisi atau tidak sesuai default
        $price = $this->defaultPlanPrice($plan->nama);
        if (!$price || $price <= 0) {
            $price = $plan->harga;
        }
        if (!$price || $price <= 0) {
            $price = 0;
        }

        return $price * $durasi;
    }

    private function recalcInvoiceTotal(Invoice $invoice)
    {
        $membership = $invoice->memberMembership;
        if (!$membership) {
            return;
        }

        $plan = $membership->plan;
        if (!$plan && $membership->plan_id) {
            $plan = MembershipPlan::find($membership->plan_id);
        }
        if (!$plan) {
            return;
        }

        $recalc = $this->calculateTotalTagihan($plan, $membership, 1);

        if ($recalc > 0 && $invoice->total_tagihan != $recalc) {
            $invoice->total_tagihan = $recalc;
            $invoice->save();
        }
    }

    private function resolvePlanFromName(?string $planName, $durasiPlan)
    {
        if (!$planName) {
            return null;
        }

        $durasi = max(1, (int) $durasiPlan);
        $defaults = [
            'basic'   => ['harga' => 150000, 'durasi_bulan' => $durasi],
            'premium' => ['harga' => 300000, 'durasi_bulan' => $durasi],
        ];

        $key = strtolower($planName);
        $price = $defaults[$key]['harga'] ?? 0;
        $duration = $defaults[$key]['durasi_bulan'] ?? $durasi;

        $plan = MembershipPlan::firstOrCreate(
            ['nama' => $planName],
            [
                'harga'         => $price,
                'durasi_bulan'  => $duration,
                'benefit'       => null,
                'status'        => 'aktif',
            ]
        );

        // Jika plan sudah ada namun harga 0, perbarui dengan default
        if (($plan->harga ?? 0) <= 0 && $price > 0) {
            $plan->harga = $price;
            $plan->durasi_bulan = $duration;
            $plan->save();
        }

        return $plan;
    }

    private function mapStatusMembership(?string $status)
    {
        switch ($status) {
            case 'Aktif':
                return 'aktif';
            case 'Suspended':
                return 'dibatalkan';
            case 'Tidak Aktif':
            default:
                return 'selesai';
        }
    }

    private function generateInvoiceNumber()
    {
        $prefix = 'INV-' . Carbon::now()->format('Ymd');

        do {
            $candidate = $prefix . '-' . strtoupper(Str::random(5));
        } while (Invoice::where('nomor_invoice', $candidate)->exists());

        return $candidate;
    }

    private function defaultPlanPrice(?string $name)
    {
        $key = strtolower($name ?? '');
        if ($key === 'basic') {
            return 150000;
        }
        if ($key === 'premium') {
            return 300000;
        }
        return 0;
    }

    public function printInvoice($id)
    {
        $invoice = Invoice::with([
            'memberMembership.member',
            'memberMembership.plan',
            'payments' => function ($q) {
                $q->latest('paid_at')->latest('id');
            },
        ])->findOrFail($id);

        return view('invoice-print', [
            'invoice' => $invoice,
        ]);
    }


    public function updateInvoiceStatus(Request $request, $id)
    {
        $invoice = Invoice::with('memberMembership')->findOrFail($id);

        $data = $request->validate([
            'status'          => 'required|in:draft,menunggu,lunas,batal',
            'payment_method'  => 'nullable|in:cash,transfer,ewallet,credit_card',
            'amount'          => 'nullable|numeric|min:0',
        ]);

        $invoice->status = $data['status'];
        $invoice->save();

        $membership = $invoice->memberMembership;
        if ($membership) {
            if ($data['status'] === 'lunas') {
                $membership->pembayaran_status = 'lunas';
            } elseif ($data['status'] === 'menunggu') {
                $membership->pembayaran_status = 'menunggu';
            } else {
                $membership->pembayaran_status = 'gagal';
            }
            $membership->save();
        }

        if ($data['status'] === 'lunas') {
            $method = $data['payment_method'] ?? 'cash';
            $amount = $data['amount'] ?? $invoice->total_tagihan;

            Payment::create([
                'invoice_id'   => $invoice->id,
                'amount'       => $amount,
                'method'       => $method,
                'paid_at'      => Carbon::now(),
                'status'       => 'berhasil',
                'bukti_bayar'  => null,
                'reference_no' => null,
                'catatan'      => 'Updated by admin',
            ]);
        }

        return redirect()->route('billing')->with('success', 'Status invoice berhasil diperbarui.');
    }

}
