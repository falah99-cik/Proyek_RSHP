@extends('layouts.admin')

@section('title', 'Manajemen Kategori - RSHP UNAIR')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Kategori</h1>
            <p class="page-subtitle">Kelola informasi kategori hewan di klinik</p>
        </div>
        <button onclick="openAddModal()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Kategori Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th class="action-buttons">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $kategori)
                        <tr>
                            <td>
                                <div class="avatar">
                                    <div class="avatar-sm">
                                        <div class="avatar-text">{{ substr($kategori->nama_kategori, 0, 2) }}</div>
                                    </div>
                                    <span>{{ $kategori->nama_kategori }}</span>
                                </div>
                            </td>
                            <td class="action-buttons">
                                <div class="action-buttons">
                                    <button onclick="openEditModal({{ $kategori->idkategori }})" class="btn btn-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.kategori.destroy', $kategori->idkategori) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $kategori->nama_kategori }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="empty-state">
                                <div class="empty-state-icon">📂</div>
                                <div class="empty-state-text">Belum ada data kategori</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<!-- Edit Modals -->
@foreach($kategoris as $kategori)
<div id="editKategoriModal{{ $kategori->idkategori }}" class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Kategori</h3>
            <button onclick="closeEditModal({{ $kategori->idkategori }})" class="modal-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.kategori.update', $kategori->idkategori) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_kategori{{ $kategori->idkategori }}">Nama Kategori</label>
                    <input type="text" class="form-control" id="nama_kategori{{ $kategori->idkategori }}" 
                           name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                    @error('nama_kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEditModal({{ $kategori->idkategori }})" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Add Modal -->
<div id="kategoriModal" class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle" class="modal-title">Tambah Kategori</h3>
            <button onclick="closeModal()" class="modal-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form id="kategoriForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" 
                           value="{{ old('nama_kategori') }}" required>
                    @error('nama_kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Kategori';
        document.getElementById('kategoriForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('kategoriForm').action = '{{ route("admin.kategori.store") }}';
        document.getElementById('kategoriModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('kategoriModal').classList.remove('show');
        document.getElementById('kategoriForm').reset();
    }

    function openEditModal(kategoriId) {
        document.getElementById(`editKategoriModal${kategoriId}`).classList.add('show');
    }

    function closeEditModal(kategoriId) {
        document.getElementById(`editKategoriModal${kategoriId}`).classList.remove('show');
    }

    // Close modal when clicking outside
    document.getElementById('kategoriModal').addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            closeModal();
        }
    });

    @foreach($kategoris as $kategori)
    document.getElementById('editKategoriModal{{ $kategori->idkategori }}').addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            closeEditModal({{ $kategori->idkategori }});
        }
    });
    @endforeach

    // Auto close alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/manajemen_pet.css') }}">
@endpush
@endsection