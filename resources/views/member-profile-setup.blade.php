{{-- resources/views/member-profile-setup.blade.php --}}
@extends('layouts.main')

@section('title', 'Lengkapi Profil Member')
@section('page_heading', 'Lengkapi Profil Member')
@section('card_title', 'Data member diperlukan sebelum melanjutkan')

@section('content')
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

  <div class="card">
    <div class="card-body">
      <p class="text-muted mb-3">Setelah verifikasi email, lengkapi data berikut agar akun kamu aktif sebagai member.</p>
      <form method="POST" action="{{ route('member.profile.save') }}">
        @csrf
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Nama lengkap</label>
            <input type="text" name="nama_member" class="form-control" value="{{ old('nama_member', $user->name) }}" required>
          </div>
          <div class="form-group col-md-6">
            <label>Email</label>
            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Nomor telepon</label>
            <input type="text" name="nomor_telepon_member" class="form-control" value="{{ old('nomor_telepon_member') }}" required>
          </div>
          <div class="form-group col-md-3">
            <label>Tanggal lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
          </div>
          <div class="form-group col-md-3">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
              <option value="">Pilih</option>
              <option value="Laki-laki" {{ old('gender') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
              <option value="Perempuan" {{ old('gender') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label>Tanggal join</label>
            <input type="date" name="tanggal_join" class="form-control" value="{{ old('tanggal_join', $today) }}" required>
            <small class="text-muted">ID member dibentuk otomatis: PLAN-YYYYMMXX.</small>
          </div>
          <div class="form-group col-md-4">
            <label>Tanggal mulai</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $today) }}" required>
          </div>
          <div class="form-group col-md-4">
            <label>Durasi (bulan)</label>
            <input type="number" name="durasi_plan" class="form-control" value="{{ old('durasi_plan', 1) }}" min="1" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label>Paket membership</label>
            <select name="membership_plan" class="form-control" required>
              <option value="basic" {{ old('membership_plan') === 'basic' ? 'selected' : '' }}>Basic</option>
              <option value="premium" {{ old('membership_plan') === 'premium' ? 'selected' : '' }}>Premium</option>
            </select>
          </div>
          <div class="form-group col-md-8">
            <label>Catatan (opsional)</label>
            <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Misal preferensi waktu latihan, kondisi khusus, dsb">
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan & lanjutkan</button>
      </form>
    </div>
  </div>
@endsection
