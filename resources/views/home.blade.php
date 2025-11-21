@extends('layouts.main')
@section('title', 'Dashboard Admin')
@section('page_heading', 'Dashboard')
@section('card_title', 'Command Center')

@section('styles')
<style>
  :root{
    --brand:#FC7753;
    --brand-dark:#2b221c;
    --brand-ink:#1f130c;
    --brand-soft:#fff2ea;
    --line:rgba(31,19,12,0.08);
  }

  .content{
    background:
      radial-gradient(circle at 12% 18%, rgba(252,119,83,0.08), transparent 22%),
      radial-gradient(circle at 90% 12%, rgba(40,35,28,0.08), transparent 18%),
      #f9f6f2;
  }

  .card.shadow-sm{
    border: 1px solid var(--line);
    background: #fffaf6;
    box-shadow: 0 18px 44px rgba(31,19,12,0.07);
  }
  .btn-brand{
    background: var(--brand);
    border-color: var(--brand);
    color: #fff;
  }
  .btn-brand:hover{
    background: #e96a49;
    border-color: #e05f3f;
    color: #fff;
  }
  .btn-outline-ink{
    color: var(--brand-ink);
    border-color: rgba(31,19,12,0.2);
  }
  .btn-outline-ink:hover{
    color: var(--brand-dark);
    background: rgba(31,19,12,0.06);
    border-color: rgba(31,19,12,0.28);
  }

  .dashboard-hero{
    position: relative;
    overflow: hidden;
    border-radius: 18px;
    padding: 26px;
    color: #fff;
    background: linear-gradient(125deg, var(--brand-dark) 0%, #34271f 55%, #fc7753 130%);
    display: flex;
    gap: 24px;
    align-items: center;
    box-shadow: 0 22px 60px rgba(31,19,12,0.22);
  }
  .dashboard-hero::after{
    content:"";
    position:absolute;
    inset:-8% 40% auto -4%;
    height:140%;
    background:
      radial-gradient(circle at 24% 20%, rgba(255,255,255,0.13), transparent 38%),
      radial-gradient(circle at 78% 50%, rgba(255,255,255,0.18), transparent 42%);
    opacity: 0.9;
    pointer-events: none;
  }
  .hero-head{
    position: relative;
    z-index: 1;
    flex: 1;
  }
  .dashboard-hero .hero-pill{
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.82rem;
    background: rgba(255,255,255,0.16);
    letter-spacing: .4px;
  }
  .dashboard-hero .hero-pill-soft{
    background: rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.9);
  }
  .hero-title{
    font-weight: 700;
    letter-spacing: -0.02em;
  }
  .hero-sub{
    color: rgba(255,255,255,0.82);
    max-width: 640px;
  }
  .hero-actions .btn{
    border-radius: 10px;
    font-weight: 600;
  }
  .hero-stats{
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
  }
  .hero-stat-box{
    padding: 12px 14px;
    border-radius: 12px;
    background: rgba(255,255,255,0.08);
    min-width: 150px;
  }
  .hero-stat-label{
    text-transform: uppercase;
    letter-spacing: .08em;
    font-size: .7rem;
    color: rgba(255,255,255,0.7);
    margin-bottom: 4px;
  }
  .hero-stat-value{
    font-weight: 800;
    font-size: 1.3rem;
  }
  .hero-panel{
    min-width: 280px;
    max-width: 340px;
    margin-left: auto;
    padding: 18px;
    border-radius: 14px;
    background: #fff8f1;
    color: var(--brand-ink);
    box-shadow: 0 18px 36px rgba(31,19,12,0.25);
    position: relative;
    z-index: 1;
  }
  .hero-panel .label{
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #a47f69;
    margin-bottom: 6px;
  }
  .hero-score{
    font-size: 2.6rem;
    font-weight: 800;
    color: var(--brand-dark);
  }
  .hero-progress{
    height: 9px;
    background: rgba(31,19,12,0.08);
    border-radius: 999px;
    overflow: hidden;
  }
  .hero-progress-bar{
    height: 100%;
    background: linear-gradient(90deg,#fc7753,#f6b179);
  }
  .hero-alert{
    padding: 10px 12px;
    border-radius: 12px;
    background: #fffaf6;
    border: 1px dashed rgba(31,19,12,0.14);
  }
  .hero-alert-icon{
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(252,119,83,0.18);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: #c44f35;
  }
  .hero-badges{
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }
  .mini-badge{
    padding: 6px 10px;
    border-radius: 10px;
    background: var(--brand-soft);
    color: var(--brand-dark);
    font-weight: 700;
    border: 1px solid rgba(31,19,12,0.08);
  }

  .metric-card{
    border: 1px solid var(--line);
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 12px 28px rgba(31,19,12,0.05);
    padding: 18px;
    position: relative;
    overflow: hidden;
  }
  .metric-card .metric-label{
    font-weight: 700;
    color: var(--brand-ink);
    letter-spacing: .01em;
  }
  .metric-card .metric-value{
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--brand-dark);
  }
  .metric-icon{
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .metric-icon.brand{ background: rgba(252,119,83,0.18); color: #c44f35; }
  .metric-icon.sand{ background: rgba(246,177,121,0.25); color: #a35638; }
  .metric-icon.dark{ background: rgba(43,34,28,0.16); color: #2b221c; }
  .metric-icon.soft{ background: rgba(255,242,234,0.92); color: #d95d39; }
  .chip{
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: .8rem;
    font-weight: 700;
    border: 1px solid rgba(31,19,12,0.12);
  }
  .chip-brand{ background: var(--brand-soft); color: var(--brand-dark); }

  .panel-card{
    border: 1px solid var(--line);
    border-radius: 16px;
    box-shadow: 0 14px 32px rgba(31,19,12,0.06);
    background: #fff;
  }
  .panel-card .badge{
    border-radius: 10px;
    padding: 6px 10px;
    background: var(--brand-soft);
    color: var(--brand-dark);
  }
  .progress{
    background: rgba(31,19,12,0.06);
    border-radius: 12px;
  }
  .progress-bar{
    background: linear-gradient(90deg,#fc7753,#f6b179);
  }
  .insight-list li+li{
    margin-top: 8px;
  }
  .insight-list li{
    padding: 10px 0;
    border-bottom: 1px solid var(--line);
  }
  .insight-list li:last-child{ border-bottom: 0; }
  .insight-dot{
    width: 12px;
    height: 12px;
    border-radius: 999px;
    display: inline-block;
  }

  .chart-card{
    border: 1px solid var(--line);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 14px 32px rgba(31,19,12,0.06);
  }
  .chart-card .card-header{
    background: transparent;
    border-bottom: 0;
    font-weight: 700;
    color: var(--brand-ink);
  }
  .chart-card canvas{
    max-height: 240px;
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
    <a href="{{ route('add-member') }}" class="btn btn-brand btn-sm text-white">
      Tambah Member
    </a>
    <a href="{{ route('member') }}" class="btn btn-outline-ink btn-sm">
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
    <div class="hero-head">
      <div class="d-flex align-items-center mb-3">
        <span class="hero-pill">GymFlow Admin</span>
        <span class="hero-pill hero-pill-soft ml-2">Realtime data</span>
      </div>
      <h2 class="hero-title mb-2">Dashboard Operasional</h2>
      <p class="hero-sub mb-3">
        Kendalikan membership, pantau tren, dan jalankan tugas harian dengan tampilan minimalis selaras warna GymFlow.
      </p>
      <div class="hero-actions mb-4">
        <a href="{{ route('member') }}" class="btn btn-light btn-sm mr-2">
          Lihat Member
        </a>
        <a href="{{ route('add-member') }}" class="btn btn-outline-light btn-sm border-light">
          Tambah Member Baru
        </a>
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
          <div class="hero-progress mb-2">
            <div class="hero-progress-bar" style="width: {{ min($activePercent, 100) }}%;"></div>
          </div>
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
            borderColor: '#fc7753',
            backgroundColor: 'rgba(252,119,83,0.2)',
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
              grid: { color: 'rgba(31,19,12,0.08)' }
            }
          }
        }
      });
    }
  })();
</script>
@endsection
