@extends('layouts.admin')

@section('title', 'Manajemen Pet')
@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/manajemen_pet.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Data Hewan</h1>
                <p class="page-subtitle">Kelola data hewan peliharaan</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="openPetModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Hewan
            </button>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Hewan</th>
                                <th>Jenis</th>
                                <th>Ras</th>
                                <th>Umur</th>
                                <th>Pemilik</th>
                                <th class="action-buttons">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pets as $pet)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="avatar avatar-sm mr-3">
                                                <span class="avatar-text">{{ substr($pet->nama_hewan, 0, 1) }}</span>
                                            </div>
                                            <span class="font-medium">{{ $pet->nama_hewan }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $pet->jenisHewan->nama_jenis_hewan ?? '-' }}</td>
                                    <td>{{ $pet->rasHewan->nama_ras ?? '-' }}</td>
                                    <td>{{ $pet->umur ?? '-' }} {{ $pet->satuan_umur ?? '' }}</td>
                                    <td>{{ $pet->pemilikHewan->user->nama ?? '-' }}</td>
                                    <td class="action-buttons">
                                        <button type="button" class="btn btn-edit" onclick="openEditModal({{ $pet }})" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.pet.destroy', $pet->idhewan) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hewan {{ $pet->nama_hewan }}?');">
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
                                    <td colspan="6" class="text-center">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="empty-state-icon">
                                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                                <path d="M2 17l10 5 10-5"></path>
                                                <path d="M2 12l10 5 10-5"></path>
                                            </svg>
                                            <p class="empty-state-text">Belum ada data hewan</p>
                                            <p class="empty-state-subtext">Silakan tambahkan data hewan pertama Anda</p>
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
<div id="petModal" class="modal-backdrop" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle" class="modal-title">Tambah Hewan</h3>
            <button onclick="closeModal()" class="modal-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form id="petForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="idpemilik">Pemilik <span class="text-danger">*</span></label>
                    <select name="idpemilik" id="idpemilik" class="form-control" required>
                        <option value="">Pilih Pemilik</option>
                        @foreach($owners as $owner)
                            <option value="{{ $owner->idpemilik }}">{{ $owner->user->nama }}</option>
                        @endforeach
                    </select>
                    @error('idpemilik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_pet">Nama Hewan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pet" id="nama_pet" class="form-control" required>
                    @error('nama_pet')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="idjenis_hewan">Jenis Hewan <span class="text-danger">*</span></label>
                    <select name="idjenis_hewan" id="idjenis_hewan" class="form-control" required>
                        <option value="">Pilih Jenis Hewan</option>
                        @foreach($jenisHewan as $jenis)
                            <option value="{{ $jenis->idjenis_hewan }}">{{ $jenis->nama_jenis_hewan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="idras_hewan">Ras Hewan <span class="text-danger">*</span></label>
                    <select name="idras_hewan" id="idras_hewan" class="form-control" required>
                        <option value="">Pilih Ras Hewan</option>
                    </select>
                    @error('idras_hewan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="warna_tanda">Warna & Tanda Khusus</label>
                    <input type="text" name="warna_tanda" id="warna_tanda" class="form-control" placeholder="Contoh: Coklat, belang putih">
                    @error('warna_tanda')
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
    let currentPetId = null;
    let isEditMode = false;

    function openAddModal() {
        isEditMode = false;
        currentPetId = null;
        document.getElementById('modalTitle').textContent = 'Tambah Hewan';
        document.getElementById('petForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('petForm').action = '{{ route("admin.pet.store") }}';
        document.getElementById('petModal').style.display = 'block';
    }

    function openEditModal(petId) {
        isEditMode = true;
        currentPetId = petId;
        document.getElementById('modalTitle').textContent = 'Edit Hewan';
        document.getElementById('petForm').reset();
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('petForm').action = `/admin/pet/${petId}`;
        document.getElementById('petModal').style.display = 'block';

        // Fetch pet data - fix route generation
        const getPetDataUrl = '{{ route("admin.pet.getPetData", ["id" => "__PET_ID__"]) }}'.replace('__PET_ID__', petId);
        fetch(getPetDataUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const pet = data.data;
                    document.getElementById('idpemilik').value = pet.idpemilik;
                    document.getElementById('nama_pet').value = pet.nama;
                    document.getElementById('tanggal_lahir').value = pet.tanggal_lahir;
                    document.getElementById('jenis_kelamin').value = pet.jenis_kelamin;
                    document.getElementById('warna_tanda').value = pet.warna_tanda;
                    document.getElementById('idjenis_hewan').value = pet.idjenis_hewan;
                    
                    // Load ras hewan for selected jenis hewan
                    loadRasHewan(pet.idjenis_hewan, pet.idras_hewan);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function closeModal() {
        document.getElementById('petModal').style.display = 'none';
        document.getElementById('petForm').reset();
        currentPetId = null;
        isEditMode = false;
    }

    function loadRasHewan(jenisHewanId, selectedRasId = null) {
        fetch(`{{ route('admin.ras-hewan.by-jenis', ['jenisHewanId' => '__JENIS_ID__']) }}`.replace('__JENIS_ID__', jenisHewanId))
            .then(response => response.json())
            .then(data => {
                const rasSelect = document.getElementById('idras_hewan');
                rasSelect.innerHTML = '<option value="">Pilih Ras Hewan</option>';
                
                data.forEach(ras => {
                    const option = document.createElement('option');
                    option.value = ras.idras_hewan;
                    option.textContent = ras.nama_ras;
                    if (selectedRasId && ras.idras_hewan == selectedRasId) {
                        option.selected = true;
                    }
                    rasSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Event listeners
    document.getElementById('idjenis_hewan').addEventListener('change', function() {
        if (this.value) {
            loadRasHewan(this.value);
        } else {
            document.getElementById('idras_hewan').innerHTML = '<option value="">Pilih Ras Hewan</option>';
        }
    });

    // Close modal when clicking outside
    document.getElementById('petModal').addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            closeModal();
        }
    });

    // Auto close alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        });
    }, 5000);
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/manajemen_pet.css') }}">
@endpush
@endsection