{{-- resources/views/member.blade.php --}}
@extends('layouts.main')

@section('title', 'Member Management')
@section('page_heading', 'Member Management')
@section('card_title', 'Daftar Member Gym')

@section('card_actions')
  <a href="/member/add-member" class="btn btn-orange btn-sm">
    <i class="bi bi-plus-circle-fill"></i> Tambah Member
  </a>
@endsection

@section('content')

  {{-- Flash success --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  {{-- Alert warning untuk error / pesan lain --}}
  @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Holy guacamole!</strong> {{ session('warning') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  {{-- Contoh: kalau mau pakai untuk error validasi --}}
  @if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Holy guacamole!</strong> Silakan cek kembali beberapa field di bawah:
      <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  <div class="table-responsive">
    <table id="membergym" class="table table-striped table-bordered w-100 mb-0" data-datatable>
      <thead class="thead-light">
        <tr>
          <th>No</th>
          <th>ID Member</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Nomor Telepon</th>
          <th>Tanggal Lahir</th>
          <th>Gender</th>
          <th>Tanggal Bergabung</th>
          <th>Membership Plan</th>
          <th>Durasi Plan</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Status Membership</th>
          <th>Notes</th>
          <th>Foto Profil</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($members as $m)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $m->id_member }}</td>
            <td>{{ $m->nama_member }}</td>
            <td>{{ $m->email_member }}</td>
            <td>{{ $m->nomor_telepon_member }}</td>
            <td>{{ $m->tanggal_lahir }}</td>
            <td>{{ $m->gender }}</td>
            <td>{{ $m->tanggal_join }}</td>
            <td>{{ $m->membership_plan }}</td>
            <td>{{ $m->durasi_plan }}</td>
            <td>{{ $m->start_date }}</td>
            <td>{{ $m->end_date }}</td>
            <td>{{ $m->status_membership }}</td>
            <td>{{ $m->notes }}</td>
            <td>
              @if(!empty($m->foto_profil))
                <img
                  src="{{ asset('storage/foto_profil/' . $m->foto_profil) }}"
                  alt="Foto Profil"
                  width="80" height="80"
                  style="object-fit:cover;border-radius:8px;">
              @else
                <img
                  src="{{ asset('storage/foto_profil/noimage.png') }}"
                  alt="No-Foto"
                  width="80" height="80"
                  style="object-fit:cover;border-radius:8px;">
              @endif
            </td>
            <td>
              <a href="/member/edit-member/{{ $m->id }}" class="btn btn-sm btn-success">
                <i class="bi bi-pencil-square"></i> Edit
              </a>
              <a href="/member/delete-member/{{ $m->id }}" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i> Delete
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td class="text-center text-muted" colspan="16">Belum ada data member.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
