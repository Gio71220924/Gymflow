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
use App\GymClass;
use App\ClassBooking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
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
        $today = Carbon::today();

        $baseQuery = DB::table('gym_classes as gc')
            ->select('gc.id', 'gc.title', 'gc.location', 'gc.start_at', 'gc.end_at', 'gc.capacity', 'gc.status')
            ->selectSub(function ($query) {
                $query->from('class_bookings as cb')
                    ->whereColumn('cb.class_id', 'gc.id')
                    ->whereIn('cb.status', ['booked', 'attended', 'no_show'])
                    ->selectRaw('COUNT(*)');
            }, 'booked_count')
            ->where(function ($query) {
                $query->whereNull('gc.status')->orWhere('gc.status', '!=', 'Cancelled');
            })
            ->orderBy('gc.start_at');

        $todayClasses = (clone $baseQuery)
            ->whereDate('gc.start_at', $today)
            ->limit(8)
            ->get();

        if ($todayClasses->isEmpty()) {
            $todayClasses = (clone $baseQuery)
                ->where('gc.start_at', '>=', $today->copy()->startOfDay())
                ->limit(8)
                ->get();
        }

        $brandingFiles = Storage::disk('public')->files('branding');
        $brandLogo = !empty($brandingFiles)
            ? Storage::url($brandingFiles[0])
            : null;

        return view('landingpage', [
            'key'          => 'landingpage',
            'todayClasses' => $todayClasses,
            'brandLogo'    => $brandLogo,
        ]);
    }
    
    public function home()
    {
        $user = auth()->user();
        if ($user && $user->role === User::ROLE_USER && !$user->memberGym) {
            return redirect()->route('member.profile.setup');
        }
        //Khusus user biasa
        if ($user && $user->role === User::ROLE_USER) {
            $member = $user->memberGym;
            $membership = null;
            $latestInvoice = null;
            $daysLeft = null;
            $invoiceDueIn = null;
            $today = Carbon::today();
            $memberId = optional($member)->id;

            if ($member) {
                $membership = $member->memberMemberships()
                    ->with(['plan', 'invoices' => function ($q) {
                        $q->latest('due_date')->latest('id');
                    }])
                    ->latest('id')
                    ->first();

                $latestInvoice = $membership ? $membership->invoices->first() : null;

                if ($membership && $membership->end_date) {
                    $daysLeft = Carbon::now()->diffInDays(Carbon::parse($membership->end_date), false);
                }

                if ($latestInvoice && $latestInvoice->due_date) {
                    $invoiceDueIn = Carbon::now()->diffInDays(Carbon::parse($latestInvoice->due_date), false);
                }
            }

            $todayClasses = DB::table('gym_classes as gc')
                ->select('gc.id', 'gc.title', 'gc.location', 'gc.start_at', 'gc.end_at', 'gc.capacity', 'gc.status')
                ->whereDate('gc.start_at', $today)
                ->selectSub(function ($query) {
                    $query->from('class_bookings as cb')
                        ->whereColumn('cb.class_id', 'gc.id')
                        ->whereIn('cb.status', ['booked', 'attended', 'no_show'])
                        ->selectRaw('COUNT(*)');
                }, 'booked_count')
                ->when($memberId, function ($query) use ($memberId) {
                    $query->selectSub(function ($sub) use ($memberId) {
                        $sub->from('class_bookings as cb')
                            ->whereColumn('cb.class_id', 'gc.id')
                            ->where('cb.member_id', $memberId)
                            ->select('cb.status')
                            ->latest('cb.id')
                            ->limit(1);
                    }, 'user_booking_status');
                }, function ($query) {
                    $query->selectRaw('NULL as user_booking_status');
                })
                ->orderBy('gc.start_at')
                ->get();

            return view('user-home', [
                'key'           => 'user-home',
                'member'        => $member,
                'membership'    => $membership,
                'latestInvoice' => $latestInvoice,
                'daysLeft'      => $daysLeft,
                'invoiceDueIn'  => $invoiceDueIn,
                'todayClasses'  => $todayClasses,
            ]);
        }

        // Admin / Super Admin dashboard
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


    public function classPage(Request $request)
    {
        $q       = $request->input('q');
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 20, 50], true)) {
            $perPage = 10;
        }

        $user     = $request->user();
        $memberId = optional(optional($user)->memberGym)->id;
        $isAdmin  = $user && $user->role === User::ROLE_SUPER_ADMIN;
        $isUser   = $user && $user->role === User::ROLE_USER;

        $classes = DB::table('gym_classes as gc')
            ->select('gc.*')
            ->selectSub(function ($query) {
                $query->from('class_bookings as cb')
                    ->whereColumn('cb.class_id', 'gc.id')
                    ->whereIn('cb.status', ['booked', 'attended', 'no_show'])
                    ->selectRaw('COUNT(*)');
            }, 'booked_count')
            ->when($memberId, function ($query) use ($memberId) {
                $query->selectSub(function ($sub) use ($memberId) {
                    $sub->from('class_bookings as cb')
                        ->whereColumn('cb.class_id', 'gc.id')
                        ->where('cb.member_id', $memberId)
                        ->select('cb.status')
                        ->latest('cb.id')
                        ->limit(1);
                }, 'user_booking_status');
            }, function ($query) {
                $query->selectRaw('NULL as user_booking_status');
            })
            ->selectSub(function ($query) {
                $query->from('class_trainers as ct')
                    ->join('trainers as t', 't.id', '=', 'ct.trainer_id')
                    ->whereColumn('ct.class_id', 'gc.id')
                    ->selectRaw("GROUP_CONCAT(t.name ORDER BY ct.role SEPARATOR ', ')");
            }, 'trainer_names')
            ->when($q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('gc.title', 'like', '%' . $q . '%')
                        ->orWhere('gc.location', 'like', '%' . $q . '%')
                        ->orWhere('gc.status', 'like', '%' . $q . '%')
                        ->orWhereRaw("EXISTS (
                            SELECT 1 FROM class_trainers AS ct
                            JOIN trainers AS t ON t.id = ct.trainer_id
                            WHERE ct.class_id = gc.id AND t.name LIKE ?
                        )", ['%' . $q . '%']);
                });
            })
            ->orderBy('gc.start_at')
            ->paginate($perPage)
            ->appends([
                'q'        => $q,
                'per_page' => $perPage,
            ]);

        $participants = collect();
        if ($isAdmin) {
            $classIds = $classes->pluck('id');
            $participants = DB::table('class_bookings as cb')
                ->join('member_gym as m', 'm.id', '=', 'cb.member_id')
                ->whereIn('cb.class_id', $classIds)
                ->select('cb.id as booking_id', 'cb.class_id', 'cb.status', 'm.nama_member', 'm.id as member_id')
                ->orderBy('m.nama_member')
                ->get()
                ->groupBy('class_id');
        }

        return view('class', [
            'key'         => 'class',
            'classes'     => $classes,
            'searchQuery' => $q,
            'perPage'     => $perPage,
            'isAdmin'     => $isAdmin,
            'isUser'      => $isUser,
            'participants'=> $participants,
            'memberId'    => $memberId,
        ]);
    }

    public function searchclass(Request $request)
    {
        return $this->classPage($request);
    }

    public function createClassForm(Request $request)
    {
        $this->ensureSuperAdmin($request);

        return view('class-form', [
            'key'                => 'class',
            'mode'               => 'create',
            'classData'          => null,
            'trainers'           => $this->getTrainerOptions(),
            'selectedTrainerIds' => [],
        ]);
    }

    public function storeClass(Request $request)
    {
        $this->ensureSuperAdmin($request);
        $payload = $this->validateClassPayload($request);
        $trainerIds = $payload['trainer_ids'] ?? [];
        unset($payload['trainer_ids']);

        $class = GymClass::create($payload);
        $this->syncClassTrainers($class, $trainerIds);

        return redirect()->route('class')->with('success', 'Kelas berhasil dibuat.');
    }

    public function editClassForm(Request $request, $id)
    {
        $this->ensureSuperAdmin($request);

        $class = GymClass::findOrFail($id);
        $selectedTrainerIds = DB::table('class_trainers')
            ->where('class_id', $id)
            ->pluck('trainer_id')
            ->toArray();

        return view('class-form', [
            'key'                => 'class',
            'mode'               => 'edit',
            'classData'          => $class,
            'trainers'           => $this->getTrainerOptions(),
            'selectedTrainerIds' => $selectedTrainerIds,
        ]);
    }

    public function updateClass(Request $request, $id)
    {
        $this->ensureSuperAdmin($request);
        $class = GymClass::findOrFail($id);

        $payload = $this->validateClassPayload($request);
        $trainerIds = $payload['trainer_ids'] ?? [];
        unset($payload['trainer_ids']);

        $class->update($payload);
        $this->syncClassTrainers($class, $trainerIds);

        return redirect()->route('class')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function deleteClass(Request $request, $id)
    {
        $this->ensureSuperAdmin($request);
        $class = GymClass::findOrFail($id);
        $class->delete();

        return redirect()->route('class')->with('success', 'Kelas berhasil dihapus.');
    }

    /* ======================== CLASS BOOKINGS ======================== */

    public function joinClass(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || $user->role !== User::ROLE_USER) {
            abort(403, 'Hanya member yang dapat bergabung ke kelas.');
        }

        $member = $user->memberGym;
        if (!$member) {
            return back()->with('error', 'Data member tidak ditemukan.');
        }

        $latestMembership = $member->memberMemberships()->latest('id')->first();
        $latestInvoice = $latestMembership ? $latestMembership->invoices()->latest('id')->first() : null;
        if ($latestInvoice && $latestInvoice->status !== 'lunas') {
            return back()->with('error', 'Anda belum melunasi tagihan Anda.');
        }

        $class = GymClass::findOrFail($id);
        $statusNormalized = strtolower(trim((string) $class->status));
        if (in_array($statusNormalized, ['cancelled', 'done'], true)) {
            return back()->with('error', 'Kelas ini tidak tersedia untuk booking.');
        }

        if (Carbon::parse($class->start_at)->isPast()) {
            return back()->with('error', 'Kelas sudah berjalan atau selesai, tidak dapat dibooking.');
        }

        $activeCount = ClassBooking::where('class_id', $class->id)
            ->whereIn('status', ['booked', 'attended', 'no_show'])
            ->count();

        if ($activeCount >= $class->capacity) {
            return back()->with('error', 'Kapasitas kelas sudah penuh.');
        }

        $booking = ClassBooking::where('class_id', $class->id)
            ->where('member_id', $member->id)
            ->first();

        if ($booking) {
            if (in_array($booking->status, ['booked', 'attended', 'no_show'], true)) {
                return back()->with('success', 'Kamu sudah bergabung di kelas ini.');
            }
            $booking->status = 'booked';
            $booking->checked_in_at = null;
            $booking->save();
        } else {
            ClassBooking::create([
                'class_id' => $class->id,
                'member_id' => $member->id,
                'status'   => 'booked',
            ]);
        }

        return back()->with('success', 'Berhasil bergabung ke kelas.');
    }

    public function cancelClassBooking(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || $user->role !== User::ROLE_USER) {
            abort(403, 'Hanya member yang dapat membatalkan booking.');
        }

        $member = $user->memberGym;
        if (!$member) {
            return back()->with('error', 'Data member tidak ditemukan.');
        }

        $booking = ClassBooking::where('class_id', $id)
            ->where('member_id', $member->id)
            ->first();

        if (!$booking) {
            return back()->with('error', 'Kamu belum bergabung pada kelas ini.');
        }

        if ($booking->status === 'cancelled') {
            return back()->with('success', 'Booking sudah dibatalkan.');
        }

        $booking->status = 'cancelled';
        $booking->checked_in_at = null;
        $booking->save();

        return back()->with('success', 'Booking kelas berhasil dibatalkan.');
    }

    public function kickClassMember(Request $request, $classId, $bookingId)
    {
        $this->ensureSuperAdmin($request);

        $booking = ClassBooking::where('id', $bookingId)
            ->where('class_id', $classId)
            ->firstOrFail();

        $booking->delete();

        return back()->with('success', 'Peserta berhasil dikeluarkan dari kelas.');
    }


    /* ======================== BILLING ======================== */

    public function billing()
    {
        $user = auth()->user();
        $isUser = $user && $user->role === User::ROLE_USER;
        $isAdmin = $user && $user->role === User::ROLE_SUPER_ADMIN;

        $query = Invoice::with([
            'memberMembership.member',
            'memberMembership.plan',
            'payments' => function ($q) {
                $q->latest('paid_at')->latest('id');
            },
        ]);

        if ($isUser) {
            $memberId = optional($user->memberGym)->id;
            $query->whereHas('memberMembership', function ($q) use ($memberId) {
                if ($memberId) {
                    $q->where('member_id', $memberId);
                } else {
                    $q->whereRaw('1 = 0');
                }
            });
        }

        $invoices = $query->orderByDesc('id')->get();

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

        $monthlyRevenue = null;
        if ($isAdmin) {
            $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
            $revenueRows = Payment::select(
                    DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as ym'),
                    DB::raw('SUM(amount) as total_amount'),
                    DB::raw('COUNT(DISTINCT invoice_id) as invoice_count'),
                    DB::raw('COUNT(*) as payment_count')
                )
                ->where('status', 'berhasil')
                ->whereNotNull('paid_at')
                ->where('paid_at', '>=', $startMonth)
                ->groupBy('ym')
                ->orderBy('ym')
                ->get();

            $labels         = [];
            $amounts        = [];
            $invoiceCounts  = [];

            foreach ($revenueRows as $row) {
                try {
                    $labels[] = Carbon::createFromFormat('Y-m', $row->ym)->format('M Y');
                } catch (\Throwable $e) {
                    $labels[] = $row->ym;
                }
                $amounts[]       = (float) $row->total_amount;
                $invoiceCounts[] = (int) $row->invoice_count;
            }

            $monthlyRevenue = [
                'labels'                => $labels,
                'amounts'               => $amounts,
                'invoice_counts'        => $invoiceCounts,
                'total'                 => array_sum($amounts),
                'latest_label'          => count($labels) ? $labels[count($labels) - 1] : null,
                'latest_amount'         => count($amounts) ? $amounts[count($amounts) - 1] : 0,
                'latest_invoice_count'  => count($invoiceCounts) ? $invoiceCounts[count($invoiceCounts) - 1] : 0,
            ];
        }

        $membershipOptions = collect();
        if ($isAdmin) {
            $membershipOptions = MemberMembership::with(['member', 'plan'])
                ->orderByDesc('id')
                ->get();
        }

        return view('billing', [
            'key'      => 'billing',
            'invoices' => $invoices,
            'summary'  => $summary,
            'isAdmin'  => $isAdmin,
            'membershipOptions' => $membershipOptions,
            'monthlyRevenue' => $monthlyRevenue,
        ]);
    }

    public function storeInvoice(Request $request)
    {
        $this->ensureSuperAdmin($request);

        $data = $request->validate([
            'member_membership_id' => 'required|exists:member_memberships,id',
            'due_date'             => 'nullable|date',
            'total_tagihan'        => 'nullable|numeric|min:0',
            'diskon'               => 'nullable|numeric|min:0',
            'pajak'                => 'nullable|numeric|min:0',
            'status'               => 'required|in:draft,menunggu,lunas,batal',
            'payment_method'       => 'nullable|in:cash,transfer,ewallet,credit_card',
            'catatan'              => 'nullable|string',
        ]);

        $membership = MemberMembership::with('plan')->findOrFail($data['member_membership_id']);
        $plan       = $membership->plan ?: ($membership->plan_id ? MembershipPlan::find($membership->plan_id) : null);

        $dueDays = (int) $this->getSettingValue('billing_due_days', 0);
        $dueDate = $data['due_date'] ?? $membership->end_date;
        if (!$dueDate && $membership->start_date && $dueDays > 0) {
            $dueDate = Carbon::parse($membership->start_date)->addDays($dueDays)->toDateString();
        }

        $total = $data['total_tagihan'] ?? $this->calculateTotalTagihan($plan, $membership, 1);

        $invoice = Invoice::create([
            'member_membership_id' => $membership->id,
            'nomor_invoice'        => $this->generateInvoiceNumber(),
            'due_date'             => $dueDate,
            'total_tagihan'        => $total,
            'diskon'               => $data['diskon'] ?? (int) $this->getSettingValue('billing_default_discount', 0),
            'pajak'                => $data['pajak'] ?? (int) $this->getSettingValue('billing_default_tax', 0),
            'status'               => $data['status'],
            'catatan'              => $data['catatan'] ?? null,
        ]);

        $membership->pembayaran_status = $data['status'] === 'lunas'
            ? 'lunas'
            : ($data['status'] === 'menunggu' ? 'menunggu' : 'gagal');
        $membership->save();

        if ($data['status'] === 'lunas') {
            Payment::create([
                'invoice_id'   => $invoice->id,
                'amount'       => $total,
                'method'       => $data['payment_method'] ?? 'cash',
                'paid_at'      => Carbon::now(),
                'status'       => 'berhasil',
                'bukti_bayar'  => null,
                'reference_no' => null,
                'catatan'      => 'Created by admin',
            ]);
        }

        return redirect()->route('billing')->with('success', 'Invoice baru berhasil ditambahkan.');
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
        if ($section === 'account') {
            $user = $request->user();

            $validated = $request->validate([
                'current_password'          => 'required_with:new_password',
                'new_password'              => 'nullable|string|min:8|confirmed',
                'foto_profil'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

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
            $member = $user->memberGym;
            if ($request->hasFile('foto_profil')) {
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

            if (!$passwordUpdated && !$photoUpdated) {
                return back()->with('success', 'Tidak ada perubahan disimpan.');
            }

            return back()->with('success', 'Pengaturan akun berhasil diperbarui.');
        } elseif ($section === 'billing') {
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

    /* ======================== MEMBER SELF SETUP ======================== */

    public function memberProfileForm(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== User::ROLE_USER) {
            abort(403, 'Hanya member yang dapat mengisi profil member.');
        }
        if ($user->memberGym) {
            return redirect()->route('home');
        }

        $today = Carbon::today()->toDateString();

        return view('member-profile-setup', [
            'key' => 'member-profile-setup',
            'user' => $user,
            'today' => $today,
        ]);
    }

    public function memberProfileSave(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== User::ROLE_USER) {
            abort(403, 'Hanya member yang dapat mengisi profil member.');
        }
        if ($user->memberGym) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'nama_member'          => 'required|string|max:100',
            'nomor_telepon_member' => 'required|string|max:20',
            'tanggal_lahir'        => 'required|date',
            'gender'               => 'required|in:Laki-laki,Perempuan',
            'tanggal_join'         => 'required|date',
            'membership_plan'      => 'required|in:basic,premium',
            'durasi_plan'          => 'required|integer|min:1',
            'start_date'           => 'required|date',
            'notes'                => 'nullable|string',
        ]);

        $joinDate = Carbon::parse($validated['tanggal_join']);
        $start    = Carbon::parse($validated['start_date']);
        $durasi   = max(1, (int) $validated['durasi_plan']);
        $endDate  = $start->copy()->addMonths($durasi)->toDateString();

        $idMember = $this->generateMemberId($validated['membership_plan'], $joinDate);

        $member = Member_Gym::create([
            'user_id'              => $user->id,
            'id_member'            => $idMember,
            'nama_member'          => $validated['nama_member'],
            'email_member'         => $user->email,
            'nomor_telepon_member' => $validated['nomor_telepon_member'],
            'tanggal_lahir'        => $validated['tanggal_lahir'],
            'gender'               => $validated['gender'],
            'tanggal_join'         => $joinDate->toDateString(),
            'membership_plan'      => $validated['membership_plan'],
            'durasi_plan'          => $durasi,
            'start_date'           => $start->toDateString(),
            'end_date'             => $endDate,
            'status_membership'    => 'Aktif',
            'notes'                => $validated['notes'] ?? null,
            'foto_profil'          => null,
        ]);

        $dataForSync = array_merge($validated, [
            'end_date'          => $endDate,
            'status_membership' => 'Aktif',
        ]);

        $this->syncMembershipAndInvoice($member, $dataForSync);

        return redirect()->route('home')->with('success', 'Profil member berhasil disimpan.');
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

    private function generateMemberId(string $planName, Carbon $joinDate)
    {
        $planKey = strtolower($planName) === 'premium' ? 'premium' : 'basic';
        $prefix = strtoupper($planKey);
        $ym = $joinDate->format('Ym'); // YYYYMM

        $order = Member_Gym::where('membership_plan', $planKey)
            ->whereYear('tanggal_join', $joinDate->year)
            ->whereMonth('tanggal_join', $joinDate->month)
            ->count() + 1;

        return sprintf('%s-%s%02d', $prefix, $ym, $order);
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

    private function ensureSuperAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Hanya admin yang dapat mengelola kelas.');
        }
    }

    private function validateClassPayload(Request $request)
    {
        return $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'level'        => 'nullable|string|max:100',
            'capacity'     => 'required|integer|min:0',
            'location'     => 'nullable|string|max:255',
            'start_at'     => 'required|date',
            'end_at'       => 'required|date|after:start_at',
            'type'         => 'nullable|string|max:100',
            'status'       => 'required|in:Scheduled,Cancelled,Done',
            'trainer_ids'  => 'nullable|array',
            'trainer_ids.*'=> 'integer|exists:trainers,id',
        ]);
    }

    private function syncClassTrainers(GymClass $class, array $trainerIds)
    {
        DB::table('class_trainers')->where('class_id', $class->id)->delete();

        if (empty($trainerIds)) {
            return;
        }

        $rows = [];
        foreach ($trainerIds as $tid) {
            $rows[] = [
                'class_id'   => $class->id,
                'trainer_id' => $tid,
                'role'       => 'lead',
            ];
        }

        DB::table('class_trainers')->insert($rows);
    }

    private function getTrainerOptions()
    {
        return DB::table('trainers')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}
