@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Pemilik</h1>
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
                            <h3>{{ $totalHewan }}</h3>
                            <p>Hewan Peliharaan</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-paw"></i>
                        </div>
                        <a href="{{ route('pemilik.daftar-pet') }}" class="small-box-footer">
                            Lihat Pet <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalRekamMedis }}</h3>
                            <p>Rekam Medis</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-medkit"></i>
                        </div>
                        <a href="{{ route('pemilik.daftar-rekam-medis') }}" class="small-box-footer">
                            Lihat Rekam Medis <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $reservasiAktif->count() }}</h3>
                            <p>Reservasi</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-calendar"></i>
                        </div>
                        <a href="{{ route('pemilik.daftar-reservasi') }}" class="small-box-footer">
                            Lihat Reservasi <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Hewan Peliharaan Saya</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Ras</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pets as $pet)
                                    <tr>
                                        <td>{{ $pet->nama }}</td>
                                        <td>{{ $pet->jenisHewan->nama_jenis_hewan }}</td>
                                        <td>{{ $pet->rasHewan->nama_ras }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data hewan peliharaan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Reservasi Terbaru</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Pet</th>
                                        <th>Dokter</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reservasiAktif as $reservasi)
                                    <tr>
                                        <td>{{ $reservasi->waktu_daftar }}</td>
                                        <td>{{ $reservasi->pet->nama }}</td>
                                        <td>{{ $reservasi->dokter->nama }}</td>
                                        <td>
                                            @if($reservasi->status == 0)
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif($reservasi->status == 1)
                                                <span class="badge badge-success">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data reservasi</td>
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