@extends('layouts.admin')

@section('title', 'Manajemen Kode Tindakan Terapi')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>Data Kode Tindakan Terapi</h3>
        <p class="page-subtitle">Kelola kode tindakan dan terapi untuk layanan klinik</p>
    </div>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Tambah Kode Tindakan Terapi
    </button>
</div>

<div class="card">
    <div class="card-body">
                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <div>
                                <h5>Sukses!</h5>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <div>
                                <h5>Error!</h5>
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kode</th>
                                    <th>Deskripsi Tindakan</th>
                                    <th>Kategori</th>
                                    <th>Kategori Klinis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kodeTindakanTerapi as $kode)
                                    <tr>
                                        <td>{{ $kode->idkode_tindakan_terapi }}</td>
                                        <td>
                                            <div class="avatar avatar-sm">
                                                <div class="avatar-text">{{ $kode->kode }}</div>
                                            </div>
                                            {{ $kode->kode }}
                                        </td>
                                        <td>{{ $kode->deskripsi_tindakan_terapi }}</td>
                                        <td>{{ $kode->kategori->nama_kategori ?? '-' }}</td>
                                        <td>{{ $kode->kategoriKlinis->nama_kategori_klinis ?? '-' }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-warning btn-sm" onclick="openModal('editModal{{ $kode->idkode_tindakan_terapi }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.kode-tindakan-terapi.destroy', $kode->idkode_tindakan_terapi) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kode tindakan terapi ini?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="empty-state">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                </svg>
                                                <p>Tidak ada data kode tindakan terapi</p>
                                            </div>
                                        </td>
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

<!-- Create Modal -->
<div class="modal-backdrop" id="createModalBackdrop" style="display: none;"></div>
<div class="modal" id="createModal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kode Tindakan Terapi</h5>
                <button type="button" class="modal-close" onclick="closeModal('createModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.kode-tindakan-terapi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode">Kode Tindakan</label>
                        <input type="text" class="form-control @error('kode') is-invalid @enderror" 
                               id="kode" name="kode" required>
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_tindakan_terapi">Deskripsi Tindakan</label>
                        <textarea class="form-control @error('deskripsi_tindakan_terapi') is-invalid @enderror" 
                                  id="deskripsi_tindakan_terapi" name="deskripsi_tindakan_terapi" rows="3" required></textarea>
                        @error('deskripsi_tindakan_terapi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="idkategori">Kategori</label>
                        <select class="form-control @error('idkategori') is-invalid @enderror" id="idkategori" name="idkategori" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->idkategori }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('idkategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="idkategori_klinis">Kategori Klinis</label>
                        <select class="form-control @error('idkategori_klinis') is-invalid @enderror" id="idkategori_klinis" name="idkategori_klinis" required>
                            <option value="">Pilih Kategori Klinis</option>
                            @foreach($kategoriKlinis as $kategoriKlinisItem)
                                <option value="{{ $kategoriKlinisItem->idkategori_klinis }}">{{ $kategoriKlinisItem->nama_kategori_klinis }}</option>
                            @endforeach
                        </select>
                        @error('idkategori_klinis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modals -->
@foreach($kodeTindakanTerapi as $kode)
<div class="modal-backdrop" id="editModalBackdrop{{ $kode->idkode_tindakan_terapi }}" style="display: none;"></div>
<div class="modal" id="editModal{{ $kode->idkode_tindakan_terapi }}" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kode Tindakan Terapi</h5>
                <button type="button" class="modal-close" onclick="closeModal('editModal{{ $kode->idkode_tindakan_terapi }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.kode-tindakan-terapi.update', $kode->idkode_tindakan_terapi) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode{{ $kode->idkode_tindakan_terapi }}">Kode Tindakan</label>
                        <input type="text" class="form-control @error('kode') is-invalid @enderror" 
                               id="kode{{ $kode->idkode_tindakan_terapi }}" 
                               name="kode" 
                               value="{{ old('kode', $kode->kode) }}" required>
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_tindakan_terapi{{ $kode->idkode_tindakan_terapi }}">Deskripsi Tindakan</label>
                        <textarea class="form-control @error('deskripsi_tindakan_terapi') is-invalid @enderror" 
                                  id="deskripsi_tindakan_terapi{{ $kode->idkode_tindakan_terapi }}" 
                                  name="deskripsi_tindakan_terapi" rows="3" required>{{ old('deskripsi_tindakan_terapi', $kode->deskripsi_tindakan_terapi) }}</textarea>
                        @error('deskripsi_tindakan_terapi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="idkategori{{ $kode->idkode_tindakan_terapi }}">Kategori</label>
                        <select class="form-control @error('idkategori') is-invalid @enderror" 
                                id="idkategori{{ $kode->idkode_tindakan_terapi }}" 
                                name="idkategori" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->idkategori }}" 
                                        {{ old('idkategori', $kode->idkategori) == $kategori->idkategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('idkategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="idkategori_klinis{{ $kode->idkode_tindakan_terapi }}">Kategori Klinis</label>
                        <select class="form-control @error('idkategori_klinis') is-invalid @enderror" 
                                id="idkategori_klinis{{ $kode->idkode_tindakan_terapi }}" 
                                name="idkategori_klinis" required>
                            <option value="">Pilih Kategori Klinis</option>
                            @foreach($kategoriKlinis as $kategoriKlinisItem)
                                <option value="{{ $kategoriKlinisItem->idkategori_klinis }}" 
                                        {{ old('idkategori_klinis', $kode->idkategori_klinis) == $kategoriKlinisItem->idkategori_klinis ? 'selected' : '' }}>
                                    {{ $kategoriKlinisItem->nama_kategori_klinis }}
                                </option>
                            @endforeach
                        </select>
                        @error('idkategori_klinis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModal{{ $kode->idkode_tindakan_terapi }}')">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/manajemen_kode_tindakan_terapi.css') }}">
@endpush

@push('scripts')
<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const backdrop = document.getElementById(modalId + 'Backdrop');
        if (modal) {
            modal.style.display = 'block';
            if (backdrop) {
                backdrop.style.display = 'block';
            }
            setTimeout(() => {
                modal.classList.add('show');
                if (backdrop) {
                    backdrop.classList.add('show');
                }
            }, 10);
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const backdrop = document.getElementById(modalId + 'Backdrop');
        if (modal) {
            modal.classList.remove('show');
            if (backdrop) {
                backdrop.classList.remove('show');
            }
            setTimeout(() => {
                modal.style.display = 'none';
                if (backdrop) {
                    backdrop.style.display = 'none';
                }
            }, 300);
        }
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-backdrop')) {
            const backdropId = event.target.id;
            const modalId = backdropId.replace('Backdrop', '');
            closeModal(modalId);
        }
    });

    // Auto-close alert messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    });
</script>
@endpush