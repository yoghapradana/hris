@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0">Selamat datang, <strong class="text-dark">Ahmad Fauzi</strong></p>
                <small class="text-secondary">Senin, 16 Maret 2025</small>
            </div>
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2"
                    style="width: 32px; height: 32px;">AF</div>
                <a href="#" class="text-muted small">
                    Profile <i class="fas fa-chevron-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 px-3">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Hari Kerja</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-left-warning shadow h-100 py-2 px-3">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Izin</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">16</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-left-danger shadow h-100 py-2 px-3">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Terlambat</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                </div>
            </div>
        </div>

        <!-- Card Grid -->
        <div class="row">
            @foreach ([['icon' => 'far fa-id-card', 'title' => 'Data Pribadi', 'desc' => 'Ubah dan perbarui informasi pribadi Anda', 'btn' => 'Lihat Detail'], ['icon' => 'far fa-calendar-check', 'title' => 'Absensi', 'desc' => 'Menu URL Absensi Pegawai', 'btn' => 'Absensi'], ['icon' => 'far fa-clock', 'title' => 'Timesheet', 'desc' => 'Menu URL Timesheet', 'btn' => 'Lihat Detail'], ['icon' => 'far fa-calendar-alt', 'title' => 'Cuti', 'desc' => 'Menu URL Record Cuti, Sakit, dan Izin', 'btn' => 'Lihat Detail'],] as $item)
                <div class="col-md-3 mb-4">
                    <div class="card shadow h-100 p-3">
                        <div class="d-flex align-items-center text-muted mb-2">
                            <i class="{{ $item['icon'] }} mr-2"></i>
                            <span class="font-weight-bold small">{{ $item['title'] }}</span>
                        </div>
                        <p class="text-muted small mb-2">{{ $item['desc'] }}</p>
                        <a href="#" class="btn btn-sm btn-primary">{{ $item['btn'] }}</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Kehadiran</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered small" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([['tgl' => '15 Maret 2025', 'in' => '08:05', 'out' => '17:00', 'status' => 'Hadir', 'class' => 'text-success'], ['tgl' => '14 Maret 2025', 'in' => '08:30', 'out' => '17:15', 'status' => 'Izin', 'class' => 'text-warning'], ['tgl' => '13 Maret 2025', 'in' => '08:00', 'out' => '17:00', 'status' => 'Hadir', 'class' => 'text-success'], ['tgl' => '12 Maret 2025', 'in' => '', 'out' => '', 'status' => 'Tidak Hadir', 'class' => 'text-danger'], ['tgl' => '11 Maret 2025', 'in' => '07:56', 'out' => '17:10', 'status' => 'Hadir', 'class' => 'text-success'],] as $row)
                                <tr>
                                    <td>{{ $row['tgl'] }}</td>
                                    <td>{{ $row['in'] }}</td>
                                    <td>{{ $row['out'] }}</td>
                                    <td><span class="font-weight-bold {{ $row['class'] }}">{{ $row['status'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
