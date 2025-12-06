@extends('layouts.main')

@section('title', 'Ubah Password')

@section('page_heading', 'Ubah Password')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif

    <form action="{{ route('settings.save') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="section" value="account">

      <div class="form-group">
        <label for="foto_profil">Foto Profil</label>
        <div class="d-flex align-items-center">
          <div class="mr-3">
            @php $photo = optional($member ?? null)->foto_profil; @endphp
            @if($photo)
              <img src="{{ asset('storage/foto_profil/' . $photo) }}" alt="Foto profil" class="rounded-circle border" style="width:64px; height:64px; object-fit:cover;">
            @else
              <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FC7753&color=fff&size=128" alt="{{ Auth::user()->name }}" class="rounded-circle border" style="width:64px; height:64px; object-fit:cover;">
            @endif
          </div>
          <div class="flex-grow-1">
            <input type="file"
                   class="form-control-file @error('foto_profil') is-invalid @enderror"
                   id="foto_profil"
                   name="foto_profil"
                   accept="image/*">
            <small class="form-text text-muted">Format JPG/PNG/WebP, maks 2MB.</small>
            @error('foto_profil')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <label for="current_password">Password Lama</label>
        <input type="password" 
               class="form-control @error('current_password') is-invalid @enderror" 
               id="current_password" 
               name="current_password">
        <small class="form-text text-muted">Isi jika ingin mengganti password.</small>
        @error('current_password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="new_password">Password Baru</label>
        <input type="password" 
               class="form-control @error('new_password') is-invalid @enderror" 
               id="new_password" 
               name="new_password">
        <small class="form-text text-muted">Minimal 8 karakter</small>
        @error('new_password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
        <input type="password" 
               class="form-control" 
               id="new_password_confirmation" 
               name="new_password_confirmation">
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-secondary">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('styles')
<style>
  .form-control:focus {
    border-color: var(--brand-primary);
    box-shadow: 0 0 0 0.2rem rgba(252, 119, 83, 0.25);
  }
  
  .btn-primary {
    background-color: var(--brand-primary);
    border-color: var(--brand-primary);
  }
  
  .btn-primary:hover {
    background-color: #e96643;
    border-color: #e96643;
  }
</style>
@endsection
