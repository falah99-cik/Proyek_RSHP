@extends('layouts.admin')

@section('title', 'Manajemen Pemilik - RSHP UNAIR')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Data Pemilik</h1>
                <p class="page-subtitle">Kelola data pemilik hewan</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="openModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Pemilik Baru
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
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Nomor WA</th>
                                <th>Alamat</th>
                                <th class="action-buttons">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pemiliks as $p)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="avatar avatar-sm mr-3">
                                                <span class="avatar-text">{{ substr($p->nama, 0, 1) }}</span>
                                            </div>
                                            <span class="font-medium">{{ $p->nama }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $p->email }}</td>
                                    <td>{{ $p->no_wa }}</td>
                                    <td>{{ $p->alamat }}</td>
                                    <td class="action-buttons">
                                        <button type="button" class="btn btn-edit" onclick="openEditModal({{ $p->idpemilik }})" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.pemilik.destroy', $p->idpemilik) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemilik {{ $p->nama }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="empty-state-icon">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                            <p class="empty-state-text">Belum ada data pemilik</p>
                                            <p class="empty-state-subtext">Silakan tambahkan data pemilik pertama Anda</p>
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

<!-- Add/Edit Modal -->
<div id="pemilikModal" class="modal-backdrop" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle" class="modal-title">Tambah Pemilik</h3>
            <button onclick="closeModal()" class="modal-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form id="pemilikForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="idpemilik" id="idpemilik">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_user">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_user" id="nama_user" class="form-control" required>
                    @error('nama_user')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" id="passwordField">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_wa">Nomor WA <span class="text-danger">*</span></label>
                    <input type="text" name="no_wa" id="no_wa" class="form-control" required placeholder="Cth: 0812XXXXXXXX">
                    @error('no_wa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3"></textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" id="newPasswordField" style="display: none;">
                    <label for="new_password">Password Baru</label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    @error('new_password')
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
    let currentPemilikId = null;
    let isEditMode = false;

    // Auto close alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    });

    function openAddModal() {
        isEditMode = false;
        currentPemilikId = null;
        document.getElementById('modalTitle').textContent = 'Tambah Pemilik';
        document.getElementById('pemilikForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('pemilikForm').action = '{{ route("admin.pemilik.store") }}';
        document.getElementById('passwordField').style.display = 'block';
        document.getElementById('password').required = true;
        document.getElementById('newPasswordField').style.display = 'none';
        document.getElementById('pemilikModal').style.display = 'block';
    }

    function openEditModal(pemilikId) {
        isEditMode = true;
        currentPemilikId = pemilikId;
        document.getElementById('modalTitle').textContent = 'Edit Pemilik';
        document.getElementById('pemilikForm').reset();
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('pemilikForm').action = `/admin/pemilik/${pemilikId}`;
        document.getElementById('passwordField').style.display = 'none';
        document.getElementById('password').required = false;
        document.getElementById('newPasswordField').style.display = 'block';
        document.getElementById('pemilikModal').style.display = 'block';

        // Fetch pemilik data
        const getPemilikDataUrl = '{{ route("admin.pemilik.getPemilikData", ["id" => "__PEMILIK_ID__"]) }}'.replace('__PEMILIK_ID__', pemilikId);
        fetch(getPemilikDataUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const pemilik = data.data;
                    document.getElementById('idpemilik').value = pemilik.idpemilik;
                    document.getElementById('nama_user').value = pemilik.nama_user;
                    document.getElementById('email').value = pemilik.email;
                    document.getElementById('no_wa').value = pemilik.no_wa;
                    document.getElementById('alamat').value = pemilik.alamat;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function closeModal() {
        document.getElementById('pemilikModal').style.display = 'none';
        document.getElementById('pemilikForm').reset();
        currentPemilikId = null;
        isEditMode = false;
    }

    // Close modal when clicking outside
    document.getElementById('pemilikModal').addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            closeModal();
        }
    });
</script>
@endpush