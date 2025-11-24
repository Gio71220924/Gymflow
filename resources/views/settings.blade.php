@extends('layouts.main')
@section('title', 'Settings')
@section('page_heading', 'Settings')
@section('card_title', 'Konfigurasi Gym')

@section('styles')
<style>
  .nav-pills .nav-link { border-radius: 10px; }
  .section-card { border:1px solid #e5e5e5; border-radius: 12px; padding: 20px; background: #fff; }
  .form-help { color: #6c757d; font-size: 12px; }
</style>
@endsection

@section('content')
  <ul class="nav nav-pills mb-3" id="settingsTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="billing-tab" data-toggle="pill" href="#billing" role="tab" aria-controls="billing" aria-selected="true">Billing</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="branding-tab" data-toggle="pill" href="#branding" role="tab" aria-controls="branding" aria-selected="false">Branding</a>
    </li>
  </ul>

  <div class="tab-content" id="settingsTabContent">
    <div class="tab-pane fade show active" id="billing" role="tabpanel" aria-labelledby="billing-tab">
      <div class="section-card mb-3">
        <h5 class="mb-3">Pengaturan Billing</h5>
        <form method="POST" action="{{ route('settings.save') }}">
          @csrf
          <input type="hidden" name="section" value="billing">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Harga default Basic (per bulan)</label>
              <input type="number" name="billing_basic_price" class="form-control" placeholder="150000" value="{{ $settings['billing_basic_price'] ?? 150000 }}">
              <div class="form-help">Digunakan saat membuat invoice jika harga plan kosong.</div>
            </div>
            <div class="form-group col-md-4">
              <label>Harga default Premium (per bulan)</label>
              <input type="number" name="billing_premium_price" class="form-control" placeholder="300000" value="{{ $settings['billing_premium_price'] ?? 300000 }}">
            </div>
            <div class="form-group col-md-4">
              <label>Jatuh tempo standar (hari)</label>
              <input type="number" name="billing_due_days" class="form-control" placeholder="30" value="{{ $settings['billing_due_days'] ?? 30 }}">
              <div class="form-help">Berapa hari setelah invoice dibuat.</div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Format nomor invoice</label>
              <input type="text" name="billing_invoice_format" class="form-control" placeholder="INV-{YYYYMMDD}-{RAND5}" value="{{ $settings['billing_invoice_format'] ?? 'INV-{YYYYMMDD}-{RAND5}' }}">
              <div class="form-help">Token: {YYYYMMDD}, {RAND5}</div>
            </div>
            <div class="form-group col-md-3">
              <label>Pajak default (Rp)</label>
              <input type="number" name="billing_default_tax" class="form-control" placeholder="0" value="{{ $settings['billing_default_tax'] ?? 0 }}">
            </div>
            <div class="form-group col-md-3">
              <label>Diskon default (Rp)</label>
              <input type="number" name="billing_default_discount" class="form-control" placeholder="0" value="{{ $settings['billing_default_discount'] ?? 0 }}">
            </div>
          </div>
          <div class="form-group">
            <label>Metode pembayaran aktif</label>
            @php
              $methods = $settings['billing_methods'] ?? 'cash,transfer,ewallet,credit_card';
              $methodsArr = explode(',', $methods);
            @endphp
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="billing_methods[]" value="cash" id="payCash" {{ in_array('cash', $methodsArr) ? 'checked' : '' }}>
              <label class="form-check-label" for="payCash">Cash</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="billing_methods[]" value="transfer" id="payTransfer" {{ in_array('transfer', $methodsArr) ? 'checked' : '' }}>
              <label class="form-check-label" for="payTransfer">Transfer</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="billing_methods[]" value="ewallet" id="payEwallet" {{ in_array('ewallet', $methodsArr) ? 'checked' : '' }}>
              <label class="form-check-label" for="payEwallet">E-Wallet</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="billing_methods[]" value="credit_card" id="payCC" {{ in_array('credit_card', $methodsArr) ? 'checked' : '' }}>
              <label class="form-check-label" for="payCC">Credit Card</label>
            </div>
          </div>
          <button type="submit" class="btn btn-brand">Simpan</button>
        </form>
      </div>
    </div>

    <div class="tab-pane fade" id="branding" role="tabpanel" aria-labelledby="branding-tab">
      <div class="section-card mb-3">
        <h5 class="mb-3">Branding</h5>
        <form method="POST" action="{{ route('settings.save') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="section" value="branding">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Nama Gym</label>
              <input type="text" name="branding_name" class="form-control" placeholder="GymFlow" value="{{ $settings['branding_name'] ?? 'GymFlow' }}">
            </div>
            <div class="form-group col-md-6">
              <label>Tagline</label>
              <input type="text" name="branding_tagline" class="form-control" placeholder="Be stronger every day" value="{{ $settings['branding_tagline'] ?? '' }}">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Logo</label>
              <input type="file" name="branding_logo" class="form-control-file">
              <div class="form-help">PNG/JPG, maks 1MB.</div>
              @if(!empty($settings['branding_logo']))
                <div class="mt-2">
                  <img src="{{ asset('storage/branding/'.$settings['branding_logo']) }}" alt="Logo" height="60">
                </div>
              @endif
            </div>
            <div class="form-group col-md-6">
              <label>Warna utama (hex)</label>
              <input type="text" name="branding_color" class="form-control" placeholder="#FC7753" value="{{ $settings['branding_color'] ?? '#FC7753' }}">
            </div>
          </div>
          <div class="form-group">
            <label>Alamat footer invoice</label>
            <textarea name="branding_address" class="form-control" rows="2" placeholder="Alamat, kontak, website">{{ $settings['branding_address'] ?? '' }}</textarea>
          </div>
          <button type="submit" class="btn btn-brand">Simpan</button>
        </form>
      </div>
    </div>
  </div>
@endsection
