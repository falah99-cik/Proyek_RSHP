@extends('layouts.admin')

@section('title', 'Manajemen Pet')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/user_management.css') }}">
@endpush

@section('content')
<div>

    <div class="page-header">
        <div>
            <h1 class="page-title">Data Hewan</h1>
        </div>

        <button type="button" class="btn btn-primary" onclick="openAddModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Hewan
        </button>
    </div>

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
                        <th>Nama Hewan</th>
                        <th>Jenis</th>
                        <th>Ras</th>
                        <th>Umur</th>
                        <th>Pemilik</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pets as $pet)
                        <tr>
                            <td>
                                <div class="user-flex">
                                    <span>{{ $pet->nama }}</span>
                                </div>
                            </td>

                            <td>{{ $pet->ras->jenisHewan->nama_jenis_hewan ?? '-' }}</td>
                            <td>{{ $pet->ras->nama_ras ?? '-' }}</td>

                            <td>
                                {{ $pet->tanggal_lahir
                                    ? \Carbon\Carbon::parse($pet->tanggal_lahir)->age . ' tahun'
                                    : '-' }}
                            </td>

                            <td>{{ $pet->pemilik->user->nama ?? '-' }}</td>

                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action edit"
                                        onclick="openEditModal({{ $pet->idpet }})"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="{{ route('admin.pet.destroy', $pet->idpet) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus?')">
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
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                Tidak ada data hewan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

<div id="petModal" class="modal-backdrop" style="display: none;">
    <div class="modal">

        <div class="modal-header">
            <h3 id="modalTitle" class="modal-title">Tambah Hewan</h3>
            <button onclick="closeModal()" class="modal-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <form id="petForm" method="POST">
            @csrf
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <div class="modal-body">

                <div class="form-group">
                    <label>Pemilik <span class="text-danger">*</span></label>
                    <select name="idpemilik" id="idpemilik" class="form-control" required>
                        <option value="">Pilih Pemilik</option>
                        @foreach($owners as $owner)
                            <option value="{{ $owner->idpemilik }}">
                                {{ $owner->user->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Hewan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_hewan" id="nama_hewan" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Jenis Hewan <span class="text-danger">*</span></label>
                    <select name="idjenis_hewan" id="idjenis_hewan" class="form-control" required>
                        <option value="">Pilih Jenis Hewan</option>
                        @foreach($jenisHewan as $jenis)
                            <option value="{{ $jenis->idjenis_hewan }}">
                                {{ $jenis->nama_jenis_hewan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Ras Hewan <span class="text-danger">*</span></label>
                    <select name="idras_hewan" id="idras_hewan" class="form-control" required>
                        <option value="">Pilih Ras Hewan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Warna & Tanda Khusus</label>
                    <input type="text" name="warna_tanda" id="warna_tanda" class="form-control">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById("modalTitle").textContent = "Tambah Hewan";
        document.getElementById("formMethod").value = "POST";
        document.getElementById("petForm").action = "{{ route('admin.pet.store') }}";
        document.getElementById("petForm").reset();
        document.getElementById("petModal").style.display = "block";
    }

    function openEditModal(id) {
        document.getElementById("modalTitle").textContent = "Edit Hewan";
        document.getElementById("formMethod").value = "PUT";
        document.getElementById("petForm").action = `/admin/pet/${id}`;
        document.getElementById("petForm").reset();
        document.getElementById("petModal").style.display = "block";

        const url = '{{ route("admin.pet.getPetData", ["id" => "__ID__"]) }}'.replace('__ID__', id);

        fetch(url)
            .then(res => res.json())
            .then(data => {
                const p = data.data;

                document.getElementById('idpemilik').value = p.idpemilik;
                document.getElementById('nama_hewan').value = p.nama_hewan;
                document.getElementById('tanggal_lahir').value = p.tanggal_lahir;
                document.getElementById('jenis_kelamin').value = p.jenis_kelamin;
                document.getElementById('warna_tanda').value = p.warna_tanda;
                document.getElementById('idjenis_hewan').value = p.idjenis_hewan;

                loadRasHewan(p.idjenis_hewan, p.idras_hewan);
            });
    }

    function closeModal() {
        document.getElementById("petModal").style.display = "none";
    }

    function loadRasHewan(idJenis, selected = null) {
        fetch(`/admin/ras-hewan/by-jenis/${idJenis}`)
            .then(res => res.json())
            .then(list => {
                const rasSelect = document.getElementById('idras_hewan');
                rasSelect.innerHTML = '<option value="">Pilih Ras Hewan</option>';

                list.forEach(r => {
                    const opt = document.createElement('option');
                    opt.value = r.idras_hewan;
                    opt.textContent = r.nama_ras;

                    if (selected && selected == r.idras_hewan) opt.selected = true;

                    rasSelect.appendChild(opt);
                });
            });
    }

    document.getElementById("idjenis_hewan").addEventListener("change", function() {
        this.value ? loadRasHewan(this.value) : null;
    });
</script>
@endpush
