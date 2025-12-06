@extends('layouts.main')
@section('title', 'Class Management')
@section('page_heading', 'Class Management')
@section('card_title', 'Class Schedule')
@section('content')


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
      @forelse($classes ?? [] as $class)
        @php
          $start = \Carbon\Carbon::parse($class->start_at);
          $end = \Carbon\Carbon::parse($class->end_at);
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
          <td><span class="badge badge-{{ $statusClass }}">{{ $class->status }}</span></td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center text-muted">Belum ada jadwal kelas.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
@endsection
