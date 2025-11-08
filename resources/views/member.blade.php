@extends('layouts.main')
@section('title', 'Member Management')
@section('page_heading', 'Member Management')
@section('card_title', 'Daftar Member Gym')

@section('card_actions')
  <a href="/member/add-member" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-circle-fill"></i> Tambah Member
  </a>
@endsection

@section('content')
  <table id="membergym" class="table table-striped table-bordered w-100" data-datatable>
    <thead class="thead-light">
      <tr>
        <th>ID</th>
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
      </tr>
    </thead>
    <tbody>
      @forelse($members as $m)
        <tr>
          <td>{{ $m->id }}</td>
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
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center text-muted">Belum ada data member.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
@endsection
