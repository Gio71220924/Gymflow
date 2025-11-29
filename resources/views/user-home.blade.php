@extends('layouts.main')

@section('title', 'Beranda Member')
@section('page_heading', 'Beranda')
@section('card_title', 'Menu utama member')

@section('styles')
<style>
  .member-hero {
    position: relative;
    overflow: hidden;
    border-radius: 14px;
    padding: 22px 24px;
    background: linear-gradient(135deg, var(--brand-primary) 0%, #ffb686 70%);
    color: #1f130c;
    box-shadow: 0 18px 40px rgba(252,119,83,0.25);
  }
  .member-hero::after {
    content: '';
    position: absolute;
    right: -80px; top: -40px;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0) 60%);
    transform: rotate(12deg);
  }
  .member-hero h3 { margin: 6px 0 10px; font-weight: 800; letter-spacing: -0.01em; }
  .member-hero p { margin: 0; color: rgba(31,19,12,0.85); }
  .hero-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; z-index: 1; position: relative; }
  .hero-chip { display: inline-flex; align-items: center; gap: 8px; padding: 10px 12px; border-radius: 12px; background: rgba(255,255,255,0.9); color: #1f130c; font-weight: 700; font-size: 0.9rem; box-shadow: 0 6px 16px rgba(0,0,0,0.12); }
  .pill-mini { display: inline-flex; align-items: center; gap: 8px; padding: 6px 10px; border-radius: 10px; background: rgba(255,255,255,0.85); color: #1f130c; font-weight: 700; font-size: 0.82rem; }
  .stat-card { background: var(--neutral-card); border: 1px solid var(--neutral-border); border-radius: 12px; padding: 18px; box-shadow: var(--shadow-sm); height: 100%; }
  .stat-card h5 { margin: 0 0 6px; font-weight: 700; font-size: 1.1rem; }
  .stat-meta { color: var(--neutral-text); font-size: 0.92rem; }
  .value-lg { font-size: 1.6rem; font-weight: 800; color: var(--brand-dark); line-height: 1.1; }
  .progress.bar { height: 8px; background: var(--neutral-light); border-radius: 999px; overflow: hidden; }
  .progress.bar .fill { height: 100%; background: linear-gradient(90deg, var(--brand-primary), #f6b179); }
  .quick-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
  .quick-card { background: var(--neutral-card); border: 1px solid var(--neutral-border); border-radius: 12px; padding: 14px; display: grid; gap: 8px; transition: transform 0.2s ease, box-shadow 0.2s ease; }
  .quick-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
  .quick-icon { width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; background: rgba(252,119,83,0.12); color: var(--brand-primary); font-size: 1.1rem; }
  .schedule-list { display: grid; gap: 10px; margin: 0; padding: 0; list-style: none; }
  .schedule-item { border: 1px solid var(--neutral-border); border-radius: 12px; padding: 10px 12px; display: flex; align-items: center; justify-content: space-between; background: var(--neutral-card); box-shadow: inset 0 1px 0 rgba(255,255,255,0.5); }
  .schedule-item .time { font-weight: 800; color: var(--brand-dark); width: 68px; }
  .schedule-item .title { font-weight: 700; color: var(--brand-dark); }
  .schedule-item .meta { display: inline-flex; align-items: center; gap: 8px; color: var(--neutral-text); }
  .badge-soft { display: inline-flex; align-items: center; padding: 6px 10px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; background: rgba(252,119,83,0.12); color: #c14f2d; }
  .badge-soft.success { background: rgba(40,199,111,0.16); color: #126f3f; }
  .badge-soft.info { background: rgba(15,23,42,0.08); color: #0f172a; }
  .note-card { background: var(--neutral-light); border: 1px solid var(--neutral-border); border-radius: 12px; padding: 14px; }
</style>
@endsection

@section('content')
@php
  use Carbon\Carbon;
  $user = Auth::user();
  $memberName = $member ? ($member->nama_member ?? $user->name) : $user->name;
  $planName = ($membership && $membership->plan)
      ? $membership->plan->nama
      : ($member ? ($member->membership_plan ?? 'Basic') : 'Basic');
  $membershipStatus = $membership->status ?? ($member ? ($member->status_membership ?? 'Belum diatur') : 'Belum diatur');
  $statusLabel = ucwords(str_replace(['_', '-'], ' ', $membershipStatus));
  $startDate = $membership->start_date ?? ($member ? ($member->start_date ?? null) : null);
  $endDate = $membership->end_date ?? ($member ? ($member->end_date ?? null) : null);
  $daysLeftText = 'Tanggal belum diatur';
  if ($daysLeft !== null) {
      if ($daysLeft > 0)       $daysLeftText = $daysLeft . ' hari tersisa';
      elseif ($daysLeft === 0) $daysLeftText = 'Berakhir hari ini';
      else                     $daysLeftText = 'Lewat ' . abs($daysLeft) . ' hari';
  }
  $invoiceStatus = $latestInvoice->status ?? 'menunggu';
  $invoiceAmount = ($latestInvoice && $latestInvoice->total_tagihan)
      ? number_format($latestInvoice->total_tagihan, 0, ',', '.')
      : '0';
  $invoiceDueText = ($latestInvoice && $latestInvoice->due_date)
      ? Carbon::parse($latestInvoice->due_date)->format('d M Y')
      : '-';
  if ($latestInvoice && $latestInvoice->due_date && $invoiceDueIn !== null) {
      if ($invoiceDueIn > 0)       $invoiceDueText .= ' - ' . $invoiceDueIn . ' hari lagi';
      elseif ($invoiceDueIn === 0) $invoiceDueText .= ' - Jatuh tempo hari ini';
      else                         $invoiceDueText .= ' - Lewat ' . abs($invoiceDueIn) . ' hari';
  }
  $membershipProgress = null;
  if ($startDate && $endDate) {
      $totalDays = max(1, Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)));
      $elapsed   = max(0, min($totalDays, Carbon::parse($startDate)->diffInDays(Carbon::now())));
      $membershipProgress = round(($elapsed / $totalDays) * 100);
  }
  $schedulePreview = [
    ['time' => '07.00', 'title' => 'HIIT Burn', 'status' => 'Penuh', 'variant' => 'success', 'slots' => '12/12'],
    ['time' => '12.30', 'title' => 'Strength Circuit', 'status' => 'Berlangsung', 'variant' => 'info', 'slots' => '8/14'],
    ['time' => '19.00', 'title' => 'Mobility Flow', 'status' => 'Tersedia', 'variant' => 'default', 'slots' => '5 slot lagi'],
  ];
@endphp


<div class="member-hero mb-3">
  <div style="position:relative; z-index:1;">
    <div class="pill-mini mb-2">Halo, {{ $memberName }}</div>
    <h3>Latihan nyaman, progres terukur.</h3>
    <p>Plan {{ ucfirst($planName) }} - Status {{ $statusLabel }} - {{ $daysLeftText }}</p>
    <div class="hero-actions">
      <a class="btn btn-light btn-sm" href="/class">Lihat jadwal</a>
      <a class="btn btn-outline-dark btn-sm border-dark" href="/billing">Tagihan saya</a>
      <a class="btn btn-outline-dark btn-sm border-dark" href="{{ route('change-password') }}">Profil</a>
    </div>
  </div>
  <div style="margin-left:auto; z-index:1;">
    <span class="hero-chip"><i class="bi bi-shield-check"></i>Membership aktif</span>
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <div class="stat-card">
      <div class="stat-meta">Membership</div>
      <h5>{{ ucfirst($planName) }}</h5>
      <div class="value-lg">{{ $statusLabel }}</div>
      <div class="stat-meta">{{ $startDate ? Carbon::parse($startDate)->format('d M Y') : '-' }} - {{ $endDate ? Carbon::parse($endDate)->format('d M Y') : '-' }}</div>
      @if(!is_null($membershipProgress))
      <div class="progress bar mt-2"><div class="fill" style="width: {{ min($membershipProgress, 100) }}%;"></div></div>
      <div class="stat-meta mt-1">Progres {{ $membershipProgress }}%</div>
      @endif
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="stat-card">
      <div class="stat-meta">Tagihan terakhir</div>
      <h5>Status: {{ ucfirst($invoiceStatus) }}</h5>
      <div class="value-lg">Rp {{ $invoiceAmount }}</div>
      <div class="stat-meta">Jatuh tempo: {{ $invoiceDueText }}</div>
      <a class="btn btn-link px-0 mt-2" href="/billing">Lihat detail billing</a>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="stat-card">
      <div class="stat-meta">Akun</div>
      <h5>{{ $memberName }}</h5>
      <div class="stat-meta mb-2">{{ $user->email }}</div>
      <div class="badge-soft" style="width:fit-content;">{{ $membershipStatus }}</div>
      <a class="btn btn-link px-0 mt-2" href="{{ route('change-password') }}">Ubah password</a>
    </div>
  </div>
</div>

<div class="mb-3">
  <h5 class="mb-2">Akses cepat</h5>
  <div class="quick-grid">
    <a class="quick-card" href="/class">
      <div class="quick-icon"><i class="bi bi-calendar2-week"></i></div>
      <div class="font-weight-bold">Booking kelas</div>
      <div class="stat-meta">Lihat slot terbaru dan dapatkan pengingat.</div>
    </a>
    <a class="quick-card" href="/billing">
      <div class="quick-icon"><i class="bi bi-receipt"></i></div>
      <div class="font-weight-bold">Tagihan & riwayat</div>
      <div class="stat-meta">Cek status pembayaran dan unduh invoice.</div>
    </a>
    <a class="quick-card" href="{{ route('change-password') }}">
      <div class="quick-icon"><i class="bi bi-person"></i></div>
      <div class="font-weight-bold">Profil & keamanan</div>
      <div class="stat-meta">Perbarui data dasar atau ganti password.</div>
    </a>
    <a class="quick-card" href="mailto:{{ $appSettings['branding_email'] ?? 'info@example.com' }}">
      <div class="quick-icon"><i class="bi bi-headset"></i></div>
      <div class="font-weight-bold">Bantuan</div>
      <div class="stat-meta">Hubungi tim jika butuh bantuan jadwal atau pembayaran.</div>
    </a>
  </div>
</div>

<div class="row">
  <div class="col-lg-6 mb-3">
    <div class="stat-card h-100">
      <div class="stat-meta">Jadwal hari ini</div>
      <ul class="schedule-list">
        @foreach($schedulePreview as $slot)
          <li class="schedule-item">
            <div class="d-flex align-items-center" style="gap:12px;">
              <span class="time">{{ $slot['time'] }}</span>
              <div>
                <div class="title">{{ $slot['title'] }}</div>
                <div class="stat-meta">{{ $slot['slots'] }}</div>
              </div>
            </div>
            <div class="meta">
              <span class="badge-soft {{ $slot['variant'] === 'success' ? 'success' : ($slot['variant'] === 'info' ? 'info' : '') }}">{{ $slot['status'] }}</span>
            </div>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
  <div class="col-lg-6 mb-3">
    <div class="stat-card h-100">
      <div class="stat-meta">Pengumuman & catatan</div>
      <div class="note-card mb-2">
        <div class="font-weight-bold">Check-in</div>
        <div class="stat-meta">Tunjukkan barcode di meja depan untuk masuk lebih cepat.</div>
      </div>
      <div class="note-card mb-2">
        <div class="font-weight-bold">Keterlambatan pembayaran</div>
        <div class="stat-meta">Tagihan yang lewat 3 hari akan mendapatkan pengingat otomatis.</div>
      </div>
      <div class="note-card">
        <div class="font-weight-bold">Butuh bantuan?</div>
        <div class="stat-meta">Hubungi resepsionis atau kirim email ke {{ $appSettings['branding_email'] ?? 'info@example.com' }}.</div>
      </div>
    </div>
  </div>
</div>
@endsection
