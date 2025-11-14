@extends('layouts.admin')

@section('title', 'Manajemen Jenis Hewan - RSHP UNAIR')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user_management.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Jenis Hewan</h1>
        </div>

        <a href="{{ route('admin.jenis-hewan.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Jenis Hewan
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- WRAPPER / CARD Premium --}}
    <div class="premium-table">
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Nama Jenis Hewan</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jenisHewan as $jenis)
                        <tr>
                            <td>
                                <div class="user-flex">
                                    <span>{{ $jenis->nama_jenis_hewan }}</span>
                                </div>
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.jenis-hewan.edit', $jenis->idjenis_hewan) }}"
                                       class="btn-action edit" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.jenis-hewan.destroy', $jenis->idjenis_hewan) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="2" class="text-center py-4 text-gray-500">
                                Tidak ada data jenis hewan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>
@endpush
