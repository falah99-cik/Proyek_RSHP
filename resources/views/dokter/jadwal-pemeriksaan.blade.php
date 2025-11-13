@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Jadwal Pemeriksaan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Jadwal Pemeriksaan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Jadwal Pemeriksaan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Waktu Daftar</th>
                                <th>Nama Pet</th>
                                <th>Nama Pemilik</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalPemeriksaan as $jadwal)
                            <tr>
                                <td>{{ $jadwal->no_urut }}</td>
                                <td>{{ $jadwal->waktu_daftar }}</td>
                                <td>{{ $jadwal->nama_pet }}</td>
                                <td>{{ $jadwal->nama_pemilik }}</td>
                                <td>
                                    @if($jadwal->status == 0)
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif($jadwal->status == 1)
                                        <span class="badge badge-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('dokter.tambah-rekam-medis', $jadwal->idreservasi_dokter) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-stethoscope"></i> Periksa
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada jadwal pemeriksaan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection