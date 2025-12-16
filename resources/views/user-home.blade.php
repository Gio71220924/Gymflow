@extends('layouts.main')

@section('title', 'Beranda Member')
@section('page_heading', 'Beranda')
@section('card_title', 'Menu utama member')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user-home.css') }}">
@endsection

@section('content')
@php
  use Carbon\Carbon;
  $user = Auth::user();
  $memberName = $member ? ($member->nama_member ?? $user->name) : $user->name;
  $memberFirstName = trim(strtok($memberName, ' ')) ?: $memberName;
  $planName = ($membership && $membership->plan)
      ? $membership->plan->nama
      : ($member ? ($member->membership_plan ?? 'Basic') : 'Basic');
  $planKey = strtolower($planName);
  $renewPlan = old('plan', $planKey ?: 'basic');
  $renewDurasi = old('durasi', $member->durasi_plan ?? 1);
  $membershipEndDate = $membershipEndDate ?? ($membership->end_date ?? ($member ? ($member->end_date ?? null) : null));
  $membershipExpired = $membershipExpired ?? false;
  $membershipStatus = $membershipExpired
      ? 'Expired'
      : ($membership->status ?? ($member ? ($member->status_membership ?? 'Belum diatur') : 'Belum diatur'));
  $statusLabel = $membershipExpired
      ? 'Expired'
      : ucwords(str_replace(['_', '-'], ' ', $membershipStatus));
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
  $normalizedStatus = strtolower(trim($membershipStatus));
  $isActiveMembership = !$membershipExpired && in_array($normalizedStatus, ['aktif', 'active']);
  $heroChipText = $membershipExpired ? 'Membership expired' : ($isActiveMembership ? 'Membership aktif' : 'Membership tidak aktif');
  $heroChipIcon = $membershipExpired ? 'bi-exclamation-triangle' : ($isActiveMembership ? 'bi-shield-check' : 'bi-exclamation-triangle');
  $heroChipClass = $membershipExpired ? ' inactive' : ($isActiveMembership ? '' : ' inactive');
  $todayClasses = $membershipExpired ? collect() : collect($todayClasses ?? []);
@endphp

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif


<div class="member-hero mb-3">
  <div style="position:relative; z-index:1;">
    <div class="pill-mini mb-2">Halo, {{ $memberFirstName }}</div>
    <h3>Latihan nyaman, progres terukur.</h3>
    <p>Plan {{ ucfirst($planName) }} - Status {{ $statusLabel }} - {{ $daysLeftText }}</p>
    <div class="hero-actions">
      @if($membershipExpired)
        <span class="btn btn-light btn-sm disabled" aria-disabled="true">Lihat jadwal</span>
      @else
        <a class="btn btn-light btn-sm" href="/class">Lihat jadwal</a>
      @endif
      <a class="btn btn-outline-dark btn-sm border-dark" href="/billing">Tagihan saya</a>
      <a class="btn btn-outline-dark btn-sm border-dark" href="{{ route('change-password') }}">Profil</a>
    </div>
  </div>
  <div class="hero-chip-wrap">
    <span class="hero-chip{{ $heroChipClass }}"><i class="bi {{ $heroChipIcon }}"></i>{{ $heroChipText }}</span>
  </div>
</div>

@if($membershipExpired)
  <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
    <i class="bi bi-exclamation-triangle-fill mr-2"></i>
    <div>
      Masa berlaku membership Anda telah berakhir{{ $membershipEndDate ? ' pada ' . Carbon::parse($membershipEndDate)->format('d M Y') : '' }}. 
      Perpanjang terlebih dulu untuk kembali mengakses jadwal & kelas.
    </div>
  </div>
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title mb-3">Perpanjang membership</h5>
      <form method="POST" action="{{ route('membership.renew') }}">
        @csrf
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="plan">Pilih paket</label>
            <select id="plan" name="plan" class="form-control @error('plan') is-invalid @enderror">
              <option value="basic" {{ $renewPlan === 'basic' ? 'selected' : '' }}>Basic</option>
              <option value="premium" {{ $renewPlan === 'premium' ? 'selected' : '' }}>Premium</option>
            </select>
            @error('plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="durasi">Durasi (bulan)</label>
            <input type="number"
                   id="durasi"
                   name="durasi"
                   min="1"
                   max="24"
                   value="{{ $renewDurasi }}"
                   class="form-control @error('durasi') is-invalid @enderror">
            <small class="form-text text-muted">Invoice baru akan dibuat dengan status menunggu.</small>
            @error('durasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Perpanjang sekarang</button>
      </form>
    </div>
  </div>
@endif

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
    @if($membershipExpired)
      <div class="quick-card" style="opacity:0.6; cursor:not-allowed;">
        <div class="quick-icon"><i class="bi bi-calendar2-week"></i></div>
        <div class="font-weight-bold">Booking kelas</div>
        <div class="stat-meta">Perpanjang membership untuk akses kelas.</div>
      </div>
    @else
      <a class="quick-card" href="/class">
        <div class="quick-icon"><i class="bi bi-calendar2-week"></i></div>
        <div class="font-weight-bold">Booking kelas</div>
        <div class="stat-meta">Lihat slot terbaru dan dapatkan pengingat.</div>
      </a>
    @endif
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
        @if($membershipExpired)
          <li class="schedule-item">
            <div class="title">Membership berakhir.</div>
            <div class="stat-meta">Perpanjang membership untuk melihat jadwal kelas.</div>
          </li>
        @else
          @forelse($todayClasses as $class)
          @php
            $start = Carbon::parse($class->start_at)->timezone('Asia/Jakarta');
            $end   = Carbon::parse($class->end_at)->timezone('Asia/Jakarta');
            $booked = (int) ($class->booked_count ?? 0);
            $capacity = (int) ($class->capacity ?? 0);
            $slotsLeft = max($capacity - $booked, 0);
            $isFull = $capacity > 0 && $booked >= $capacity;
            $statusKey = strtolower(trim($class->status ?? ''));
            $statusBadge = $statusKey === 'cancelled' ? 'danger' : ($statusKey === 'done' ? 'success' : 'info');
            $statusLabel = $class->status ?? 'Scheduled';
            $userStatus = $class->user_booking_status ?? null;
          @endphp
          <li class="schedule-item">
            <div class="d-flex align-items-center" style="gap:12px;">
              <span class="time">{{ $start->format('H:i') }}</span>
              <div>
                <div class="title">{{ $class->title }}</div>
                <div class="stat-meta">
                  {{ $class->location ?? 'Studio' }} ·
                  @if($capacity > 0)
                    {{ $booked }} / {{ $capacity }} slot (sisa {{ $slotsLeft }})
                  @else
                    {{ $booked }} peserta
                  @endif
                </div>
              </div>
            </div>
            <div class="meta">
              <span class="badge-soft {{ $statusBadge }}">{{ $statusLabel }}</span>
              @if($userStatus && $userStatus !== 'cancelled')
                <span class="badge-soft success">Saya terdaftar</span>
              @elseif($isFull)
                <span class="badge-soft danger">Penuh</span>
              @endif
            </div>
          </li>
        @empty
          <li class="schedule-item">
            <div class="title">Belum ada jadwal kelas hari ini.</div>
            <div class="stat-meta">Cek jadwal lain di halaman kelas.</div>
          </li>
          @endforelse
        @endif
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
