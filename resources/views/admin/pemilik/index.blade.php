@extends('layouts.admin')

@section('title', 'Manajemen Pemilik - RSHP UNAIR')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user_management.css') }}">
@endpush

@section('content')
<div>

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Pemilik</h1>
        </div>

        <button type="button" class="btn btn-primary" onclick="openModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Pemilik Baru
        </button>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- TABEL PREMIUM -->
    <div class="premium-table">
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Nomor WA</th>
                        <th>Alamat</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pemiliks as $p)
                        <tr>
                            <td>
                                <div class="user-flex">
                                    <span>{{ $p->user->nama }}</span>
                                </div>
                            </td>

                            <td>{{ $p->user->email }}</td>
                            <td>{{ $p->no_wa }}</td>
                            <td>{{ $p->alamat }}</td>

                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action edit"
                                            onclick="openEditModal({{ $p->idpemilik }})"
                                            title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="{{ route('admin.pemilik.destroy', $p->idpemilik) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemilik {{ $p->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-action delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                Tidak ada data pemilik.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>


<!-- Modal Add/Edit -->
<div id="pemilikModal" class="modal-backdrop" style="display: none;">
    <div class="modal">

        <div class="modal-header">
            <h3 id="modalTitle" class="modal-title">Tambah Pemilik</h3>

            <button onclick="closeModal()" class="modal-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_user" id="nama_user" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="form-group" id="passwordField">
                    <label>Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Nomor WA <span class="text-danger">*</span></label>
                    <input type="text" name="no_wa" id="no_wa" class="form-control" required placeholder="0812XXXXXXX">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group" id="newPasswordField" style="display:none;">
                    <label>Password Baru</label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak diganti</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>

    </div>
</div>

@endsection



@push('scripts')
<script>
    let currentPemilikId = null;

    // Auto-hide alert
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    });

    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Pemilik';
        document.getElementById('pemilikForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('passwordField').style.display = 'block';
        document.getElementById('newPasswordField').style.display = 'none';
        document.getElementById('pemilikForm').action = '{{ route("admin.pemilik.store") }}';
        document.getElementById('pemilikModal').style.display = 'block';
    }

    function openEditModal(id) {
        document.getElementById('modalTitle').textContent = 'Edit Pemilik';
        document.getElementById('pemilikForm').reset();
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('passwordField').style.display = 'none';
        document.getElementById('newPasswordField').style.display = 'block';
        document.getElementById('pemilikForm').action = `/admin/pemilik/${id}`;
        document.getElementById('pemilikModal').style.display = 'block';

        const url = '{{ route("admin.pemilik.getPemilikData", ["id" => "__ID__"]) }}'.replace('__ID__', id);

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const p = data.data;
                    document.getElementById('idpemilik').value = p.idpemilik;
                    document.getElementById('nama_user').value = p.nama_user;
                    document.getElementById('email').value = p.email;
                    document.getElementById('no_wa').value = p.no_wa;
                    document.getElementById('alamat').value = p.alamat;
                }
            });
    }

    function closeModal() {
        document.getElementById('pemilikModal').style.display = 'none';
    }
</script>
@endpush
