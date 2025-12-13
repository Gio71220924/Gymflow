@extends('layouts.main')

@section('title', 'Instruktur')
@section('page_heading', 'Instruktur')
@section('card_title', 'Daftar instruktur')

@section('content')
  @php use Illuminate\Support\Str; @endphp
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle mr-2"></i>{{ session('success') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Instruktur terdaftar</h5>
    <a href="{{ route('trainers.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg"></i> Tambah instruktur
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-sm table-hover">
      <thead class="thead-light">
        <tr>
          <th>Nama</th>
          <th>Kontak</th>
          <th>Pengalaman</th>
          <th>Tarif/Jam</th>
          <th>Status</th>
          <th style="width:160px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($trainers as $trainer)
          <tr>
            <td class="align-middle">
              <div class="font-weight-bold">{{ $trainer->name }}</div>
              <small class="text-muted">{{ Str::limit($trainer->bio, 60) }}</small>
            </td>
            <td class="align-middle">
              <div>{{ $trainer->phone ?? '-' }}</div>
            </td>
            <td class="align-middle">{{ $trainer->experience_years ?? 0 }} th</td>
            <td class="align-middle">Rp {{ $trainer->hourly_rate ? number_format($trainer->hourly_rate, 0, ',', '.') : '-' }}</td>
            <td class="align-middle">
              <span class="badge badge-soft {{ $trainer->status === 'active' ? 'success' : 'secondary' }}">
                {{ $trainer->status }}
              </span>
            </td>
            <td class="align-middle">
              <a href="{{ route('trainers.edit', $trainer->id) }}" class="btn btn-sm btn-outline-primary mr-1">Edit</a>
              <form action="{{ route('trainers.destroy', $trainer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus instruktur ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted">Belum ada instruktur.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
