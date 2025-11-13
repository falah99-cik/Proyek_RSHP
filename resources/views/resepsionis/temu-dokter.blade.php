@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Temu Dokter</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('resepsionis.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Temu Dokter</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Temu Dokter Hari Ini</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahTemu">
                            <i class="fas fa-plus"></i> Tambah Temu
                        </button>
                    </div>
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
                            @forelse($temuDokter as $temu)
                            <tr>
                                <td>{{ $temu->no_urut }}</td>
                                <td>{{ $temu->waktu_daftar }}</td>
                                <td>{{ $temu->nama_pet }}</td>
                                <td>{{ $temu->nama_pemilik }}</td>
                                <td>{{ $temu->nama_dokter }}</td>
                                <td>
                                    @if($temu->status == 0)
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif($temu->status == 1)
                                        <span class="badge badge-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" onclick="viewDetail({{ $temu->idtemu }})">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada temu dokter hari ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Temu -->
<div class="modal fade" id="modalTambahTemu" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Temu Dokter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('resepsionis.temu-dokter.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="idpet">Hewan Peliharaan</label>
                        <select class="form-control" id="idpet" name="idpet" required>
                            <option value="">Pilih Hewan</option>
                            @foreach($pets as $pet)
                            <option value="{{ $pet->idpet }}">{{ $pet->nama }} - {{ $pet->pemilik->user->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="iddokter">Dokter</label>
                        <select class="form-control" id="iddokter" name="iddokter" required>
                            <option value="">Pilih Dokter</option>
                            @foreach($dokters as $dokter)
                            <option value="{{ $dokter->idrole_user }}">{{ $dokter->nama }}</option>
                            @endforeach
                        </select>
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

@push('scripts')
<script>
function viewDetail(id) {
    // Implementasi untuk melihat detail temu dokter
    console.log('View detail for ID:', id);
}
</script>
@endpush