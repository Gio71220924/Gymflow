@extends('layouts.main')
@section('title', 'Dashboard Admin')
@section('page_heading', 'Dashboard')
@section('card_title', 'Command Center')

@section('styles')
<style>
  .dashboard-hero{
    position: relative;
    overflow: hidden;
    border-radius: 18px;
    padding: 24px;
    color: #fff;
    background: linear-gradient(120deg,#0f172a 0%,#1b98e0 50%,#0f4c75 100%);
    display: flex;
    gap: 28px;
    align-items: center;
  }
  .dashboard-hero .hero-pill{
    display: inline-flex;
    align-items: center;
    padding: 5px 10px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.8rem;
    background: rgba(255,255,255,0.15);
    letter-spacing: .5px;
  }
  .dashboard-hero .hero-pill-soft{
    background: rgba(255,255,255,0.08);
  }
  .hero-title{
    font-weight: 700;
    letter-spacing: -0.02em;
  }
  .hero-sub{
    color: rgba(255,255,255,0.85);
    max-width: 640px;
  }
  .hero-actions .btn{
    border-radius: 999px;
  }
  .hero-panel{
    min-width: 280px;
    max-width: 320px;
    margin-left: auto;
    padding: 18px;
    border-radius: 14px;
    background: rgba(0,0,0,0.15);
    box-shadow: 0 10px 30px rgba(15,23,42,0.25);
  }
  .hero-score{
    font-size: 2.6rem;
    font-weight: 800;
  }
  .hero-progress{
    height: 9px;
    background: rgba(255,255,255,0.15);
    border-radius: 999px;
    overflow: hidden;
  }
  .hero-progress-bar{
    height: 100%;
    background: linear-gradient(90deg,#34c38f,#1b98e0);
  }
  .hero-alert{
    padding: 10px 12px;
    border-radius: 12px;
    background: rgba(255,255,255,0.08);
  }
  .hero-alert-icon{
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(255,255,255,0.14);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
  }
  .hero-highlight .h4{
    font-weight: 700;
  }
  .hero-decoration{
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 30% 20%,rgba(255,255,255,0.12),transparent 30%),
                radial-gradient(circle at 80% 40%,rgba(255,255,255,0.12),transparent 30%);
    pointer-events: none;
  }

  .metric-card{
    border: none;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 12px 30px rgba(15,23,42,0.06);
    padding: 18px;
    position: relative;
    overflow: hidden;
  }
  .metric-card .metric-label{
    font-weight: 600;
    color: #1f2937;
  }
  .metric-card .metric-value{
    font-size: 1.8rem;
    font-weight: 800;
  }
  .metric-icon{
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
  }
  .chip{
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: .8rem;
    font-weight: 600;
  }
  .chip-blue{ background: rgba(27,152,224,0.12); color: #0f4c75; }
  .chip-green{ background: rgba(52,195,143,0.12); color: #0f5132; }
  .chip-amber{ background: rgba(240,173,78,0.16); color: #8a6d3b; }
  .chip-orange{ background: rgba(252,119,83,0.14); color: #a04524; }

  .panel-card{
    border: none;
    border-radius: 14px;
    box-shadow: 0 10px 26px rgba(0,0,0,0.05);
  }
  .insight-list li+li{
    margin-top: 12px;
  }
  .insight-dot{
    width: 12px;
    height: 12px;
    border-radius: 999px;
    display: inline-block;
  }
  .chart-card .card-header{
    background: transparent;
    border-bottom: 0;
    font-weight: 700;
  }
  .chart-card canvas{
    max-height: 260px;
  }
  @media (max-width: 992px){
    .dashboard-hero{
      flex-direction: column;
      align-items: flex-start;
    }
    .hero-panel{
      width: 100%;
      max-width: none;
    }
  }
</style>
@endsection

@section('card_actions')
  <div class="btn-group">
    <a href="{{ route('add-member') }}" class="btn btn-primary btn-sm">
      Tambah Member
    </a>
    <a href="{{ route('member') }}" class="btn btn-outline-secondary btn-sm">
      Kelola Member
    </a>
  </div>
@endsection

@section('content')
  @php
    $activePercent   = $totalMembers ? round(($activeMembers / max($totalMembers, 1)) * 100) : 0;
    $inactivePercent = $totalMembers ? round(($inactiveMembers / max($totalMembers, 1)) * 100) : 0;
    $topPlan = $planCounts->count()
      ? $planCounts->sortByDesc(function ($count) { return $count; })->keys()->first()
      : '-';
  @endphp

  <div class="dashboard-hero mb-4">
    <div class="hero-decoration"></div>
    <div class="position-relative" style="z-index:1; flex:1;">
      <div class="d-flex align-items-center mb-2">
        <span class="hero-pill">Admin</span>
        <span class="hero-pill hero-pill-soft ml-2">Realtime</span>
      </div>
      <h2 class="hero-title mb-2">GymFlow Control Center</h2>
      <p class="hero-sub mb-3">
        Pantau kesehatan membership, tindak lanjuti member berisiko, dan jalankan aksi harian tanpa keluar dari dashboard.
      </p>
      <div class="hero-actions mb-3">
        <a href="{{ route('member') }}" class="btn btn-light btn-sm mr-2">
          Lihat Data Member
        </a>
        <a href="{{ route('add-member') }}" class="btn btn-outline-light btn-sm">
          Tambah Member Baru
        </a>
      </div>
      <div class="d-flex flex-wrap">
        <div class="hero-highlight mr-4 mb-2">
          <div class="text-white-50 small text-uppercase">Total Member</div>
          <div class="h4 mb-0">{{ $totalMembers }}</div>
        </div>
        <div class="hero-highlight mr-4 mb-2">
          <div class="text-white-50 small text-uppercase">Aktif</div>
          <div class="h4 mb-0">{{ $activeMembers }}</div>
        </div>
        <div class="hero-highlight mb-2">
          <div class="text-white-50 small text-uppercase">Tidak Aktif</div>
          <div class="h4 mb-0">{{ $inactiveMembers }}</div>
        </div>
      </div>
    </div>

    <div class="hero-panel position-relative" style="z-index:1;">
      <div class="small text-uppercase text-white-50 mb-2">Health Score</div>
      <div class="d-flex align-items-center mb-3">
        <div class="hero-score">{{ $activePercent }}%</div>
        <div class="flex-fill ml-3">
          <div class="hero-progress mb-1">
            <div class="hero-progress-bar" style="width: {{ min($activePercent, 100) }}%;"></div>
          </div>
          <div class="small text-white-50">Aktif vs total membership</div>
        </div>
      </div>
      <div class="hero-alert d-flex align-items-center">
        <div class="hero-alert-icon"><i class="bi bi-bell"></i></div>
        <div class="ml-3">
          <div class="text-white small font-weight-bold">{{ $expiringSoon }} membership kedaluwarsa <= 7 hari</div>
          <div class="text-white-50 small">Siapkan reminder atau tawarkan perpanjangan.</div>
        </div>
      </div>
      <div class="d-flex flex-wrap mt-3 text-white-50 small">
        <div class="mr-3">Join bulan ini: <span class="text-white font-weight-bold">{{ $newThisMonth }}</span></div>
        <div>Plan favorit: <span class="text-white font-weight-bold text-uppercase">{{ $topPlan }}</span></div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-6 col-lg-3 mb-3">
      <div class="metric-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="metric-label">Total Member</div>
          <span class="chip chip-blue">Semua</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="metric-value mb-0">{{ $totalMembers }}</div>
          <div class="metric-icon" style="background:#1b98e0;"><i class="bi bi-people-fill"></i></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
      <div class="metric-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="metric-label">Aktif</div>
          <span class="chip chip-green">{{ $activePercent }}%</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="metric-value mb-0">{{ $activeMembers }}</div>
          <div class="metric-icon" style="background:#34c38f;"><i class="bi bi-check2-circle"></i></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
      <div class="metric-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="metric-label">Expiring <= 7 hari</div>
          <span class="chip chip-amber">Butuh aksi</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="metric-value mb-0">{{ $expiringSoon }}</div>
          <div class="metric-icon" style="background:#f0ad4e;"><i class="bi bi-hourglass-split"></i></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
      <div class="metric-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="metric-label">Join bulan ini</div>
          <span class="chip chip-orange">Growth</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="metric-value mb-0">{{ $newThisMonth }}</div>
          <div class="metric-icon" style="background:#fc7753;"><i class="bi bi-graph-up-arrow"></i></div>
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
            <span class="badge badge-success px-3 py-2">Stabil</span>
          </div>
          <div class="progress mb-3" style="height: 10px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($activePercent, 100) }}%;"></div>
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
            <span class="badge badge-warning px-3 py-2">{{ $expiringSoon }} tiket</span>
          </div>
          <ul class="list-unstyled insight-list mb-0">
            <li class="d-flex align-items-center">
              <span class="insight-dot mr-3" style="background:#f0ad4e;"></span>
              <div>
                <div class="font-weight-bold mb-0">Membership kedaluwarsa <= 7 hari</div>
                <div class="text-muted small">Hubungi {{ $expiringSoon }} member untuk perpanjangan.</div>
              </div>
            </li>
            <li class="d-flex align-items-center">
              <span class="insight-dot mr-3" style="background:#1b98e0;"></span>
              <div>
                <div class="font-weight-bold mb-0">Pertumbuhan bulan ini</div>
                <div class="text-muted small">{{ $newThisMonth }} member baru bergabung bulan ini.</div>
              </div>
            </li>
            <li class="d-flex align-items-center">
              <span class="insight-dot mr-3" style="background:#34c38f;"></span>
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

    const palette = ['#1B98E0', '#fc7753', '#34c38f', '#f0ad4e', '#6f42c1', '#20c997'];
    const textColor = '#1f2937';

    const planCtx = document.getElementById('planChart');
    if (planCtx) {
      new Chart(planCtx, {
        type: 'doughnut',
        data: {
          labels: planLabels.length ? planLabels : ['-'],
          datasets: [{
            data: planData.length ? planData : [1],
            backgroundColor: palette,
          }]
        },
        options: {
          plugins: {
            legend: {
              position: 'bottom',
              labels: { usePointStyle: true, color: textColor }
            }
          },
          cutout: '68%'
        }
      });
    }

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
      new Chart(statusCtx, {
        type: 'doughnut',
        data: {
          labels: statusLabels.length ? statusLabels : ['-'],
          datasets: [{
            data: statusData.length ? statusData : [1],
            backgroundColor: palette,
          }]
        },
        options: {
          plugins: {
            legend: {
              position: 'bottom',
              labels: { usePointStyle: true, color: textColor }
            }
          },
          cutout: '68%'
        }
      });
    }

    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
      new Chart(trendCtx, {
        type: 'line',
        data: {
          labels: trendLabels.length ? trendLabels : ['-'],
          datasets: [{
            label: 'Join',
            data: trendData.length ? trendData : [0],
            borderColor: '#1B98E0',
            backgroundColor: 'rgba(27,152,224,0.18)',
            tension: 0.25,
            fill: true,
            pointRadius: 4,
          }]
        },
        options: {
          plugins: {
            legend: { display: false },
            tooltip: { mode: 'index', intersect: false }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: { color: textColor }
            },
            y: {
              beginAtZero: true,
              precision: 0,
              ticks: { color: textColor },
              grid: { color: 'rgba(0,0,0,0.05)' }
            }
          }
        }
      });
    }
  })();
</script>
@endsection
