@extends('layouts.main')
@section('title', 'Add Member')
@section('page_heading', 'Add Member')
@section('card_title', 'Form Tambah Member Gym')

{{-- tombol kanan di header card --}}
@section('card_actions')
  <a href="{{ url('/member') }}" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
@endsection

@section('content')
  {{-- PENTING: tambah enctype supaya upload file jalan --}}
  <form action="{{ url('/member/add-member/save') }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf

    <div class="form-row">
      {{-- user_id --}}
      <div class="form-group col-md-6">
        <label for="user_id">User (akun) <span class="text-danger">*</span></label>
        <select id="user_id" name="user_id"
                class="form-control @error('user_id') is-invalid @enderror" required>
          <option value="" disabled {{ old('user_id') ? '' : 'selected' }}>Pilih user...</option>
          @foreach($availableUsers as $user)
            <option value="{{ $user->id }}"
                    data-email="{{ $user->email }}"
                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
              {{ $user->name }} ({{ $user->email }})
            </option>
          @endforeach
        </select>
        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <small class="form-text text-muted">Pilih akun user yang sudah dibuat.</small>
      </div>

      {{-- id_member --}}
      <div class="form-group col-md-6">
        <label for="id_member">ID Member <span class="text-danger">*</span></label>
        <input type="text" maxlength="10"
               class="form-control @error('id_member') is-invalid @enderror"
               id="id_member" name="id_member" value="{{ old('id_member') }}"
               placeholder="GM-001" required>
        @error('id_member') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- nama_member --}}
      <div class="form-group col-md-8">
        <label for="nama_member">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text"
               class="form-control @error('nama_member') is-invalid @enderror"
               id="nama_member" name="nama_member" value="{{ old('nama_member') }}"
               placeholder="Nama lengkap" required>
        @error('nama_member') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="form-row">
      {{-- email_member --}}
      <div class="form-group col-md-6">
        <label for="email_member">Email <span class="text-danger">*</span></label>
        <input type="email"
               class="form-control @error('email_member') is-invalid @enderror"
               id="email_member" name="email_member" value="{{ old('email_member') }}"
               placeholder="nama@email.com" required>
        @error('email_member') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- nomor_telepon_member --}}
      <div class="form-group col-md-6">
        <label for="nomor_telepon_member">Nomor Telepon <span class="text-danger">*</span></label>
        <input type="text"
               class="form-control @error('nomor_telepon_member') is-invalid @enderror"
               id="nomor_telepon_member" name="nomor_telepon_member" value="{{ old('nomor_telepon_member') }}"
               placeholder="08xxxxxxxxxx" required>
        @error('nomor_telepon_member') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="form-row">
      {{-- tanggal_lahir --}}
      <div class="form-group col-md-4">
        <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
        <input type="date"
               class="form-control @error('tanggal_lahir') is-invalid @enderror"
               id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- gender --}}
      <div class="form-group col-md-4">
        <label for="gender">Gender <span class="text-danger">*</span></label>
        <select id="gender" name="gender"
                class="form-control @error('gender') is-invalid @enderror" required>
          <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Pilih...</option>
          <option value="Laki-laki"  {{ old('gender')==='Laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
          <option value="Perempuan"  {{ old('gender')==='Perempuan'  ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- tanggal_join --}}
      <div class="form-group col-md-4">
        <label for="tanggal_join">Tanggal Join <span class="text-danger">*</span></label>
        <input type="date"
               class="form-control @error('tanggal_join') is-invalid @enderror"
               id="tanggal_join" name="tanggal_join" value="{{ old('tanggal_join') }}" required>
        @error('tanggal_join') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="form-row">
      {{-- membership_plan --}}
      <div class="form-group col-md-4">
        <label for="membership_plan">Membership Plan <span class="text-danger">*</span></label>
        <select id="membership_plan" name="membership_plan"
                class="form-control @error('membership_plan') is-invalid @enderror" required>
          <option value="" disabled {{ old('membership_plan') ? '' : 'selected' }}>Pilih...</option>
          <option value="basic"   {{ old('membership_plan')==='basic'   ? 'selected' : '' }}>basic</option>
          <option value="premium" {{ old('membership_plan')==='premium' ? 'selected' : '' }}>premium</option>
        </select>
        @error('membership_plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- durasi_plan (bulan) --}}
      <div class="form-group col-md-4">
        <label for="durasi_plan">Durasi Plan (bulan) <span class="text-danger">*</span></label>
        <input type="number" min="1" step="1"
               class="form-control @error('durasi_plan') is-invalid @enderror"
               id="durasi_plan" name="durasi_plan" value="{{ old('durasi_plan', 1) }}" required>
        @error('durasi_plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- start_date --}}
      <div class="form-group col-md-4">
        <label for="start_date">Start Date <span class="text-danger">*</span></label>
        <input type="date"
               class="form-control @error('start_date') is-invalid @enderror"
               id="start_date" name="start_date" value="{{ old('start_date') }}" required>
        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="form-row">
      {{-- end_date --}}
      <div class="form-group col-md-4">
        <label for="end_date">End Date <span class="text-danger">*</span></label>
        <input type="date"
               class="form-control @error('end_date') is-invalid @enderror"
               id="end_date" name="end_date" value="{{ old('end_date') }}" required>
        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- status_membership --}}
      <div class="form-group col-md-4">
        <label for="status_membership">Status <span class="text-danger">*</span></label>
        <select id="status_membership" name="status_membership"
                class="form-control @error('status_membership') is-invalid @enderror" required>
          <option value="" disabled {{ old('status_membership') ? '' : 'selected' }}>Pilih...</option>
          <option value="Aktif"        {{ old('status_membership')==='Aktif'        ? 'selected' : '' }}>Aktif</option>
          <option value="Tidak Aktif"  {{ old('status_membership')==='Tidak Aktif'  ? 'selected' : '' }}>Tidak Aktif</option>
          <option value="Suspended"    {{ old('status_membership')==='Suspended'    ? 'selected' : '' }}>Suspended</option>
        </select>
        @error('status_membership') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- notes --}}
    <div class="form-group">
      <label for="notes">Catatan</label>
      <textarea id="notes" name="notes" rows="4"
                class="form-control @error('notes') is-invalid @enderror"
                placeholder="Opsional...">{{ old('notes') }}</textarea>
      @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- foto_profil (opsional) --}}
    <div class="form-group">
      <label for="foto_profil">Foto Profil <small class="text-muted">(opsional)</small></label>
      <div class="custom-file">
        <input type="file"
               class="custom-file-input @error('foto_profil') is-invalid @enderror"
               id="foto_profil" name="foto_profil" accept="image/*">
        <label class="custom-file-label" for="foto_profil">Pilih gambar...</label>
        @error('foto_profil') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
      <small class="form-text text-muted">Format: JPG/PNG/WEBP, maks 2 MB.</small>

      <div class="mt-2">
        <img id="preview-foto" src="" alt="Preview foto" class="img-thumbnail d-none" style="max-height:160px;">
      </div>
    </div>

    <div class="d-flex justify-content-between">
      <a href="{{ url('/member') }}" class="btn btn-outline-secondary">
        Batal
      </a>
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Simpan Member
      </button>
    </div>
  </form>
@endsection

{{-- Auto-hitungan end_date + preview foto --}}
@section('scripts')
<script>
  (function(){
    // Prefill email member dari pilihan user
    const userSelect  = document.getElementById('user_id');
    const emailInput  = document.getElementById('email_member');
    function syncEmail(){
      if (!userSelect || !emailInput) return;
      const opt = userSelect.selectedOptions[0];
      if (opt && opt.dataset.email) {
        emailInput.value = opt.dataset.email;
      }
    }
    userSelect && userSelect.addEventListener('change', syncEmail);
    syncEmail(); // prefilling jika old() ada

    const durasi = document.getElementById('durasi_plan');
    const start  = document.getElementById('start_date');
    const end    = document.getElementById('end_date');

    function recalc(){
      const s = start.value;
      const d = parseInt(durasi.value, 10);
      if(!s || !d || d < 1) return;
      const dt = new Date(s + 'T00:00:00');
      dt.setMonth(dt.getMonth() + d);
      const yyyy = dt.getFullYear();
      const mm   = String(dt.getMonth()+1).padStart(2,'0');
      const dd   = String(dt.getDate()).padStart(2,'0');
      end.value  = `${yyyy}-${mm}-${dd}`;
    }
    start && start.addEventListener('change', recalc);
    durasi && durasi.addEventListener('input', recalc);

    // Preview foto + ubah label custom-file
    const fileInput = document.getElementById('foto_profil');
    const label     = document.querySelector('label.custom-file-label[for="foto_profil"]') || document.querySelector('#foto_profil ~ .custom-file-label');
    const preview   = document.getElementById('preview-foto');

    if (fileInput) {
      fileInput.addEventListener('change', function(){
        const file = this.files && this.files[0];
        if (label) label.textContent = file ? file.name : 'Pilih gambar...';

        if (file) {
          const reader = new FileReader();
          reader.onload = (e) => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
          };
          reader.readAsDataURL(file);
        } else {
          preview.src = '';
          preview.classList.add('d-none');
        }
      });
    }
  })();
</script>
@endsection
