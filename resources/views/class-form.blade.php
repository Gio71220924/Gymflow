@extends('layouts.main')
@section('title', ($mode ?? 'create') === 'edit' ? 'Edit Kelas' : 'Tambah Kelas')
@section('page_heading', ($mode ?? 'create') === 'edit' ? 'Edit Kelas' : 'Tambah Kelas')
@section('card_title', 'Form Kelas')

@section('content')
  <div class="row justify-content-center">
    <div class="col-lg-8">
      @if($errors->any())
        <div class="alert alert-danger">
          <div class="font-weight-bold mb-1">Periksa kembali input Anda:</div>
          <ul class="mb-0 pl-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @php
        $class = $classData ?? null;
        $startValue = old('start_at', $class ? \Carbon\Carbon::parse($class->start_at)->format('Y-m-d\\TH:i') : '');
        $endValue = old('end_at', $class ? \Carbon\Carbon::parse($class->end_at)->format('Y-m-d\\TH:i') : '');
        $selected = old('trainer_ids', $selectedTrainerIds ?? []);
      @endphp

      <form method="POST" action="{{ ($mode ?? 'create') === 'edit' ? route('class.update', $class->id) : route('class.store') }}">
        @csrf
        @if(($mode ?? 'create') === 'edit')
          @method('PUT')
        @endif

        <div class="form-group">
          <label for="title">Nama Kelas <span class="text-danger">*</span></label>
          <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $class->title ?? '') }}" required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label for="description">Deskripsi</label>
          <textarea name="description" id="description" rows=3 class="form-control @error('description') is-invalid @enderror">{{ old('description', $class->description ?? '') }}</textarea>
          @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="level">Level</label>
            <input type="text" name="level" id="level" class="form-control @error('level') is-invalid @enderror" value="{{ old('level', $class->level ?? '') }}">
            @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="capacity">Kapasitas <span class="text-danger">*</span></label>
            <input type="number" name="capacity" id="capacity" min="0" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', $class->capacity ?? 0) }}" required>
            @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="location">Lokasi</label>
            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $class->location ?? '') }}">
            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="start_at">Mulai <span class="text-danger">*</span></label>
            <input type="datetime-local" name="start_at" id="start_at" class="form-control @error('start_at') is-invalid @enderror" value="{{ $startValue }}" required>
            @error('start_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="end_at">Selesai <span class="text-danger">*</span></label>
            <input type="datetime-local" name="end_at" id="end_at" class="form-control @error('end_at') is-invalid @enderror" value="{{ $endValue }}" required>
            @error('end_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="type">Tipe</label>
            <input type="text" name="type" id="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type', $class->type ?? '') }}">
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="status">Status <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
              @foreach(['Scheduled', 'Cancelled', 'Done'] as $status)
                <option value="{{ $status }}" {{ old('status', $class->status ?? 'Scheduled') === $status ? 'selected' : '' }}>{{ $status }}</option>
              @endforeach
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="trainer_ids">Instruktur (opsional)</label>
            <select name="trainer_ids[]" id="trainer_ids" class="form-control @error('trainer_ids') is-invalid @enderror" multiple>
              @foreach($trainers as $trainer)
                <option value="{{ $trainer->id }}" {{ in_array($trainer->id, $selected) ? 'selected' : '' }}>{{ $trainer->name }}</option>
              @endforeach
            </select>
            @error('trainer_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
          <a href="{{ route('class') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> {{ ($mode ?? 'create') === 'edit' ? 'Update Kelas' : 'Simpan Kelas' }}
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
