@extends('layouts.dokter')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tambah Rekam Medis</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dokter.jadwal-pemeriksaan') }}">Jadwal</a></li>
                    <li class="breadcrumb-item active">Tambah Rekam Medis</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Rekam Medis</h3>
                    </div>
                    <form action="{{ route('dokter.rekam-medis.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="idreservasi_dokter" value="{{ $reservasi->idreservasi_dokter }}">
                        <input type="hidden" name="idpet" value="{{ $reservasi->idpet }}">
                        
                        <div class="card-body">
                            <div class="form-group">
                                <label>Anamnesa</label>
                                <textarea class="form-control" name="anamnesa" rows="3" required>{{ old('anamnesa') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Temuan Klinis</label>
                                <textarea class="form-control" name="temuan_klinis" rows="3" required>{{ old('temuan_klinis') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Diagnosa</label>
                                <textarea class="form-control" name="diagnosa" rows="2" required>{{ old('diagnosa') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Prognosa</label>
                                <textarea class="form-control" name="prognosa" rows="2">{{ old('prognosa') }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('dokter.jadwal-pemeriksaan') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pasien</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> {{ $reservasi->nama_pet }}</p>
                        <p><strong>Jenis:</strong> {{ $reservasi->nama_jenis_hewan }}</p>
                        <p><strong>Ras:</strong> {{ $reservasi->nama_ras }}</p>
                        <p><strong>Pemilik:</strong> {{ $reservasi->nama_pemilik }}</p>
                        <p><strong>No. HP:</strong> {{ $reservasi->no_wa }}</p>
                        <p><strong>Alamat:</strong> {{ $reservasi->alamat }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection