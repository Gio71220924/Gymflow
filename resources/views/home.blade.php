@extends('layouts.main')
@section('title', 'Dashboard Admin')
@section('page_heading', 'Dashboard')
@section('card_title', 'Command Center')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('card_actions')
  <div class="btn-group">
    <a href="{{ route('add-member') }}" class="btn btn-brand btn-sm text-white">Tambah Member</a>
    <a href="{{ route('member') }}" class="btn btn-outline-ink btn-sm">Kelola Member</a>
  </div>
@endsection

@section('content')
  @php
    $activePercent   = $totalMembers ? round(($activeMembers / max($totalMembers, 1)) * 100) : 0;
    $inactivePercent = $totalMembers ? round(($inactiveMembers / max($totalMembers, 1)) * 100) : 0;
    $topPlan = $planCounts->count() ? $planCounts->sortByDesc(function ($count) { return $count; })->keys()->first() : '-';
  @endphp

  <div class="dashboard-hero mb-4">
    <div class="hero-head">
      <div class="d-flex align-items-center mb-3">
        <span class="hero-pill">GymFlow Admin</span>
        <span class="hero-pill hero-pill-soft ml-2">Realtime data</span>
      </div>
      <h2 class="hero-title mb-2">Dashboard Operasional</h2>
      <p class="hero-sub mb-3">Kendalikan membership, pantau tren, dan jalankan tugas harian dengan tampilan minimalis selaras warna GymFlow.</p>
      <div class="hero-actions mb-4">
        <a href="{{ route('member') }}" class="btn btn-light btn-sm mr-2">Lihat Member</a>
        <a href="{{ route('add-member') }}" class="btn btn-outline-light btn-sm border-light">Tambah Member Baru</a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat-box">
          <div class="hero-stat-label">Total member</div>
          <div class="hero-stat-value">{{ $totalMembers }}</div>
        </div>
        <div class="hero-stat-box">
          <div class="hero-stat-label">Aktif</div>
          <div class="hero-stat-value">{{ $activeMembers }}</div>
        </div>
        <div class="hero-stat-box">
          <div class="hero-stat-label">Tidak aktif</div>
          <div class="hero-stat-value">{{ $inactiveMembers }}</div>
        </div>
      </div>
    </div>
    <div class="hero-panel">
      <div class="label">Health score</div>
      <div class="d-flex align-items-center mb-3">
        <div class="hero-score">{{ $activePercent }}%</div>
        <div class="flex-fill ml-3">
          <div class="hero-progress mb-2"><div class="hero-progress-bar" style="width: {{ min($activePercent, 100) }}%;"></div></div>
          <div class="d-flex justify-content-between text-muted small">
            <span>{{ $activeMembers }} aktif</span>
            <span>{{ $inactiveMembers }} tidak aktif</span>
          </div>
        </div>
      </div>
      <div class="hero-alert d-flex align-items-start mb-3">
        <div class="hero-alert-icon mr-3"><i class="bi bi-bell"></i></div>
        <div>
          <div class="font-weight-bold mb-1">{{ $expiringSoon }} membership kedaluwarsa <= 7 hari</div>
          <div class="text-muted small">Siapkan reminder atau tawarkan perpanjangan.</div>
        </div>
      </div>
      <div class="hero-badges">
        <span class="mini-badge">Join bln ini: {{ $newThisMonth }}</span>
        <span class="mini-badge">Plan favorit: {{ strtoupper($topPlan) }}</span>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-6 col-lg-3 mb-3">
      <div class="metric-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <div class="metric-label">Total Member</div>
            <small class="text-muted">Semua status</small>
          </div>
          <span class="chip chip-brand">Live</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="metric-value mb-0">{{ $totalMembers }}</div>
          <div class="metric-icon brand"><i class="bi bi-people-fill"></i></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
      <div class="metric-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <div class="metric-label">Aktif</div>
            <small class="text-muted">Dominasi saat ini</small>
          </div>
          <span class="chip chip-brand">{{ $activePercent }}%</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="metric-value mb-0">{{ $activeMembers }}</div>
          <div class="metric-icon sand"><i class="bi bi-check2-circle"></i></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
      <div class="metric-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <div class="metric-label">Expiring <= 7 hari</div>
            <small class="text-muted">Prioritas follow-up</small>
          </div>
          <span class="chip chip-brand">Butuh aksi</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="metric-value mb-0">{{ $expiringSoon }}</div>
          <div class="metric-icon soft"><i class="bi bi-hourglass-split"></i></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
      <div class="metric-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <div class="metric-label">Join bulan ini</div>
            <small class="text-muted">Pertumbuhan terbaru</small>
          </div>
          <span class="chip chip-brand">Bulan ini</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="metric-value mb-0">{{ $newThisMonth }}</div>
          <div class="metric-icon dark"><i class="bi bi-graph-up-arrow"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-5 mb-3">
      <div class="card panel-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <div class="text-muted small">Kesehatan Membership</div>
              <div class="h5 mb-0">{{ $activePercent }}% aktif</div>
            </div>
            <span class="badge px-3 py-2">Stabil</span>
          </div>
          <div class="progress mb-3" style="height: 10px;">
            <div class="progress-bar" role="progressbar" style="width: {{ min($activePercent, 100) }}%;"></div>
          </div>
          <div class="d-flex justify-content-between text-muted small">
            <span>{{ $activeMembers }} aktif</span>
            <span>{{ $inactiveMembers }} tidak aktif</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-7 mb-3">
      <div class="card panel-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <div class="text-muted small">Aksi Harian</div>
              <div class="h5 mb-0">Perlu perhatian admin</div>
            </div>
            <span class="badge px-3 py-2">{{ $expiringSoon }} tiket</span>
          </div>
          <ul class="list-unstyled insight-list mb-0">
            <li class="d-flex align-items-center">
              <span class="insight-dot mr-3" style="background:#fc7753;"></span>
              <div>
                <div class="font-weight-bold mb-0">Membership kedaluwarsa <= 7 hari</div>
                <div class="text-muted small">Hubungi {{ $expiringSoon }} member untuk perpanjangan.</div>
              </div>
            </li>
            <li class="d-flex align-items-center">
              <span class="insight-dot mr-3" style="background:#f6b179;"></span>
              <div>
                <div class="font-weight-bold mb-0">Pertumbuhan bulan ini</div>
                <div class="text-muted small">{{ $newThisMonth }} member baru bergabung bulan ini.</div>
              </div>
            </li>
            <li class="d-flex align-items-center">
              <span class="insight-dot mr-3" style="background:#2b221c;"></span>
              <div>
                <div class="font-weight-bold mb-0">Plan terpopuler</div>
                <div class="text-muted small text-uppercase">Plan {{ $topPlan }} mendominasi pendaftaran.</div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6 mb-3">
      <div class="card chart-card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <span>Distribusi Membership Plan</span>
        </div>
        <div class="card-body">
          <canvas id="planChart" height="220"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6 mb-3">
      <div class="card chart-card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <span>Status Membership</span>
        </div>
        <div class="card-body">
          <canvas id="statusChart" height="220"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card chart-card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <span>Tren Member Join (bulan)</span>
        </div>
        <div class="card-body">
          <canvas id="trendChart" height="130"></canvas>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  (function(){
    const planLabels = @json(array_values($planCounts->keys()->toArray()));
    const planData   = @json(array_values($planCounts->values()->toArray()));
    const statusLabels = @json(array_values($statusCounts->keys()->toArray()));
    const statusData   = @json(array_values($statusCounts->values()->toArray()));
    const trendLabels = @json($joinTrend['labels']);
    const trendData   = @json($joinTrend['values']);
    const palette = ['#fc7753', '#2b221c', '#f6b179', '#d95d39', '#f1c0a2', '#9c7a64'];
    const textColor = '#1f130c';
    const planCtx = document.getElementById('planChart');
    if (planCtx) {
      new Chart(planCtx, {
        type: 'doughnut',
        data: { labels: planLabels.length ? planLabels : ['-'], datasets: [{ data: planData.length ? planData : [1], backgroundColor: palette }] },
        options: { plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, color: textColor } } }, cutout: '68%' }
      });
    }
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
      new Chart(statusCtx, {
        type: 'doughnut',
        data: { labels: statusLabels.length ? statusLabels : ['-'], datasets: [{ data: statusData.length ? statusData : [1], backgroundColor: palette }] },
        options: { plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, color: textColor } } }, cutout: '68%' }
      });
    }
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
      new Chart(trendCtx, {
        type: 'line',
        data: { labels: trendLabels.length ? trendLabels : ['-'], datasets: [{ label: 'Join', data: trendData.length ? trendData : [0], borderColor: '#fc7753', backgroundColor: 'rgba(252,119,83,0.2)', tension: 0.25, fill: true, pointRadius: 4 }] },
        options: { plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } }, scales: { x: { grid: { display: false }, ticks: { color: textColor } }, y: { beginAtZero: true, precision: 0, ticks: { color: textColor }, grid: { color: 'rgba(31,19,12,0.08)' } } } }
      });
    }
  })();
</script>
@endsection
