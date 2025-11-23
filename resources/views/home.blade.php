@extends('layouts.main')
@section('title', 'Dashboard Admin')
@section('page_heading', 'Dashboard')
@section('card_title', 'Command Center')

@section('styles')
<style>
  /* Content Background with subtle gradients */
  .content{
    background:
      radial-gradient(circle at 12% 18%, rgba(252,119,83,0.04), transparent 25%),
      radial-gradient(circle at 90% 12%, rgba(40,35,28,0.04), transparent 20%),
      var(--neutral-bg);
  }

  /* Buttons */
  .btn-brand{
    background: var(--brand-primary);
    border-color: var(--brand-primary);
    color: #fff;
    border-radius: var(--radius-sm);
    padding: var(--space-2) var(--space-4);
    font-weight: 600;
    font-size: var(--text-sm);
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(252,119,83,0.2);
  }
  .btn-brand:hover{
    background: #e96a49;
    border-color: #e05f3f;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(252,119,83,0.3);
  }
  .btn-outline-ink{
    color: var(--brand-dark);
    border-color: var(--neutral-border);
    background: transparent;
    border-radius: var(--radius-sm);
    padding: var(--space-2) var(--space-4);
    font-weight: 600;
    font-size: var(--text-sm);
    transition: all 0.2s ease;
  }
  .btn-outline-ink:hover{
    color: var(--brand-dark);
    background: var(--neutral-light);
    border-color: var(--brand-primary);
  }

  /* Dashboard Hero Section */
  .dashboard-hero{
    position: relative;
    overflow: hidden;
    border-radius: var(--radius-lg);
    padding: var(--space-6);
    color: #fff;
    background: linear-gradient(135deg, #2b221c 0%, #3d2f24 50%, #fc7753 120%);
    display: flex;
    gap: var(--space-6);
    align-items: center;
    box-shadow: 0 20px 50px rgba(31,19,12,0.18);
    margin-bottom: var(--space-5);
  }
  .dashboard-hero::after{
    content:"";
    position:absolute;
    inset:-10% 40% auto -5%;
    height:150%;
    background:
      radial-gradient(circle at 25% 20%, rgba(255,255,255,0.12), transparent 40%),
      radial-gradient(circle at 80% 50%, rgba(255,255,255,0.15), transparent 45%);
    opacity: 0.85;
    pointer-events: none;
  }
  .hero-head{
    position: relative;
    z-index: 1;
    flex: 1;
  }
  
  /* Hero Pills */
  .dashboard-hero .hero-pill{
    display: inline-flex;
    align-items: center;
    padding: var(--space-2) var(--space-3);
    border-radius: 999px;
    font-weight: 600;
    font-size: var(--text-xs);
    background: rgba(255,255,255,0.18);
    letter-spacing: 0.3px;
    text-transform: uppercase;
  }
  .dashboard-hero .hero-pill-soft{
    background: rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.9);
  }
  
  /* Hero Typography */
  .hero-title{
    font-weight: 700;
    font-size: 1.75rem;
    letter-spacing: -0.02em;
    margin-bottom: var(--space-2);
  }
  .hero-sub{
    color: rgba(255,255,255,0.85);
    max-width: 640px;
    font-size: var(--text-base);
    line-height: 1.6;
  }
  
  /* Hero Actions */
  .hero-actions{
    margin-top: var(--space-4);
    margin-bottom: var(--space-5);
  }
  .hero-actions .btn{
    border-radius: var(--radius-sm);
    font-weight: 600;
    padding: var(--space-2) var(--space-4);
    font-size: var(--text-sm);
  }
  
  /* Hero Stats */
  .hero-stats{
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-3);
  }
  .hero-stat-box{
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-md);
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    min-width: 140px;
    border: 1px solid rgba(255,255,255,0.1);
  }
  .hero-stat-label{
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-size: var(--text-xs);
    color: rgba(255,255,255,0.75);
    margin-bottom: var(--space-1);
    font-weight: 600;
  }
  .hero-stat-value{
    font-weight: 800;
    font-size: 1.5rem;
    color: #fff;
  }

  /* Hero Panel (Health Score) */
  .hero-panel{
    min-width: 280px;
    max-width: 340px;
    margin-left: auto;
    padding: var(--space-5);
    border-radius: var(--radius-lg);
    background: #fff;
    color: var(--brand-ink);
    box-shadow: 0 16px 40px rgba(31,19,12,0.2);
    position: relative;
    z-index: 1;
  }
  .hero-panel .label{
    font-size: var(--text-xs);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--neutral-text);
    margin-bottom: var(--space-2);
    font-weight: 600;
  }
  .hero-score{
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--brand-dark);
    line-height: 1;
  }
  .hero-progress{
    height: 8px;
    background: var(--neutral-light);
    border-radius: 999px;
    overflow: hidden;
  }
  .hero-progress-bar{
    height: 100%;
    background: linear-gradient(90deg, var(--brand-primary), #f6b179);
    border-radius: 999px;
  }
  
  /* Hero Alert */
  .hero-alert{
    padding: var(--space-3);
    border-radius: var(--radius-md);
    background: #fffbf7;
    border: 1px solid var(--neutral-border);
  }
  .hero-alert-icon{
    width: 36px;
    height: 36px;
    border-radius: var(--radius-sm);
    background: rgba(252,119,83,0.12);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: var(--brand-primary);
  }
  
  /* Hero Badges */
  .hero-badges{
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-2);
  }
  .mini-badge{
    padding: var(--space-2) var(--space-3);
    border-radius: var(--radius-sm);
    background: var(--neutral-light);
    color: var(--brand-dark);
    font-weight: 600;
    font-size: var(--text-xs);
    border: 1px solid var(--neutral-border);
  }

  /* Metric Cards */
  .metric-card{
    border: 1px solid var(--neutral-border);
    border-radius: var(--radius-lg);
    background: var(--neutral-card);
    box-shadow: var(--shadow-sm);
    padding: var(--space-5);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
  }
  .metric-card:hover{
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
  }
  .metric-card .metric-label{
    font-weight: 600;
    color: var(--brand-dark);
    letter-spacing: 0.01em;
    font-size: var(--text-sm);
    margin-bottom: var(--space-1);
  }
  .metric-card .metric-value{
    font-size: 2rem;
    font-weight: 800;
    color: var(--brand-dark);
    line-height: 1;
  }
  .metric-icon{
    width: 44px;
    height: 44px;
    border-radius: var(--radius-md);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
  }
  .metric-icon.brand{ 
    background: rgba(252,119,83,0.12); 
    color: var(--brand-primary); 
  }
  .metric-icon.sand{ 
    background: rgba(246,177,121,0.18); 
    color: #a35638; 
  }
  .metric-icon.dark{ 
    background: rgba(43,34,28,0.12); 
    color: var(--brand-dark); 
  }
  .metric-icon.soft{ 
    background: rgba(252,119,83,0.08); 
    color: #d95d39; 
  }
  
  /* Chips */
  .chip{
    display: inline-flex;
    align-items: center;
    padding: var(--space-1) var(--space-3);
    border-radius: 999px;
    font-size: var(--text-xs);
    font-weight: 700;
    border: 1px solid var(--neutral-border);
  }
  .chip-brand{ 
    background: rgba(252,119,83,0.1); 
    color: var(--brand-primary);
    border-color: rgba(252,119,83,0.2);
  }

  /* Panel Cards */
  .panel-card{
    border: 1px solid var(--neutral-border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    background: var(--neutral-card);
  }
  .panel-card .card-body{
    padding: var(--space-5);
  }
  .panel-card .badge{
    border-radius: var(--radius-sm);
    padding: var(--space-2) var(--space-3);
    background: rgba(252,119,83,0.1);
    color: var(--brand-primary);
    font-weight: 600;
    font-size: var(--text-xs);
  }
  
  /* Progress Bars */
  .progress{
    background: var(--neutral-light);
    border-radius: 999px;
    height: 8px;
  }
  .progress-bar{
    background: linear-gradient(90deg, var(--brand-primary), #f6b179);
    border-radius: 999px;
  }
  
  /* Insight List */
  .insight-list li{
    padding: var(--space-3) 0;
    border-bottom: 1px solid var(--neutral-border);
  }
  .insight-list li:last-child{ 
    border-bottom: 0; 
  }
  .insight-dot{
    width: 10px;
    height: 10px;
    border-radius: 999px;
    display: inline-block;
    flex-shrink: 0;
  }

  /* Chart Cards */
  .chart-card{
    border: 1px solid var(--neutral-border);
    border-radius: var(--radius-lg);
    background: var(--neutral-card);
    box-shadow: var(--shadow-sm);
  }
  .chart-card .card-header{
    background: transparent;
    border-bottom: 1px solid var(--neutral-border);
    padding: var(--space-5);
    font-weight: 600;
    color: var(--brand-dark);
    font-size: var(--text-base);
  }
  .chart-card .card-body{
    padding: var(--space-5);
  }
  .chart-card canvas{
    max-height: 260px;
  }

  /* Responsive */
  @media (max-width: 992px){
    .dashboard-hero{
      flex-direction: column;
      align-items: flex-start;
      padding: var(--space-5);
    }
    .hero-panel{
      width: 100%;
      max-width: none;
      margin-left: 0;
      margin-top: var(--space-4);
    }
    .hero-title{
      font-size: 1.5rem;
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
