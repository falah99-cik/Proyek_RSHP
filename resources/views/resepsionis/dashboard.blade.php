@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Resepsionis</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalReservasiHariIni }}</h3>
                            <p>Reservasi Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-calendar"></i>
                        </div>
                        <a href="{{ route('resepsionis.temu-dokter') }}" class="small-box-footer">
                            Kelola Reservasi <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalPemilik }}</h3>
                            <p>Total Pemilik</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person"></i>
                        </div>
                        <a href="{{ route('resepsionis.registrasi-pemilik') }}" class="small-box-footer">
                            Registrasi Pemilik <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalPet }}</h3>
                            <p>Total Hewan Peliharaan</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-paw"></i>
                        </div>
                        <a href="{{ route('resepsionis.registrasi-pet') }}" class="small-box-footer">
                            Registrasi Pet <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Reservasi Hari Ini</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No Antrian</th>
                                        <th>Waktu</th>
                                        <th>Nama Pet</th>
                                        <th>Nama Pemilik</th>
                                        <th>Dokter</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reservasiHariIni as $reservasi)
                                    <tr>
                                        <td>{{ $reservasi->no_urut }}</td>
                                        <td>{{ $reservasi->waktu_daftar }}</td>
                                        <td>{{ $reservasi->nama_pet }}</td>
                                        <td>{{ $reservasi->nama_pemilik }}</td>
                                        <td>{{ $reservasi->nama_dokter }}</td>
                                        <td>
                                            @if($reservasi->status == 0)
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif($reservasi->status == 1)
                                                <span class="badge badge-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('resepsionis.temu-dokter') }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada reservasi hari ini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection