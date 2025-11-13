@extends('layouts.perawat')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Rekam Medis</h3>
                    <div class="card-tools">
                        <a href="{{ route('perawat.rekam-medis') }}" class="btn btn-secondary btn-sm">
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
                            <h5>Informasi Pemilik</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nama Pemilik</strong></td>
                                    <td>: {{ $rekamMedis->pet->pemilik->user->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. HP</strong></td>
                                    <td>: {{ $rekamMedis->pet->pemilik->no_hp }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>: {{ $rekamMedis->pet->pemilik->alamat }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Hasil Pemeriksaan</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Tanggal Pemeriksaan</strong></td>
                                    <td>: {{ $rekamMedis->created_at->format('d/m/Y H:i') }}</td>
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
                                            <th>Aksi</th>
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
                                            <td>
                                                <form action="{{ route('perawat.destroy-detail-rekam-medis', $detail->iddetail_rekam_medis) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus detail ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada detail rekam medis</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahDetail">
                                <i class="fas fa-plus"></i> Tambah Detail Rekam Medis
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Detail -->
<div class="modal fade" id="modalTambahDetail" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Detail Rekam Medis</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('perawat.store-detail-rekam-medis') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="idrekam_medis" value="{{ $rekamMedis->idrekam_medis }}">
                    
                    <div class="form-group">
                        <label for="idkategori">Kategori</label>
                        <select name="idkategori" id="idkategori" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->idkategori }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="idkategori_klinis">Kategori Klinis</label>
                        <select name="idkategori_klinis" id="idkategori_klinis" class="form-control">
                            <option value="">Pilih Kategori Klinis</option>
                            @foreach($kategoriKlinis as $kategori)
                            <option value="{{ $kategori->idkategori_klinis }}">{{ $kategori->nama_kategori_klinis }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="idkode_terapi">Kode Terapi</label>
                        <select name="idkode_terapi" id="idkode_terapi" class="form-control">
                            <option value="">Pilih Kode Terapi</option>
                            @foreach($kodeTerapis as $kode)
                            <option value="{{ $kode->idkode_terapi }}">{{ $kode->nama_kode_terapi }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection