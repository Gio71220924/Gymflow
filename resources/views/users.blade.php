@extends('layouts.main')

@section('title', 'User Management')
@section('page_heading', 'User Management')
@section('card_title', 'Daftar Users')

@section('styles')
<style>
  .btn-orange{
    background-color:#FC7753;
    border-color:#FC7753;
    color:#fff;
  }
  .btn-orange:hover{
    background-color:#e96a49;
    border-color:#e05f3f;
    color:#fff;
  }
  .btn-orange:focus, .btn-orange:active{
    background-color:#e05f3f !important;
    border-color:#d85738 !important;
    color:#fff !important;
    box-shadow:0 0 0 .2rem rgba(252,119,83,.35);
  }
</style>
@endsection

@section('card_actions')
  <a href="{{ route('users.create') }}" class="btn btn-orange btn-sm">
    <i class="bi bi-plus-circle-fill"></i> Tambah User
  </a>
@endsection

@section('content')

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      Periksa kembali beberapa input:
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

  <table class="table table-striped table-bordered w-100 mb-0" data-datatable>
    <thead class="thead-light">
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Dibuat</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->role }}</td>
          <td>{{ $user->status }}</td>
          <td>{{ $user->created_at }}</td>
          <td>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?');" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i> Delete
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center text-muted">Belum ada data user.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
@endsection
