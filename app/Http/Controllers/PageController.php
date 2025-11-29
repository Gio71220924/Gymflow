<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member_Gym;
use App\User;
use App\MembershipPlan;
use App\MemberMembership;
use App\Invoice;
use App\Payment;
use App\AppSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PageController extends Controller
{
    private $appSettings;

    public function __construct()
    {
        $this->appSettings = $this->getSettings();
        view()->share('appSettings', $this->appSettings);
    }

    public function landing()
    {
        return view('landingpage', [
            'key' => 'landingpage',
        ]);
    }
    
    public function home()
    {
        $today = Carbon::today();
        $next7 = $today->copy()->addDays(7);

        $totalMembers     = Member_Gym::count();
        $activeMembers    = Member_Gym::where('status_membership', 'Aktif')->count();
        $inactiveMembers  = Member_Gym::where('status_membership', 'Tidak Aktif')->count();
        $expiringSoon     = Member_Gym::whereBetween('end_date', [$today, $next7])->count();
        $newThisMonth     = Member_Gym::whereBetween('tanggal_join', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])->count();

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

        $joinTrend = ['labels' => [], 'values' => []];
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
            'key'               => 'home',
            'totalMembers'      => $totalMembers,
            'activeMembers'     => $activeMembers,
            'inactiveMembers'   => $inactiveMembers,
            'expiringSoon'      => $expiringSoon,
            'newThisMonth'      => $newThisMonth,
            'planCounts'        => $planCounts,
            'statusCounts'      => $statusCounts,
            'joinTrend'         => $joinTrend,
            'paidInvoices'      => $paidInvoices,
            'pendingInvoices'   => $pendingInvoices,
            'totalPaidAmount'   => $totalPaidAmount,
            'outstandingAmount' => $outstandingAmount,
            'recentInvoices'    => $recentInvoices,
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

    // Ganti nama method ini dari "class" -> "classPage"
    public function classPage()
    {
        return view('class', ['key' => 'class']);
    }

    /* ======================== BILLING ======================== */

    public function billing()
    {
        $invoices = Invoice::with([
            'memberMembership.member',
            'memberMembership.plan',
            'payments' => function ($q) {
                $q->latest('paid_at')->latest('id');
            },
        ])->orderByDesc('id')->get();

        foreach ($invoices as $inv) {
            $this->recalcInvoiceTotal($inv);
        }

        $summary = [
            'total'         => $invoices->count(),
            'lunas'         => $invoices->where('status', 'lunas')->count(),
            'menunggu'      => $invoices->where('status', 'menunggu')->count(),
            'draft'         => $invoices->where('status', 'draft')->count(),
            'batal'         => $invoices->where('status', 'batal')->count(),
            'total_amount'  => $invoices->sum('total_tagihan'),
        ];

        return view('billing', [
            'key'      => 'billing',
            'invoices' => $invoices,
            'summary'  => $summary,
        ]);
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

        return view('invoice-print', ['invoice' => $invoice]);
    }

    public function updateInvoiceStatus(Request $request, $id)
    {
        $invoice = Invoice::with('memberMembership')->findOrFail($id);

        $data = $request->validate([
            'status'         => 'required|in:draft,menunggu,lunas,batal',
            'payment_method' => 'nullable|in:cash,transfer,ewallet,credit_card',
            'amount'         => 'nullable|numeric|min:0',
        ]);

        $invoice->status = $data['status'];
        $invoice->save();

        $membership = $invoice->memberMembership;
        if ($membership) {
            $membership->pembayaran_status = $data['status'] === 'lunas'
                ? 'lunas'
                : ($data['status'] === 'menunggu' ? 'menunggu' : 'gagal');
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

    /* ======================== SETTINGS ======================== */

    public function settings()
    {
        $settings = $this->getSettings();
        return view('settings', ['key' => 'settings', 'settings' => $settings]);
    }

    public function saveSettings(Request $request)
    {
        $section = $request->input('section');
        if ($section === 'billing') {
            $payload = $request->validate([
                'billing_basic_price'      => 'nullable|numeric|min:0',
                'billing_premium_price'    => 'nullable|numeric|min:0',
                'billing_due_days'         => 'nullable|numeric|min:0',
                'billing_invoice_format'   => 'nullable|string|max:100',
                'billing_default_tax'      => 'nullable|numeric|min:0',
                'billing_default_discount' => 'nullable|numeric|min:0',
                'billing_methods'          => 'nullable|array',
                'billing_methods.*'        => 'string',
            ]);
            $this->saveSetting('billing_basic_price', $payload['billing_basic_price'] ?? 150000);
            $this->saveSetting('billing_premium_price', $payload['billing_premium_price'] ?? 300000);
            $this->saveSetting('billing_due_days', $payload['billing_due_days'] ?? 30);
            $this->saveSetting('billing_invoice_format', $payload['billing_invoice_format'] ?? 'INV-{YYYYMMDD}-{RAND5}');
            $this->saveSetting('billing_default_tax', $payload['billing_default_tax'] ?? 0);
            $this->saveSetting('billing_default_discount', $payload['billing_default_discount'] ?? 0);
            $methods = $payload['billing_methods'] ?? ['cash','transfer','ewallet','credit_card'];
            $this->saveSetting('billing_methods', implode(',', $methods));
        } elseif ($section === 'branding') {
            $payload = $request->validate([
                'branding_name'    => 'nullable|string|max:100',
                'branding_tagline' => 'nullable|string|max:150',
                'branding_color'   => 'nullable|string|max:20',
                'branding_address' => 'nullable|string|max:255',
                'branding_logo'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            ]);
            $this->saveSetting('branding_name', $payload['branding_name'] ?? 'GymFlow');
            $this->saveSetting('branding_tagline', $payload['branding_tagline'] ?? '');
            $this->saveSetting('branding_color', $payload['branding_color'] ?? '#FC7753');
            $this->saveSetting('branding_address', $payload['branding_address'] ?? '');

            if ($request->hasFile('branding_logo')) {
                $fileName = time() . '-' . $request->file('branding_logo')->getClientOriginalName();
                $request->file('branding_logo')->storeAs('branding', $fileName, 'public');
                $this->saveSetting('branding_logo', $fileName);
            }
        }

        return redirect()->route('settings')->with('success', 'Settings tersimpan.');
    }

    /* ======================== MEMBER ======================== */

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
        $validated = $request->validate([
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

        // Upload foto jika ada (simpan nama file saja)
        $fotoFileName = null;
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $fotoFileName = basename($path);
        }

        // Hitung end_date dari start + durasi
        $start  = Carbon::parse($validated['start_date']);
        $durasi = max(1, (int) $validated['durasi_plan']);
        $validated['end_date'] = $start->copy()->addMonths($durasi)->toDateString();

        $member = Member_Gym::create(array_merge($validated, [
            'foto_profil' => $fotoFileName,
        ]));

        $this->syncMembershipAndInvoice($member, $validated);

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
            'key'            => 'member',
            'member'         => $member,
            'availableUsers' => $availableUsers,
        ]);
    }

    public function updateMember(Request $request, $id)
    {
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

        // Update field non-file
        $member->user_id              = $validated['user_id'];
        $member->id_member            = $validated['id_member'];
        $member->nama_member          = $validated['nama_member'];
        $member->email_member         = $validated['email_member'];
        $member->nomor_telepon_member = $validated['nomor_telepon_member'];
        $member->tanggal_lahir        = $validated['tanggal_lahir'];
        $member->gender               = $validated['gender'];
        $member->tanggal_join         = $validated['tanggal_join'];
        $member->membership_plan      = $validated['membership_plan'];
        $member->durasi_plan          = $validated['durasi_plan'];

        // Rehitung end_date dari start + durasi
        $start  = Carbon::parse($validated['start_date']);
        $durasi = max(1, (int) $validated['durasi_plan']);
        $validated['end_date'] = $start->copy()->addMonths($durasi)->toDateString();

        $member->start_date        = $validated['start_date'];
        $member->end_date          = $validated['end_date'];
        $member->status_membership = $validated['status_membership'];
        $member->notes             = $validated['notes'] ?? null;

        // Jika ada file baru, hapus lama & simpan baru
        if ($request->hasFile('foto_profil')) {
            if (!empty($member->foto_profil)) {
                Storage::disk('public')->delete('foto_profil/' . $member->foto_profil);
            }
            $fileName = time() . '-' . $request->file('foto_profil')->getClientOriginalName();
            $request->file('foto_profil')->storeAs('foto_profil', $fileName, 'public');
            $member->foto_profil = $fileName;
        }

        $member->save();

        // Sinkron membership & invoice
        $this->syncMembershipAndInvoice($member, $validated);

        return redirect()->route('member')->with('success', 'Data member berhasil diperbarui!');
    }

    public function deleteMember($id)
    {
        $member = Member_Gym::findOrFail($id);

        if (!empty($member->foto_profil) && $member->foto_profil !== 'noimage.png') {
            $path = 'foto_profil/' . $member->foto_profil;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $member->delete();

        return redirect()->route('member')->with('success', 'Member berhasil dihapus!');
    }

    /* ======================== HELPERS ======================== */

    private function syncMembershipAndInvoice(Member_Gym $member, array $data)
    {
        $plan = $this->resolvePlanFromName($data['membership_plan'] ?? null, $data['durasi_plan'] ?? 1);
        if (!$plan) return;

        $membershipData = [
            'member_id'         => $member->id,
            'plan_id'           => $plan->id,
            'start_date'        => $data['start_date'] ?? null,
            'end_date'          => $data['end_date'] ?? null,
            'status'            => $this->mapStatusMembership($data['status_membership'] ?? null),
            'pembayaran_status' => 'menunggu',
            'catatan'           => $data['notes'] ?? null,
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
        $dueDays = (int) $this->getSettingValue('billing_due_days', 0);
        $dueDate = $membership->end_date ?? ($data['end_date'] ?? null);
        if (!$dueDate && $membership->start_date && $dueDays > 0) {
            $dueDate = Carbon::parse($membership->start_date)->addDays($dueDays)->toDateString();
        }

        $invoice = new Invoice([
            'member_membership_id' => $membership->id,
            'nomor_invoice'        => $this->generateInvoiceNumber(),
            'due_date'             => $dueDate,
            'total_tagihan'        => $this->calculateTotalTagihan($plan, $membership, $data['durasi_plan'] ?? 1),
            'diskon'               => (int) $this->getSettingValue('billing_default_discount', 0),
            'pajak'                => (int) $this->getSettingValue('billing_default_tax', 0),
            'status'               => 'menunggu',
            'catatan'              => $data['notes'] ?? null,
        ]);

        $invoice->save();
    }

    private function calculateTotalTagihan(MembershipPlan $plan, MemberMembership $membership, $durasiInput)
    {
        $durationMonths = null;
        if ($membership->start_date && $membership->end_date) {
            $durationMonths = Carbon::parse($membership->start_date)
                ->diffInMonths(Carbon::parse($membership->end_date));
            $durationMonths = max(1, $durationMonths);
        }

        $durasi = $durationMonths ?? max(1, (int) $durasiInput);

        $price = $this->defaultPlanPrice($plan->nama);
        if (!$price || $price <= 0) $price = $plan->harga ?: 0;

        return $price * $durasi;
    }

    private function recalcInvoiceTotal(Invoice $invoice)
    {
        $membership = $invoice->memberMembership;
        if (!$membership) return;

        $plan = $membership->plan ?: ($membership->plan_id ? MembershipPlan::find($membership->plan_id) : null);
        if (!$plan) return;

        $recalc = $this->calculateTotalTagihan($plan, $membership, 1);
        if ($recalc > 0 && $invoice->total_tagihan != $recalc) {
            $invoice->total_tagihan = $recalc;
            $invoice->save();
        }
    }

    private function resolvePlanFromName(?string $planName, $durasiPlan)
    {
        if (!$planName) return null;

        $durasi   = max(1, (int) $durasiPlan);
        $defaults = [
            'basic'   => ['harga' => 150000, 'durasi_bulan' => $durasi],
            'premium' => ['harga' => 300000, 'durasi_bulan' => $durasi],
        ];

        $key      = strtolower($planName);
        $price    = $defaults[$key]['harga'] ?? 0;
        $duration = $defaults[$key]['durasi_bulan'] ?? $durasi;

        $plan = MembershipPlan::firstOrCreate(
            ['nama' => $planName],
            ['harga' => $price, 'durasi_bulan' => $duration, 'benefit' => null, 'status' => 'aktif']
        );

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
            case 'Aktif':       return 'aktif';
            case 'Suspended':   return 'dibatalkan';
            case 'Tidak Aktif':
            default:            return 'selesai';
        }
    }

    private function generateInvoiceNumber()
    {
        $format = (string) $this->getSettingValue('billing_invoice_format', 'INV-{YYYYMMDD}-{RAND5}');
        $prefix = str_replace('{YYYYMMDD}', Carbon::now()->format('Ymd'), $format);

        do {
            $rand      = strtoupper(Str::random(5));
            $candidate = str_replace('{RAND5}', $rand, $prefix);
        } while (Invoice::where('nomor_invoice', $candidate)->exists());

        return $candidate;
    }

    private function defaultPlanPrice(?string $name)
    {
        $key = strtolower($name ?? '');
        if ($key === 'basic')   return (int) $this->getSettingValue('billing_basic_price', 150000);
        if ($key === 'premium') return (int) $this->getSettingValue('billing_premium_price', 300000);
        return 0;
    }

    /* ===== Settings storage helpers (hindari duplikasi) ===== */

    private function getSettings()
    {
        return AppSetting::pluck('value', 'key');
    }

    private function getSettingValue(string $key, $default = null)
    {
        static $cached = null;
        if ($cached === null) {
            $cached = $this->getSettings();
        }
        return $cached[$key] ?? $default;
    }

    private function saveSetting(string $key, $value)
    {
        AppSetting::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
