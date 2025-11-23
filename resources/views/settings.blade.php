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
        <form>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Harga default Basic (per bulan)</label>
              <input type="number" class="form-control" placeholder="150000" value="150000">
              <div class="form-help">Digunakan saat membuat invoice jika harga plan kosong.</div>
            </div>
            <div class="form-group col-md-4">
              <label>Harga default Premium (per bulan)</label>
              <input type="number" class="form-control" placeholder="300000" value="300000">
            </div>
            <div class="form-group col-md-4">
              <label>Jatuh tempo standar (hari)</label>
              <input type="number" class="form-control" placeholder="30" value="30">
              <div class="form-help">Berapa hari setelah invoice dibuat.</div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Format nomor invoice</label>
              <input type="text" class="form-control" placeholder="INV-{YYYYMMDD}-{RAND5}" value="INV-{YYYYMMDD}-{RAND5}">
              <div class="form-help">Gunakan token: {YYYYMMDD}, {RAND5}</div>
            </div>
            <div class="form-group col-md-3">
              <label>Pajak default (Rp)</label>
              <input type="number" class="form-control" placeholder="0" value="0">
            </div>
            <div class="form-group col-md-3">
              <label>Diskon default (Rp)</label>
              <input type="number" class="form-control" placeholder="0" value="0">
            </div>
          </div>
          <div class="form-group">
            <label>Metode pembayaran aktif</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="payCash" checked>
              <label class="form-check-label" for="payCash">Cash</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="payTransfer" checked>
              <label class="form-check-label" for="payTransfer">Transfer</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="payEwallet" checked>
              <label class="form-check-label" for="payEwallet">E-Wallet</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="payCC" checked>
              <label class="form-check-label" for="payCC">Credit Card</label>
            </div>
          </div>
          <button type="button" class="btn btn-brand">Simpan (placeholder)</button>
        </form>
      </div>
    </div>

    <div class="tab-pane fade" id="branding" role="tabpanel" aria-labelledby="branding-tab">
      <div class="section-card mb-3">
        <h5 class="mb-3">Branding</h5>
        <form>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Nama Gym</label>
              <input type="text" class="form-control" placeholder="GymFlow" value="GymFlow">
            </div>
            <div class="form-group col-md-6">
              <label>Tagline</label>
              <input type="text" class="form-control" placeholder="Be stronger every day">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Logo</label>
              <input type="file" class="form-control-file">
              <div class="form-help">PNG/JPG, maks 1MB. (placeholder, belum upload)</div>
            </div>
            <div class="form-group col-md-6">
              <label>Warna utama (hex)</label>
              <input type="text" class="form-control" placeholder="#FC7753" value="#FC7753">
            </div>
          </div>
          <div class="form-group">
            <label>Alamat footer invoice</label>
            <textarea class="form-control" rows="2" placeholder="Alamat, kontak, website"></textarea>
          </div>
          <button type="button" class="btn btn-brand">Simpan (placeholder)</button>
        </form>
      </div>
    </div>
  </div>
@endsection
