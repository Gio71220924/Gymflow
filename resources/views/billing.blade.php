{{-- resources/views/billing.blade.php --}}
@extends('layouts.main')

@section('title', 'Billing')
@section('page_heading', 'Billing & Invoices')
@section('card_title', 'Daftar Invoice & Pembayaran')

@section('content')
  @php
    $brandingLogo = $appSettings['branding_logo'] ?? null;
    $brandingName = $appSettings['branding_name'] ?? 'GymFlow';
    $brandingTagline = $appSettings['branding_tagline'] ?? '';
    $brandingAddress = $appSettings['branding_address'] ?? '';
    $canManageBilling = ($isAdmin ?? false) || (Auth::user()->role ?? null) === \App\User::ROLE_SUPER_ADMIN;
    $membershipOptions = $membershipOptions ?? collect();
  @endphp

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ $errors->first() }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  @if($canManageBilling)
    @if($membershipOptions->isEmpty())
      <div class="alert alert-warning">Belum ada membership yang bisa dibuatkan invoice.</div>
    @else
      <div class="card mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              <div class="text-muted text-uppercase small">Tambah Invoice</div>
              <div class="font-weight-bold">Buat tagihan manual</div>
            </div>
            <span class="badge badge-light border">Admin only</span>
          </div>
          <form method="POST" action="{{ route('billing.store') }}">
            @csrf
            <div class="form-row">
              <div class="form-group col-md-4">
                <label class="mb-1">Membership</label>
                <select name="member_membership_id" class="form-control form-control-sm" required>
                  <option value="">Pilih member & plan</option>
                  @foreach($membershipOptions as $mm)
                    @php
                      $m = $mm->member;
                      $p = $mm->plan;
                    @endphp
                    <option value="{{ $mm->id }}" {{ old('member_membership_id') == $mm->id ? 'selected' : '' }}>
                      {{ $m->nama_member ?? ('Member #' . $mm->member_id) }} - {{ $p->nama ?? 'Plan' }} ({{ $mm->start_date }} s/d {{ $mm->end_date }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-2">
                <label class="mb-1">Total Tagihan</label>
                <input type="number" step="0.01" name="total_tagihan" value="{{ old('total_tagihan') }}" class="form-control form-control-sm" placeholder="Otomatis">
                <small class="text-muted">Kosongkan untuk hitung otomatis.</small>
              </div>
              <div class="form-group col-md-2">
                <label class="mb-1">Jatuh Tempo</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-control form-control-sm">
              </div>
              <div class="form-group col-md-2">
                <label class="mb-1">Status</label>
                <select name="status" class="form-control form-control-sm">
                  <option value="menunggu" {{ old('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                  <option value="lunas" {{ old('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                  <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                  <option value="batal" {{ old('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label class="mb-1">Metode Bayar</label>
                <select name="payment_method" class="form-control form-control-sm">
                  <option value="">-</option>
                  <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                  <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                  <option value="ewallet" {{ old('payment_method') === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                  <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                </select>
                <small class="text-muted">Isi jika status "Lunas".</small>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label class="mb-1">Catatan</label>
                <input type="text" name="catatan" value="{{ old('catatan') }}" class="form-control form-control-sm" placeholder="Opsional">
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Tambah Invoice</button>
          </form>
        </div>
      </div>
    @endif
  @endif


  <div class="row mb-4">
    <div class="col-md-3 mb-3">
      <div class="p-3 border rounded" style="background:#fff;">
        <div class="text-muted text-uppercase" style="font-size:12px;">Total Invoice</div>
        <div class="h4 mb-0">{{ $summary['total'] ?? 0 }}</div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="p-3 border rounded" style="background:#fff;">
        <div class="text-muted text-uppercase" style="font-size:12px;">Status Lunas</div>
        <div class="h4 mb-0">{{ $summary['lunas'] ?? 0 }}</div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="p-3 border rounded" style="background:#fff;">
        <div class="text-muted text-uppercase" style="font-size:12px;">Menunggu</div>
        <div class="h4 mb-0">{{ $summary['menunggu'] ?? 0 }}</div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="p-3 border rounded" style="background:#fff;">
        <div class="text-muted text-uppercase" style="font-size:12px;">Total Tagihan</div>
        <div class="h4 mb-0">Rp {{ number_format($summary['total_amount'] ?? 0, 0, ',', '.') }}</div>
      </div>
    </div>
  </div>

  @if(($isAdmin ?? false) && !empty($monthlyRevenue))
  @php $revenue = $monthlyRevenue; @endphp
  <div class="row mb-4 align-items-stretch">
    <div class="col-lg-4 mb-3">
      <div class="p-4 border rounded h-100" style="background:#fff;">
        <div class="text-muted text-uppercase small mb-1">Pendapatan 12 bulan</div>
        <div class="h3 mb-1">Rp {{ number_format($revenue['total'] ?? 0, 0, ',', '.') }}</div>
        <div class="text-muted small">Total pembayaran billing berstatus berhasil dibayar.</div>
        <hr>
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small">Bulan terakhir</div>
            <div class="font-weight-bold mb-0">{{ $revenue['latest_label'] ?? '-' }}</div>
            <div class="text-muted small">Rp {{ number_format($revenue['latest_amount'] ?? 0, 0, ',', '.') }}</div>
          </div>
          <div class="text-right">
            <div class="text-muted small">Invoice lunas</div>
            <div class="h5 mb-0">{{ $revenue['latest_invoice_count'] ?? 0 }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-8 mb-3">
      <div class="p-4 border rounded h-100" style="background:#fff;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <div class="text-muted text-uppercase small">Pendapatan per bulan</div>
            <div class="font-weight-bold">Billing (pembayaran berhasil)</div>
          </div>
          <span class="badge badge-pill badge-light border">12 bulan terakhir</span>
        </div>
        <div style="height: 220px;">
          <canvas id="monthlyRevenueChart" height="200"></canvas>
        </div>
      </div>
    </div>
  </div>
  @endif

  <table class="table table-striped table-bordered w-100" id="billing-table" data-datatable>
    <thead class="thead-light">
      <tr>
        <th>#</th>
        <th>Brand</th>
        <th>Member</th>
        <th>Plan</th>
        <th>Periode</th>
        <th>Invoice</th>
        <th>Status</th>
        <th>Payment</th>
        <th>Jatuh Tempo</th>
        <th>Pembayaran Terakhir</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($invoices as $invoice)
        @php
          $membership = $invoice->memberMembership;
          $member = optional($membership)->member;
          $plan = optional($membership)->plan;
          $payment = $invoice->payments->first();
          $durationMonths = null;
          if ($membership && $membership->start_date && $membership->end_date) {
            $durationMonths = \Carbon\Carbon::parse($membership->start_date)
              ->diffInMonths(\Carbon\Carbon::parse($membership->end_date));
            $durationMonths = max(1, $durationMonths);
          }
          $selectedMethod = $payment->method ?? 'cash';
          $period = $membership ? ($membership->start_date . ' - ' . $membership->end_date) : '-';
          $paidAtLocal = ($payment && $payment->paid_at)
            ? \Carbon\Carbon::parse($payment->paid_at)->timezone('Asia/Jakarta')
            : null;
          $paidAtText = $paidAtLocal
            ? $paidAtLocal->format('d-m H:i:s') . ''
            : '-';
        @endphp
        <tr>
          <td>
            <div class="font-weight-bold">{{ $invoice->nomor_invoice }}</div>
            <small class="text-muted">{{ $invoice->created_at }}</small>
          </td>
          <td>
            @if($brandingLogo)
              <img src="{{ asset('storage/branding/'.$brandingLogo) }}" alt="logo" height="36" class="mb-1"><br>
            @endif
            <div class="font-weight-bold">{{ $brandingName }}</div>
            @if($brandingTagline)<small class="text-muted">{{ $brandingTagline }}</small>@endif
          </td>
          <td>
            <div class="font-weight-bold">{{ $member->nama_member ?? '-' }}</div>
            <small class="text-muted">{{ $member->email_member ?? '-' }}</small>
          </td>
          <td>
            <div>{{ $plan->nama ?? ($membership->plan_id ?? '-') }}</div>
            <small class="text-muted">Durasi: {{ $durationMonths ?? '-' }} bln</small>
          </td>
          <td>{{ $period }}</td>
          <td>
            <div>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</div>
            <small class="text-muted">Diskon: Rp {{ number_format($invoice->diskon, 0, ',', '.') }} | Pajak: Rp {{ number_format($invoice->pajak, 0, ',', '.') }}</small>
          </td>
          <td>
            <span class="badge badge-soft {{ $invoice->status }} text-capitalize">{{ $invoice->status }}</span>
          </td>
          <td>
            @if($payment)
              <div class="d-flex align-items-center">
                <span class="badge badge-soft {{ $payment->status }} mr-2 text-capitalize">{{ $payment->status }}</span>
                <div>
                  <div>Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                  <small class="text-muted">{{ $payment->method }} @ {{ $paidAtText }}</small>
                </div>
              </div>
            @else
              <span class="badge badge-soft pending">Belum ada pembayaran</span>
            @endif
          </td>
          <td>{{ $invoice->due_date ?? '-' }}</td>
          <td>{{ $paidAtText }}</td>
          @if($canManageBilling)
          <td>
            <form method="POST" action="{{ route('billing.update', $invoice->id) }}" class="form-inline">
              @csrf
              @method('PUT')
              <div class="form-group mb-2 mr-2">
                <select name="status" class="form-control form-control-sm">
                  <option value="menunggu" {{ $invoice->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                  <option value="lunas" {{ $invoice->status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                  <option value="draft" {{ $invoice->status === 'draft' ? 'selected' : '' }}>Draft</option>
                  <option value="batal" {{ $invoice->status === 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
              </div>
              <div class="form-group mb-2 mr-2">
                <select name="payment_method" class="form-control form-control-sm">
                  <option value="cash" {{ $selectedMethod === 'cash' ? 'selected' : '' }}>Cash</option>
                  <option value="transfer" {{ $selectedMethod === 'transfer' ? 'selected' : '' }}>Transfer</option>
                  <option value="ewallet" {{ $selectedMethod === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                  <option value="credit_card" {{ $selectedMethod === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                </select>
              </div>
              <input type="hidden" name="amount" value="{{ $invoice->total_tagihan }}">
              <button type="submit" class="btn btn-sm btn-primary mb-2 mr-2">Update</button>
              <a class="btn btn-sm btn-outline-secondary mb-2" href="{{ route('billing.print', $invoice->id) }}" target="_blank" rel="noopener">Cetak</a>
            </form>
          </td>
          @else
          <td>
            <a class="btn btn-sm btn-outline-secondary mb-2" href="{{ route('billing.print', $invoice->id) }}" target="_blank" rel="noopener">Detail / Cetak</a>
            @php
              $canConfirm = !in_array($invoice->status, ['lunas', 'batal'], true);
              $collapseId = 'pay-' . $invoice->id;
            @endphp
            @if($canConfirm)
              <button class="btn btn-sm btn-primary mb-1" type="button" data-toggle="collapse" data-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
                Saya sudah bayar
              </button>
              <div class="collapse mt-2" id="{{ $collapseId }}">
                <div class="border rounded p-2 bg-light">
                  <div class="small mb-1">
                    Total: <strong>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</strong><br>
                    Jatuh tempo: {{ $invoice->due_date ?? '-' }}
                  </div>
                  <form method="POST" action="{{ route('billing.confirm', $invoice->id) }}">
                    @csrf
                    <div class="form-group mb-2">
                      <label class="small mb-1">Metode bayar</label>
                      <select name="method" class="form-control form-control-sm" required>
                        <option value="transfer">Transfer</option>
                        <option value="ewallet">E-Wallet</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                      </select>
                    </div>
                    <div class="form-group mb-2">
                      <label class="small mb-1">Nomor referensi (opsional)</label>
                      <input type="text" name="reference_no" class="form-control form-control-sm" placeholder="ID transaksi / no. transfer">
                    </div>
                    <div class="form-group mb-2">
                      <label class="small mb-1">Catatan (opsional)</label>
                      <textarea name="catatan" rows="2" class="form-control form-control-sm" placeholder="Tanggal bayar, bank, dsb."></textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success">Kirim ke admin</button>
                    <div class="text-muted small mt-1">Status akan di-update oleh admin setelah verifikasi.</div>
                  </form>
                </div>
              </div>
            @else
              <small class="text-muted d-block">Invoice sudah {{ $invoice->status }}.</small>
            @endif
          </td>
          @endif
        </tr>
      @empty
        <tr>
          <td colspan="11" class="text-center text-muted">Belum ada invoice.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
@endsection

@if(($isAdmin ?? false) && !empty($monthlyRevenue))
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  (function() {
    const revenue = @json($monthlyRevenue);
    if (!revenue) return;
    const labels = Array.isArray(revenue.labels) && revenue.labels.length ? revenue.labels : ['-'];
    const amounts = Array.isArray(revenue.amounts) && revenue.amounts.length ? revenue.amounts : [0];
    const ctx = document.getElementById('monthlyRevenueChart');
    if (!ctx) return;

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total dibayar',
          data: amounts,
          backgroundColor: 'rgba(252, 119, 83, 0.18)',
          borderColor: '#fc7753',
          borderWidth: 2,
          borderRadius: 8,
          maxBarThickness: 42,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(context) {
                const value = Number(context.raw || 0);
                return 'Rp ' + value.toLocaleString('id-ID');
              }
            }
          }
        },
        scales: {
          x: {
            grid: { display: false }
          },
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) { return 'Rp ' + Number(value).toLocaleString('id-ID'); }
            },
            grid: { color: 'rgba(31,19,12,0.08)' }
          }
        }
      }
    });
  })();
</script>
@endsection
@endif
