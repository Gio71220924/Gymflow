{{-- resources/views/billing.blade.php --}}
@extends('layouts.main')

@section('title', 'Billing')
@section('page_heading', 'Billing & Invoices')
@section('card_title', 'Daftar Invoice & Pembayaran')

@section('styles')
<style>
  .badge-soft {
    font-weight: 600;
    padding: 0.4rem 0.65rem;
    border-radius: 10px;
  }
  .badge-soft.lunas { background: #e6f4ea; color: #1b8a3d; }
  .badge-soft.menunggu { background: #fff4e5; color: #b45d00; }
  .badge-soft.draft { background: #eef2f7; color: #4b5563; }
  .badge-soft.batal { background: #fdecec; color: #b91c1c; }
  .badge-soft.pending { background: #fff4e5; color: #b45d00; }
  .badge-soft.berhasil { background: #e6f4ea; color: #1b8a3d; }
  .badge-soft.gagal { background: #fdecec; color: #b91c1c; }
</style>
@endsection

@section('content')
  @php
    $brandingLogo = $appSettings['branding_logo'] ?? null;
    $brandingName = $appSettings['branding_name'] ?? 'GymFlow';
    $brandingTagline = $appSettings['branding_tagline'] ?? '';
    $brandingAddress = $appSettings['branding_address'] ?? '';
    $canManageBilling = ($isAdmin ?? false) || (Auth::user()->role ?? null) === \App\User::ROLE_SUPER_ADMIN;
  @endphp

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
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
                  <small class="text-muted">{{ $payment->method }} @ {{ $payment->paid_at ?? '-' }}</small>
                </div>
              </div>
            @else
              <span class="badge badge-soft pending">Belum ada pembayaran</span>
            @endif
          </td>
          <td>{{ $invoice->due_date ?? '-' }}</td>
          <td>{{ $payment->paid_at ?? '-' }}</td>
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
            <a class="btn btn-sm btn-outline-secondary mb-1" href="{{ route('billing.print', $invoice->id) }}" target="_blank" rel="noopener">Lihat / Cetak</a><br>
            <small class="text-muted">Status diatur oleh admin</small>
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
