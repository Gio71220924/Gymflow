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
          <div class="col-12 col-md-6 col-lg-5 mb-2 mb-md-0">
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

          <div class="col-auto mb-2 mb-md-0">
            <select name="sort" class="form-control" onchange="this.form.submit()">
              <option value="terbaru" {{ ($sort ?? 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru dulu</option>
              <option value="terlama" {{ ($sort ?? 'terbaru') === 'terlama' ? 'selected' : '' }}>Terlama dulu</option>
            </select>
          </div>

          <div class="col-auto d-flex align-items-center flex-wrap" style="gap:8px; white-space: nowrap;">
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
            <a href="{{ route('class') }}" class="btn btn-light btn-sm">Reset</a>
            @if($isUser ?? false)
              <button class="btn btn-outline-primary btn-sm" type="button" data-toggle="collapse" data-target="#oneononeForm" aria-expanded="false" aria-controls="oneononeForm">
                Ajukan sesi one-on-one
              </button>
            @endif
          </div>
        </div>

        @if(!empty($searchQuery))
          <small class="text-muted d-block mt-2">
            Menampilkan hasil untuk keyword: "{{ $searchQuery }}"
          </small>
        @endif
      </form>


      @if($isUser ?? false)
        <div class="collapse mb-3" id="oneononeForm">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap:8px;">
              <h6 class="mb-0">Form ajukan sesi one-on-one</h6>
              <button class="btn btn-sm btn-light" type="button" data-toggle="collapse" data-target="#oneononeForm">Tutup</button>
            </div>
              <form action="{{ route('oneonone.store') }}" method="POST">
                @csrf
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Tanggal</label>
                    <input type="date" name="preferred_date" class="form-control @error('preferred_date') is-invalid @enderror" value="{{ old('preferred_date', now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required>
                    @error('preferred_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                  <div class="form-group col-md-4">
                    <label>Waktu</label>
                    <input type="time" name="preferred_time" class="form-control @error('preferred_time') is-invalid @enderror" value="{{ old('preferred_time', '09:00') }}" min="06:00" max="22:00" required>
                    @error('preferred_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                  <div class="form-group col-md-4">
                    <label>Instruktur</label>
                    <select name="trainer_id" class="form-control @error('trainer_id') is-invalid @enderror" required>
                      <option value="">Pilih instruktur</option>
                      @forelse($trainers as $trainer)
                        <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>{{ $trainer->name }}</option>
                      @empty
                        <option value="" disabled>Belum ada instruktur aktif</option>
                      @endforelse
                    </select>
                    @error('trainer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Tempat</label>
                    <select name="location" class="form-control @error('location') is-invalid @enderror" required>
                      <option value="">Pilih lokasi</option>
                      @php
                        $locationOptions = [
                          'Studio A',
                          'Studio B',
                          'Ruang privat',
                          'Outdoor',
                        ];
                        $selectedLoc = old('location');
                      @endphp
                      @foreach($locationOptions as $loc)
                        <option value="{{ $loc }}" {{ $selectedLoc === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                      @endforeach
                    </select>
                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                  <div class="form-group col-md-6">
                    <label>Catatan (opsional)</label>
                    <textarea name="note" rows="2" class="form-control @error('note') is-invalid @enderror" placeholder="Tujuan latihan, fokus area, dll.">{{ old('note') }}</textarea>
                    @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">Ajukan sesi</button>
              </form>

              <div class="mt-3">
                <h6 class="mb-2">Status pengajuan</h6>
                <div class="table-responsive">
                  <table class="table table-sm mb-0">
                    <thead class="thead-light">
                      <tr>
                        <th>Tanggal</th>
                        <th>Instruktur</th>
                        <th>Status</th>
                        <th>Catatan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($oneOnOneRequests as $req)
                        @php
                          $badgeClass = $req->status === 'approved' ? 'success' : ($req->status === 'rejected' ? 'danger' : 'warning');
                        @endphp
                        <tr>
                          <td>{{ \Carbon\Carbon::parse($req->preferred_date)->format('d M Y') }}<br><small class="text-muted">{{ $req->preferred_time }}</small></td>
                          <td>{{ $req->trainer_name ?? '-' }}</td>
                          <td><span class="badge badge-soft {{ $badgeClass }}">{{ ucfirst($req->status) }}</span></td>
                          <td>
                            <div class="text-truncate" style="max-width:180px;">{{ $req->note ?: '-' }}</div>
                            @if(!empty($req->admin_note))
                              <div class="text-muted small">Admin: {{ $req->admin_note }}</div>
                            @endif
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="4" class="text-muted text-center">Belum ada pengajuan.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
          </div>
        </div>
        </div>
      @endif

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

      @if(($isAdmin ?? false) && isset($oneOnOneRequests))
        <hr class="my-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h5 class="mb-0">Pengajuan sesi one-on-one</h5>
          <span class="badge badge-light">Pending: {{ $oneOnOneRequests->where('status', 'pending')->count() }}</span>
        </div>
        <div class="table-responsive">
          <table class="table table-sm table-striped">
            <thead class="thead-light">
              <tr>
                <th>Member</th>
                <th>Instruktur</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Catatan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($oneOnOneRequests as $req)
                @php
                  $badgeClass = $req->status === 'approved' ? 'success' : ($req->status === 'rejected' ? 'danger' : 'warning');
                @endphp
                <tr>
                  <td>
                    <div class="font-weight-bold">{{ $req->nama_member ?? '-' }}</div>
                    <div class="text-muted small">{{ $req->email_member ?? '' }}</div>
                  </td>
                  <td>{{ $req->trainer_name ?? '-' }}</td>
                  <td>
                    {{ \Carbon\Carbon::parse($req->preferred_date)->format('d M Y') }}
                    <div class="text-muted small">{{ $req->preferred_time }}</div>
                  </td>
                  <td>{{ $req->location }}</td>
                  <td>
                    <div class="text-truncate" style="max-width:200px;">{{ $req->note ?: '-' }}</div>
                    @if($req->admin_note)
                      <div class="text-muted small">Admin: {{ $req->admin_note }}</div>
                    @endif
                  </td>
                  <td><span class="badge badge-soft {{ $badgeClass }}">{{ ucfirst($req->status) }}</span></td>
                  <td>
                    @if($req->status === 'pending')
                      <form action="{{ route('oneonone.approve', $req->id) }}" method="POST" class="mb-1">
                        @csrf
                        <div class="input-group input-group-sm">
                          <input type="text" name="admin_note" class="form-control" placeholder="Catatan (opsional)">
                          <div class="input-group-append">
                            <button class="btn btn-success" type="submit">ACC</button>
                          </div>
                        </div>
                      </form>
                      <form action="{{ route('oneonone.reject', $req->id) }}" method="POST">
                        @csrf
                        <div class="input-group input-group-sm">
                          <input type="text" name="admin_note" class="form-control" placeholder="Catatan tolak (opsional)">
                          <div class="input-group-append">
                            <button class="btn btn-outline-danger" type="submit">Tolak</button>
                          </div>
                        </div>
                      </form>
                    @else
                      <div class="text-muted small">Diproses oleh {{ $req->admin_name ?? '-' }}</div>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted">Belum ada pengajuan one-on-one.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('js/oneonone.js') }}"></script>
@endsection
