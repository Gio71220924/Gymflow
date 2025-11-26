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

    <form action="{{ route('update-password') }}" method="POST">
      @csrf
      
      <div class="form-group">
        <label for="current_password">Password Lama <span class="text-danger">*</span></label>
        <input type="password" 
               class="form-control @error('current_password') is-invalid @enderror" 
               id="current_password" 
               name="current_password" 
               required>
        @error('current_password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="new_password">Password Baru <span class="text-danger">*</span></label>
        <input type="password" 
               class="form-control @error('new_password') is-invalid @enderror" 
               id="new_password" 
               name="new_password" 
               required>
        <small class="form-text text-muted">Minimal 8 karakter</small>
        @error('new_password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="new_password_confirmation">Konfirmasi Password Baru <span class="text-danger">*</span></label>
        <input type="password" 
               class="form-control" 
               id="new_password_confirmation" 
               name="new_password_confirmation" 
               required>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-secondary">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-lg me-1"></i> Ubah Password
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