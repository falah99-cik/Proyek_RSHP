@extends('layouts.dokter')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Rekam Medis</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Hewan</th>
                                    <th>Jenis Hewan</th>
                                    <th>Ras Hewan</th>
                                    <th>Nama Pemilik</th>
                                    <th>Anamnesa</th>
                                    <th>Diagnosa</th>
                                    <th>Tanggal Pemeriksaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatRekamMedis as $index => $rekamMedis)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $rekamMedis->pet->nama }}</td>
                                    <td>{{ $rekamMedis->pet->jenisHewan->nama_jenis }}</td>
                                    <td>{{ $rekamMedis->pet->rasHewan->nama_ras }}</td>
                                    <td>{{ $rekamMedis->pet->pemilik->user->nama }}</td>
                                    <td>{{ Str::limit($rekamMedis->anamnesa, 50) }}</td>
                                    <td>{{ Str::limit($rekamMedis->diagnosa, 50) }}</td>
                                    <td>{{ $rekamMedis->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada riwayat rekam medis</td>
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