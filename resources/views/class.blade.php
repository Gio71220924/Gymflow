@extends('layouts.main')
@section('title', 'Class Management')
@section('page_heading', 'Class Management')
@section('card_title', 'Class Schedule')

@section('content')
  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0">Jadwal Kelas</h5>
        @if($isAdmin ?? false)
          <a href="{{ route('class.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Kelas
          </a>
        @endif
      </div>

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

      {{-- Search bar method get --}}
      <form action="{{ route('class') }}" method="GET" class="mb-3">
        <div class="form-row align-items-center">
          <div class="col-12 col-md-6 mb-2 mb-md-0">
            <input type="text" class="form-control" name="q" id="q"
                  placeholder="Cari: judul kelas/Nama instruktur/lokasi/Status"
                  value="{{ $searchQuery ?? '' }}">
          </div>

          <div class="col-auto mb-2 mb-md-0">
            <select name="per_page" class="form-control" onchange="this.form.submit()">
              @foreach([10, 20, 50] as $size)
                <option value="{{ $size }}" {{ (int)($perPage ?? 10) === $size ? 'selected' : '' }}>
                  {{ $size }} / halaman
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('class') }}" class="btn btn-light">Reset</a>
          </div>
        </div>

        @if(!empty($searchQuery))
          <small class="text-muted d-block mt-2">
            Menampilkan hasil untuk keyword: "{{ $searchQuery }}"
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
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($classes ?? []) as $class)
              @php
                $start = \Carbon\Carbon::parse($class->start_at)->timezone('Asia/Jakarta');
                $end   = \Carbon\Carbon::parse($class->end_at)->timezone('Asia/Jakarta');
                $startObj = $start->copy();
                $bookedCount = (int) ($class->booked_count ?? 0);
                $isFull = $bookedCount >= ($class->capacity ?? 0);
                $userBookingStatus = $class->user_booking_status ?? null;
                $classParticipants = ($participants ?? collect())->get($class->id, collect());
                $normalizedStatus = strtolower(trim((string) $class->status));
                if ($startObj->isFuture() && $normalizedStatus === 'done') {
                  $normalizedStatus = 'scheduled'; // data bisa out-of-sync, anggap masih berjalan
                }
                $isPast = $startObj->isPast();
                $canJoin = !in_array($normalizedStatus, ['cancelled', 'done'], true) && !$isFull && !$isPast;
                $statusClass = [
                  'scheduled' => 'primary',
                  'done'      => 'success',
                  'cancelled' => 'danger',
                ][$normalizedStatus] ?? 'secondary';
                $statusLabel = ucfirst($normalizedStatus);
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
                <td>
                  <div class="font-weight-bold">{{ $bookedCount }} / {{ $class->capacity }}</div>
                  <div class="text-muted small">Tersisa {{ max(($class->capacity ?? 0) - $bookedCount, 0) }} slot</div>
                </td>
                <td>
                  <span class="badge badge-{{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td>
                  @if($isAdmin ?? false)
                    <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                      <a class="btn btn-sm btn-outline-primary" href="{{ route('class.edit', $class->id) }}">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <form action="{{ route('class.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                    <div class="border rounded p-2 mt-2">
                      <div class="small font-weight-bold mb-1">Peserta ({{ $classParticipants->count() }})</div>
                      @forelse($classParticipants as $participant)
                        <div class="d-flex align-items-center justify-content-between small py-1 border-top">
                          <span>
                            {{ $participant->nama_member }}
                            <span class="badge badge-light text-muted">{{ ucfirst(str_replace('_', ' ', $participant->status)) }}</span>
                          </span>
                          <form action="{{ route('class.kick', [$class->id, $participant->booking_id]) }}" method="POST" onsubmit="return confirm('Kick peserta ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Kick</button>
                          </form>
                        </div>
                      @empty
                        <div class="text-muted small">Belum ada peserta.</div>
                      @endforelse
                    </div>
                  @else
                    @if($userBookingStatus && $userBookingStatus !== 'cancelled')
                      <div class="d-flex align-items-center" style="gap:10px;">
                        <span class="badge badge-success">Sudah bergabung</span>
                        <form action="{{ route('class.cancel', $class->id) }}" method="POST">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan</button>
                        </form>
                      </div>
                    @else
                      <form action="{{ route('class.join', $class->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary" {{ $canJoin ? '' : 'disabled' }}>
                          @if(!$canJoin)
                            {{ $isFull ? 'Kelas Penuh' : 'Tidak Tersedia' }}
                          @else
                            Gabung Kelas
                          @endif
                        </button>
                        @if($isFull)
                          <div class="text-muted small mt-1">Slot penuh, pilih jadwal lain.</div>
                        @elseif($isPast)
                          <div class="text-muted small mt-1">Kelas sudah lewat, tidak dapat dibooking.</div>
                        @elseif(in_array($normalizedStatus, ['cancelled','done'], true))
                          <div class="text-muted small mt-1">Kelas tidak dapat dibooking.</div>
                        @endif
                      </form>
                    @endif
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted">Belum ada jadwal kelas.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if(isset($classes) && method_exists($classes, 'links'))
        <div class="mt-3">
          {{ $classes->appends([
            'q'        => $searchQuery ?? '',
            'per_page' => $perPage ?? 10,
          ])->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
