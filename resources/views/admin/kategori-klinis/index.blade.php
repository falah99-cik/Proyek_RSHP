@extends('layouts.admin')

@section('title', 'Manajemen Kategori Klinis')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user_management.css') }}">
@endpush

@section('content')
<div class="container-fluid">

    <div class="page-header">
        <div>
            <h1 class="page-title">Data Kategori Klinis</h1>
        </div>

        <button class="btn btn-primary" onclick="openModal('createModal')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Kategori Klinis
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="premium-table">
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Nama Kategori Klinis</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($kategoriKlinis as $kategori)
                    <tr>

                        <td>
                            <div class="user-flex">
                                <span>{{ $kategori->nama_kategori_klinis }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="action-buttons">

                                <button class="btn-action edit"
                                    onclick="openModal('editModal{{ $kategori->idkategori_klinis }}')">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <form method="POST"
                                      action="{{ route('admin.kategori-klinis.destroy', $kategori->idkategori_klinis) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus kategori klinis?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-action delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-slate-500">
                            Tidak ada data kategori klinis.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <div class="modal-backdrop" id="createModal" style="display:none;">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Kategori Klinis</h3>
                <button class="modal-close" onclick="closeModal('createModal')">✕</button>
            </div>

            <form action="{{ route('admin.kategori-klinis.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kategori Klinis</label>
                        <input type="text" name="nama_kategori_klinis" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" onclick="closeModal('createModal')">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    @foreach($kategoriKlinis as $kategori)
    <div class="modal-backdrop" id="editModal{{ $kategori->idkategori_klinis }}" style="display:none;">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Edit Kategori Klinis</h3>
                <button class="modal-close" onclick="closeModal('editModal{{ $kategori->idkategori_klinis }}')">✕</button>
            </div>

            <form action="{{ route('admin.kategori-klinis.update', $kategori->idkategori_klinis) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kategori Klinis</label>
                        <input type="text"
                               name="nama_kategori_klinis"
                               value="{{ $kategori->nama_kategori_klinis }}"
                               class="form-control"
                               required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" onclick="closeModal('editModal{{ $kategori->idkategori_klinis }}')">Batal</button>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach


</div>
@endsection


@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).style.display = "block";
}

function closeModal(id) {
    document.getElementById(id).style.display = "none";
}

window.onclick = e => {
    if (e.target.classList.contains("modal-backdrop")) {
        e.target.style.display = "none";
    }
};

setTimeout(() => {
    document.querySelectorAll(".alert").forEach(a => {
        a.style.opacity = "0";
        setTimeout(() => a.remove(), 300);
    });
}, 5000);
</script>
@endpush
