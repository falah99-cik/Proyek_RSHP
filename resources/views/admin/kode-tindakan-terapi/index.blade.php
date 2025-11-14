@extends('layouts.admin')

@section('title', 'Manajemen Kode Tindakan Terapi')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/user_management.css') }}">
@endpush

@section('content')
<div class="container-fluid">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Kode Tindakan Terapi</h1>
            <p class="page-subtitle">Kelola kode tindakan dan terapi layanan klinik</p>
        </div>

        <button class="btn btn-primary" onclick="openModal('createModal')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Kode Tindakan Terapi
        </button>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- TABLE PREMIUM -->
    <div class="premium-table">
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                        <th>Kategori Klinis</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($kodeTindakanTerapi as $kode)
                    <tr>
                        <td>
                            <div class="user-flex">
                                <span>{{ $kode->kode }}</span>
                            </div>
                        </td>

                        <td>{{ $kode->deskripsi_tindakan_terapi }}</td>
                        <td>{{ $kode->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $kode->kategoriKlinis->nama_kategori_klinis ?? '-' }}</td>

                        <td>
                            <div class="action-buttons">

                                <!-- EDIT -->
                                <button type="button"
                                        class="btn-action edit"
                                        onclick="openModal('editModal{{ $kode->idkode_tindakan_terapi }}')"
                                        title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <!-- DELETE -->
                                <form method="POST"
                                      action="{{ route('admin.kode-tindakan-terapi.destroy', $kode->idkode_tindakan_terapi) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn-action delete" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">
                            Tidak ada data kode tindakan terapi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>


<!-- CREATE MODAL -->
<div class="modal-backdrop" id="createModal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Kode Tindakan Terapi</h3>
            <button onclick="closeModal('createModal')" class="modal-close">✕</button>
        </div>

        <form action="{{ route('admin.kode-tindakan-terapi.store') }}" method="POST">
            @csrf

            <div class="modal-body">
                <div class="form-group">
                    <label>Kode</label>
                    <input type="text" name="kode" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Tindakan</label>
                    <textarea name="deskripsi_tindakan_terapi" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="idkategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->idkategori }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Kategori Klinis</label>
                    <select name="idkategori_klinis" class="form-control" required>
                        <option value="">Pilih Kategori Klinis</option>
                        @foreach($kategoriKlinis as $klinis)
                            <option value="{{ $klinis->idkategori_klinis }}">{{ $klinis->nama_kategori_klinis }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('createModal')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>

    </div>
</div>


<!-- EDIT MODALS -->
@foreach($kodeTindakanTerapi as $kode)
<div class="modal-backdrop" id="editModal{{ $kode->idkode_tindakan_terapi }}" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Kode Tindakan Terapi</h3>
            <button onclick="closeModal('editModal{{ $kode->idkode_tindakan_terapi }}')" class="modal-close">✕</button>
        </div>

        <form action="{{ route('admin.kode-tindakan-terapi.update', $kode->idkode_tindakan_terapi) }}" method="POST">
            @csrf
            @method("PUT")

            <div class="modal-body">

                <div class="form-group">
                    <label>Kode</label>
                    <input type="text" name="kode" value="{{ $kode->kode }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Tindakan</label>
                    <textarea name="deskripsi_tindakan_terapi" class="form-control" rows="3" required>{{ $kode->deskripsi_tindakan_terapi }}</textarea>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="idkategori" class="form-control" required>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->idkategori }}"
                                {{ $kategori->idkategori == $kode->idkategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Kategori Klinis</label>
                    <select name="idkategori_klinis" class="form-control" required>
                        @foreach($kategoriKlinis as $klinis)
                            <option value="{{ $klinis->idkategori_klinis }}"
                                {{ $klinis->idkategori_klinis == $kode->idkategori_klinis ? 'selected' : '' }}>
                                {{ $klinis->nama_kategori_klinis }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('editModal{{ $kode->idkode_tindakan_terapi }}')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>

        </form>
    </div>
</div>
@endforeach

@endsection


@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).style.display = 'block';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 300);
    });
}, 5000);
</script>
@endpush
