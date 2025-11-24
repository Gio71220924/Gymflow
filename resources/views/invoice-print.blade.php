{{-- resources/views/invoice-print.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Invoice - {{ $invoice->nomor_invoice }}</title>
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
  <style>
    body { background: #f8f9fa; }
    .invoice-card {
      max-width: 820px;
      margin: 30px auto;
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.08);
      padding: 32px;
    }
    .invoice-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }
    .title {
      font-weight: 700;
      letter-spacing: -0.02em;
    }
    .badge-soft {
      font-weight: 600;
      padding: 0.3rem 0.75rem;
      border-radius: 10px;
    }
    .badge-soft.lunas { background: #e6f4ea; color: #1b8a3d; }
    .badge-soft.menunggu { background: #fff4e5; color: #b45d00; }
    .badge-soft.draft { background: #eef2f7; color: #4b5563; }
    .badge-soft.batal { background: #fdecec; color: #b91c1c; }
    .table-borderless td {
      padding: 0.35rem 0;
      border: none;
    }
    @media print {
      .no-print { display: none !important; }
      body { background: #fff; }
      .invoice-card { box-shadow: none; border: 1px solid #ccc; }
    }
  </style>
</head>
<body>
  <div class="invoice-card">
    <div class="invoice-header">
      <div class="d-flex align-items-center">
        @php
          $brandingLogo = $appSettings['branding_logo'] ?? null;
          $brandingName = $appSettings['branding_name'] ?? 'GymFlow';
          $brandingTagline = $appSettings['branding_tagline'] ?? '';
          $brandingAddress = $appSettings['branding_address'] ?? '';
        @endphp
        @if($brandingLogo)
          <img src="{{ asset('storage/branding/'.$brandingLogo) }}" alt="Logo" style="height:48px;" class="mr-3">
        @endif
        <div>
          <h4 class="title mb-1">{{ $brandingName }}</h4>
          @if($brandingTagline)<div class="text-muted">{{ $brandingTagline }}</div>@endif
          <div class="text-muted">Invoice: {{ $invoice->nomor_invoice }}</div>
        </div>
      </div>
      <div class="text-right">
        <div class="badge-soft {{ $invoice->status }} text-capitalize">{{ $invoice->status }}</div>
        <div class="text-muted">Tanggal: {{ optional($invoice->created_at)->format('Y-m-d') }}</div>
      </div>
    </div>

    @php
      $membership = $invoice->memberMembership;
      $member = optional($membership)->member;
      $plan = optional($membership)->plan;
      $payment = $invoice->payments->first();
      $subtotal = $invoice->total_tagihan;
      $durationMonths = null;
      if ($membership && $membership->start_date && $membership->end_date) {
        $durationMonths = \Carbon\Carbon::parse($membership->start_date)
          ->diffInMonths(\Carbon\Carbon::parse($membership->end_date));
        $durationMonths = max(1, $durationMonths);
      }
      $diskon = $invoice->diskon;
      $pajak = $invoice->pajak;
      $grandTotal = $subtotal - $diskon + $pajak;
    @endphp

    <div class="row mb-4">
      <div class="col-md-6">
        <h6 class="text-uppercase text-muted">Tagih Ke</h6>
        <table class="table table-borderless mb-0">
          <tr><td>Nama</td><td>: {{ $member->nama_member ?? '-' }}</td></tr>
          <tr><td>Email</td><td>: {{ $member->email_member ?? '-' }}</td></tr>
          <tr><td>Telepon</td><td>: {{ $member->nomor_telepon_member ?? '-' }}</td></tr>
        </table>
      </div>
      <div class="col-md-6 text-md-right mt-3 mt-md-0">
        <h6 class="text-uppercase text-muted">Detail</h6>
        <table class="table table-borderless mb-0">
          <tr><td>Plan</td><td>: {{ $plan->nama ?? '-' }}</td></tr>
          <tr><td>Durasi</td><td>: {{ $durationMonths ?? '-' }} bulan</td></tr>
          <tr><td>Periode</td><td>: {{ optional($membership)->start_date }} - {{ optional($membership)->end_date }}</td></tr>
          <tr><td>Jatuh Tempo</td><td>: {{ $invoice->due_date ?? '-' }}</td></tr>
          @if($brandingAddress)<tr><td>Alamat</td><td>: {{ $brandingAddress }}</td></tr>@endif
        </table>
      </div>
    </div>

    <div class="table-responsive mb-4">
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>Deskripsi</th>
            <th class="text-right" style="width: 160px;">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Paket {{ $plan->nama ?? '-' }} ({{ $durationMonths ?? '-' }} bulan)</td>
            <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
          </tr>
          <tr>
            <td>Diskon</td>
            <td class="text-right">- Rp {{ number_format($diskon, 0, ',', '.') }}</td>
          </tr>
          <tr>
            <td>Pajak</td>
            <td class="text-right">Rp {{ number_format($pajak, 0, ',', '.') }}</td>
          </tr>
          <tr>
            <th>Total</th>
            <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mb-4">
      <h6 class="text-uppercase text-muted">Pembayaran</h6>
      @if($payment)
        <div class="d-flex align-items-center">
          <span class="badge-soft {{ $payment->status }} mr-2 text-capitalize">{{ $payment->status }}</span>
          <div>
            <div>Rp {{ number_format($payment->amount, 0, ',', '.') }} via {{ $payment->method }}</div>
            <small class="text-muted">Pada: {{ $payment->paid_at ?? '-' }}</small>
          </div>
        </div>
      @else
        <div class="text-muted">Belum ada pembayaran.</div>
      @endif
    </div>

    <div class="text-right no-print">
      <button class="btn btn-primary" onclick="window.print()">Cetak</button>
      <a href="{{ route('billing') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
  </div>
</body>
</html>
