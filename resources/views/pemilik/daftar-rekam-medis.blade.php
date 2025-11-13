@extends('layouts.pemilik')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Rekam Medis</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Hewan</th>
                                    <th>Tanggal Pemeriksaan</th>
                                    <th>Anamnesa</th>
                                    <th>Diagnosa</th>
                                    <th>Dokter</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekamMedis as $index => $rekam)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $rekam->pet->nama }}</td>
                                    <td>{{ $rekam->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ Str::limit($rekam->anamnesa, 50) }}</td>
                                    <td>{{ Str::limit($rekam->diagnosa, 50) }}</td>
                                    <td>{{ $rekam->dokter->user->nama }}</td>
                                    <td>
                                        <a href="{{ route('pemilik.detail-rekam-medis', $rekam->idrekam_medis) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada rekam medis</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection