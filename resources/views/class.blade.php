@extends('layouts.main')
@section('title', 'Class Management')
@section('page_heading', 'Class Management')
@section('card_title', 'Class Schedule')

@section('content')
  <div class="alert alert-info mb-4">
    Jadwal kelas belum terhubung ke database. Tambahkan data nanti atau integrasikan dengan tabel class_schedules/class_bookings jika sudah siap.
  </div>

  <table class="table table-striped table-bordered w-100" data-datatable>
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
      <tr>
        <td>HIIT Express</td>
        <td>Coach Dimas</td>
        <td>{{ date('Y-m-d') }}</td>
        <td>18:00 - 19:00</td>
        <td>20</td>
        <td><span class="badge badge-primary">Draft</span></td>
      </tr>
      <tr>
        <td>Yoga Sunrise</td>
        <td>Coach Rani</td>
        <td>{{ date('Y-m-d', strtotime('+1 day')) }}</td>
        <td>07:00 - 08:00</td>
        <td>15</td>
        <td><span class="badge badge-success">Terjadwal</span></td>
      </tr>
    </tbody>
  </table>
@endsection
