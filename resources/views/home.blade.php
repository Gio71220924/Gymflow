@extends('layouts.main')
@section('title', 'Dashboard')
@section('page_heading', 'Dashboard')
@section('card_title', 'Ringkasan Gym')

@section('styles')
<style>
  .stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }
  .stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
  }
</style>
@endsection

@section('card_actions')
  <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
    Kelola Users
  </a>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-3 mb-3">
      <div class="card stat-card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small">Total Member</div>
            <div class="h4 mb-0">{{ $totalMembers }}</div>
          </div>
          <div class="stat-icon" style="background:#1B98E0;"><i class="bi bi-people-fill"></i></div>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card stat-card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small">Aktif</div>
            <div class="h4 mb-0">{{ $activeMembers }}</div>
          </div>
          <div class="stat-icon" style="background:#34c38f;"><i class="bi bi-check2-circle"></i></div>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card stat-card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small">Expiring ≤ 7 hari</div>
            <div class="h4 mb-0">{{ $expiringSoon }}</div>
          </div>
          <div class="stat-icon" style="background:#f0ad4e;"><i class="bi bi-hourglass-split"></i></div>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card stat-card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small">Join bulan ini</div>
            <div class="h4 mb-0">{{ $newThisMonth }}</div>
          </div>
          <div class="stat-icon" style="background:#fc7753;"><i class="bi bi-graph-up-arrow"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6 mb-3">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Membership Plan</span>
        </div>
        <div class="card-body">
          <canvas id="planChart" height="220"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6 mb-3">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
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
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Tren Member Join (bulan)</span>
        </div>
        <div class="card-body">
          <canvas id="trendChart" height="120"></canvas>
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

    // Plan pie
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
        options: { plugins: { legend: { position: 'bottom' } } }
      });
    }

    // Status pie
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
        options: { plugins: { legend: { position: 'bottom' } } }
      });
    }

    // Trend line
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
            backgroundColor: 'rgba(27,152,224,0.15)',
            tension: 0.25,
            fill: true,
            pointRadius: 4,
          }]
        },
        options: {
          scales: {
            y: { beginAtZero: true, precision: 0 }
          }
        }
      });
    }
  })();
</script>
@endsection
