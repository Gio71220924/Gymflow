@extends('layouts.main')

@section('title', 'Tambah User')
@section('page_heading', 'User Management')
@section('card_title', 'Tambah User')

@section('content')
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

  <form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="name">Nama</label>
      <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
    </div>
    <div class="form-group">
      <label for="role">Role</label>
      <select class="form-control" id="role" name="role" required>
        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
      </select>
    </div>
    <div class="form-group">
      <label for="status">Status</label>
      <select class="form-control" id="status" name="status" required>
        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required minlength="8">
      </div>
      <div class="form-group col-md-6">
        <label for="password_confirmation">Konfirmasi Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="8">
      </div>
    </div>

    <div class="d-flex justify-content-end">
      <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">Batal</a>
      <button type="submit" class="btn btn-orange">Simpan User</button>
    </div>
  </form>
@endsection
