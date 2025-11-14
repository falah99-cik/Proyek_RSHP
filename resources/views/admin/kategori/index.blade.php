@extends('layouts.admin')

@section('title', 'Manajemen Kategori - RSHP UNAIR')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/user_management.css') }}">
@endpush

@section('content')
<div>

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Kategori</h1>
        </div>

        <button type="button" class="btn btn-primary" onclick="openAddModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Kategori Baru
        </button>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif


    {{-- TABEL PREMIUM --}}
    <div class="premium-table">
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($kategoris as $kategori)
                        <tr>
                            <td>
                                <div class="user-flex">
                                    <span>{{ $kategori->nama_kategori }}</span>
                                </div>
                            </td>

                            <td>
                                <div class="action-buttons">

                                    {{-- EDIT --}}
                                    <button class="btn-action edit"
                                            onclick="openEditModal({{ $kategori->idkategori }}, '{{ $kategori->nama_kategori }}')"
                                            title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    {{-- DELETE --}}
                                    <form action="{{ route('admin.kategori.destroy', $kategori->idkategori) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus kategori {{ $kategori->nama_kategori }}?')">
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
                            <td colspan="2" class="text-center py-4 text-gray-500">
                                Tidak ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>


{{-- MODAL ADD --}}
<div id="kategoriModal" class="modal-backdrop" style="display:none;">
    <div class="modal">

        <div class="modal-header">
            <h3 id="modalTitle" class="modal-title">Tambah Kategori</h3>
            <button onclick="closeModal()" class="modal-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <form id="kategoriForm" method="POST">
            @csrf
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>

        </form>

    </div>
</div>

@endsection


@push('scripts')
<script>
    function openAddModal() {
        document.getElementById("modalTitle").textContent = "Tambah Kategori";
        document.getElementById("kategoriForm").reset();
        document.getElementById("formMethod").value = "POST";
        document.getElementById("kategoriForm").action = "{{ route('admin.kategori.store') }}";
        document.getElementById("kategoriModal").style.display = "block";
    }

    function openEditModal(id, nama) {
        document.getElementById("modalTitle").textContent = "Edit Kategori";
        document.getElementById("formMethod").value = "PUT";
        document.getElementById("kategoriForm").action = "/admin/kategori/" + id;

        document.getElementById("nama_kategori").value = nama;

        document.getElementById("kategoriModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("kategoriModal").style.display = "none";
    }

    // Auto Close Alerts
    setTimeout(() => {
        document.querySelectorAll(".alert").forEach(a => {
            a.style.opacity = "0";
            setTimeout(() => a.remove(), 300);
        });
    }, 5000);
</script>
@endpush

