@extends('layouts.main')

@section('title', $mode === 'edit' ? 'Edit Instruktur' : 'Tambah Instruktur')
@section('page_heading', $mode === 'edit' ? 'Edit Instruktur' : 'Tambah Instruktur')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">{{ $mode === 'edit' ? 'Edit Instruktur' : 'Instruktur Baru' }}</h5>
          <form method="POST" action="{{ $mode === 'edit' ? route('trainers.update', $trainer->id) : route('trainers.store') }}">
            @csrf
            @if($mode === 'edit')
              @method('PUT')
            @endif

            <div class="form-group">
              <label for="name">Nama</label>
              <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name', $trainer->name ?? '') }}" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="phone">Nomor HP</label>
                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone', $trainer->phone ?? '') }}" placeholder="08xxx">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="form-group col-md-3">
                <label for="experience_years">Pengalaman (tahun)</label>
                <input type="number" min="0" max="60" id="experience_years" name="experience_years"
                       class="form-control @error('experience_years') is-invalid @enderror"
                       value="{{ old('experience_years', $trainer->experience_years ?? 0) }}">
                @error('experience_years') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="form-group col-md-3">
                <label for="hourly_rate">Tarif/Jam (Rp)</label>
                <input type="number" min="0" id="hourly_rate" name="hourly_rate"
                       class="form-control @error('hourly_rate') is-invalid @enderror"
                       value="{{ old('hourly_rate', $trainer->hourly_rate ?? '') }}">
                @error('hourly_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="form-group">
              <label for="status">Status</label>
              <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                <option value="active" {{ old('status', $trainer->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $trainer->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
              @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label for="bio">Bio / Catatan</label>
              <textarea id="bio" name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror"
                        placeholder="Highlight keahlian, sertifikasi, atau jadwal ketersediaan">{{ old('bio', $trainer->bio ?? '') }}</textarea>
              @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('trainers.index') }}" class="btn btn-outline-secondary">Kembali</a>
              <button type="submit" class="btn btn-primary">
                {{ $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Instruktur' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
