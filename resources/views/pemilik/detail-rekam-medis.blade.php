@extends('layouts.pemilik')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Rekam Medis</h3>
                    <div class="card-tools">
                        <a href="{{ route('pemilik.daftar-rekam-medis') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Hewan</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nama Hewan</strong></td>
                                    <td>: {{ $rekamMedis->pet->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Hewan</strong></td>
                                    <td>: {{ $rekamMedis->pet->jenisHewan->nama_jenis }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ras Hewan</strong></td>
                                    <td>: {{ $rekamMedis->pet->rasHewan->nama_ras }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Umur</strong></td>
                                    <td>: {{ $rekamMedis->pet->tanggal_lahir->age }} tahun</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin</strong></td>
                                    <td>: {{ $rekamMedis->pet->jenis_kelamin == 'J' ? 'Jantan' : 'Betina' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Pemeriksaan</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Tanggal Pemeriksaan</strong></td>
                                    <td>: {{ $rekamMedis->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dokter Pemeriksa</strong></td>
                                    <td>: {{ $rekamMedis->dokter->user->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Anamnesa</strong></td>
                                    <td>: {{ $rekamMedis->anamnesa }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Temuan Klinis</strong></td>
                                    <td>: {{ $rekamMedis->temuan_klinis }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diagnosa</strong></td>
                                    <td>: {{ $rekamMedis->diagnosa }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Prognosa</strong></td>
                                    <td>: {{ $rekamMedis->prognosa ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($rekamMedis->detailRekamMedis->count() > 0)
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Detail Rekam Medis</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kategori</th>
                                            <th>Kategori Klinis</th>
                                            <th>Kode Terapi</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rekamMedis->detailRekamMedis as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->kategori->nama_kategori }}</td>
                                            <td>{{ $detail->kategoriKlinis->nama_kategori_klinis ?? '-' }}</td>
                                            <td>{{ $detail->kodeTerapi->nama_kode_terapi ?? '-' }}</td>
                                            <td>{{ $detail->keterangan }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada detail rekam medis</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection