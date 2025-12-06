@extends('layouts.main')
@section('title', 'Class Management')
@section('page_heading', 'Class Management')
@section('card_title', 'Class Schedule')

@section('content')
  <div class="card">
    <div class="card-body">
      <h5 class="card-title mb-3">Jadwal Kelas</h5>

      <form action="/class/searching" method="GET" class="mb-3">
        <div class="form-row align-items-center">
          <div class="col-12 col-md-6 mb-2 mb-md-0">
            <input type="text" class="form-control" name="q" id="q"
                   placeholder="Cari: judul kelas/Nama instruktur/lokasi/Status">
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="/class" class="btn btn-light">Reset</a>
          </div>
        </div>
        @if(request('q'))
          <small class="text-muted d-block mt-2">
            Menampilkan hasil untuk keyword: “{{ request('q') }}”
          </small>
        @endif
      </form>

      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover w-100 mb-0">
          <thead class="thead-light">
            <tr>
              <th>Kelas</th>
              <th>Instruktur</th>
              <th>Tanggal</th>
              <th>Waktu</th>
              <th>Kapasitas</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($classes ?? []) as $class)
              @php
                $start = \Carbon\Carbon::parse($class->start_at);
                $end   = \Carbon\Carbon::parse($class->end_at);
                $statusClass = [
                  'Scheduled' => 'primary',
                  'Done'      => 'success',
                  'Cancelled' => 'danger',
                ][$class->status] ?? 'secondary';
              @endphp
              <tr>
                <td>
                  <div class="font-weight-bold">{{ $class->title }}</div>
                  @if(!empty($class->location))
                    <div class="text-muted small">{{ $class->location }}</div>
                  @endif
                </td>
                <td>{{ $class->trainer_names ?? 'Belum ada instruktur' }}</td>
                <td>{{ $start->format('Y-m-d') }}</td>
                <td>{{ $start->format('H:i') }} - {{ $end->format('H:i') }}</td>
                <td>{{ $class->capacity }}</td>
                <td>
                  <span class="badge badge-{{ $statusClass }}">{{ $class->status }}</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada jadwal kelas.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Tampilkan pagination kalau $classes adalah paginator --}}
      @if(isset($classes) && method_exists($classes, 'links'))
        <div class="mt-3">
          {{-- Pertahankan keyword saat paging --}}
          {{ $classes->appends(['q' => request('q')])->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
